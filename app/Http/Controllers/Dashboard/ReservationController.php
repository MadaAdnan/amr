<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ExportReservation;
use App\Http\Requests\Dashboard\Reservation\StoreReservationFormRequest;
use App\Mail\ConfirmationMail;
use App\Models\ChildSeat;
use App\Models\ChildSeatReservation;
use App\Models\Company;
use App\Models\Coupon;
use App\Models\PaymentIntentLog;
use App\Services\ReservationStatusService;
use Carbon\Carbon;
use Exception;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Models\FleetCategory;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Airline;
use App\Models\City;
use App\Models\Customer;
use App\Traits\CalculationTrait;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\Mail;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\PricingTrait;
use App\Traits\UtilitiesTrait;

class ReservationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Reservation Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the reservations in the dashboard
    |
    */


    use CalculationTrait, NotificationTrait, PricingTrait, LogErrorAndRedirectTrait, JsonResponseTrait, UtilitiesTrait;


    public function index(Request $request)
    {
        /**
         * Index
         * 
         * Doc: get all reservations
         *
         * @param Illuminate\Http\Request $request filtering
         *
         * @return \Illuminate\View\View
         */

        try {
            #page type index
            $pageType = 'index';

            #get the all the drivers so the user can select from
            $drivers = User::whereHas('roles', function ($query) {
                $query->where('name', 'Driver');
            })->get();

            #Initialize the reservations query builder
            $reservations = Reservation::where('status', "!=", Reservation::Draft)->whereHas('users');

            #filter the reservation according
            $reservations = $this->filter($request, $reservations);

            #paginate the results
            $reservations = $reservations->orderBy('id', 'desc')->paginate(config('general.dashboard_pagination_number'));

            #append query parameters to pagination links
            $reservations->appends(request()->query());

            return view(
                'dashboard.reservations.index',
                compact('reservations', 'pageType', 'drivers')
            );
        } 
        catch (Exception $e) 
        {
            throw $e;
            $this->logErrorAndRedirect($e, 'Error in reservation index: ');
            return back();
        }
    }

    public function create()
    {
        /**
         * Create
         * 
         * Doc: send get the create
         *
         * @return \Illuminate\View\View
         */

        try 
        {
            #To determine the actions for the reservation
            $pageType = 'create';

            #get the necessary data to the 
            $fleetCategories = FleetCategory::get();
            $coupons = Coupon::get();
            $childSeats = ChildSeat::where('status', ChildSeat::STATUS_PUBLISHED)->get();
            $airlines = Airline::get();
            $drivers = User::whereHas('roles', function ($query) {
                $query->where('name', 'Driver');
            })->get();
            $cities = City::where('status', City::STATUS_ACTIVE)->get();
            $companies = Company::get();

            return view(
                'dashboard.reservations.create',
                compact('companies', 'fleetCategories', 'drivers', 'coupons', 'childSeats', 'airlines', 'pageType', 'cities')
            );
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e, 'Error in creating page: ');
            return back();
        }
    }

    public function store(Request $request)
    {
        /**
         * Store
         * 
         * Doc: store the reservation with it's information
         *
         * @param Illuminate\Http\Request $request 
         *
         * @return \Illuminate\View\View
         */

        try 
        {
            #get the customer
            $user = User::where('email', $request->email)->first();
            $customer = $this->getCustomer($user, $request);

            #save the reservation data
            $this->saveReservation($request, $customer);

            #return to the front end with updated status if there is an links with the same info return false
            $responseObj = [
                'msg' => 'reservation was created',
                'status' => config('status_codes.success.created')
            ];

            return $this->successResponse($responseObj, config('status_codes.success.created'));
        } 
        catch (Exception $e) 
        {
            return $this->logErrorAndRedirect($e, 'Error saving the reservation');
        }
    }

    public function edit($id)
    {
        /**
         * Edit
         * 
         * Doc: get the reservation details 
         *
         * @param Integer $id App\Models\Reservation
         *
         * @return \Illuminate\View\View
         */

        try 
        {

            #init the stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));

            #this will decide the page type
            $pageType = 'edit';

            #get the reservation id
            $reservation = Reservation::findOrFail($id);

            #get the data
            $companies = Company::get();
            $selectedChildSeats = $reservation->childSeats;
            $paymentIntentLogs = $reservation->paymentIntentLogs()->paginate(config('general.dashboard_pagination_number'));

            #get data for the edit page
            $fleetCategories = FleetCategory::get();
            $coupons = Coupon::get();
            $airlines = Airline::get();
            $cities = City::where('status', City::STATUS_ACTIVE)->get();
            $childSeats = ChildSeat::where('status', ChildSeat::STATUS_PUBLISHED)->get();
            $customer = $reservation->users;
            $drivers = User::whereHas('roles', function ($query) {
                $query->where('name', 'Driver');
            })->get();
            $riskLevel = null; #for the payment method stripe
            $riskInfo = null; #for the payment method stripe

            #get the last payment intent was paid so we can check the payment method risk
            $paymentIntentId = $reservation->paymentIntentLogs()->pluck('payment_intent_id')->first();
            
            if ($paymentIntentId) {
                #access the Payment Method ID associated with the Payment Intent
                $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

                #access the Payment Method ID associated with the Payment Intent
                $paymentMethodId = $paymentIntent->payment_method;

                #replace 'payment_method_id' with the actual ID of the payment method you want to retrieve
                if ($paymentMethodId) {
                    $paymentMethod = PaymentMethod::retrieve($paymentMethodId);

                    #access risk-related information if available
                    $riskInfo = $paymentMethod->card->checks;

                    #determine risk level based on available checks
                    $riskLevel = $this->determineRiskLevel($riskInfo);
                }
            }

            return view('dashboard.reservations.edit', compact('companies', 'cities', 'drivers', 'reservation', 'fleetCategories', 'coupons', 'childSeats', 'airlines', 'pageType', 'customer', 'paymentIntentLogs', 'selectedChildSeats', 'riskLevel'));

        } 
        catch (\Stripe\Exception\ApiErrorException $e) 
        {
            $this->logErrorAndRedirect($e, 'Stripe API Error ');
            return back();
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            $this->logErrorAndRedirect($e, 'Error finding the reservation in edit dashboard : ');
            return back();
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e, 'Error creating the reservation : ');
            return back();
        }
    }

    public function update(StoreReservationFormRequest $request, $id)
    {
        /**
         * Update
         * 
         * Doc: get the reservation details 
         *
         * @param Integer $id App\Models\Reservation
         * @param App\Http\Requests\Dashboard\Reservation\StoreReservationFormRequest
         *
         * @return \Illuminate\View\View
         */

        try 
        {
            $reservation = Reservation::findOrFail($id);
            $user = User::where('email', $request->email)->first();
            $customer = $this->getCustomer($user, $request);

            $reservation = $this->saveReservation($request, $customer, $reservation);

            #send notification to the fcm
            $usersFcmTokens = [$customer->fcm];
            $chauffeur = isset($reservation->drivers) ? $reservation->drivers->full_name : '';

            #log the updated data
            activity()->performedOn($reservation);

            #return to the front end with updated status
            $responseObj = [
                'msg' => 'Reservation was updated',
                'status' => config('status_code.success.created')
            ];

            return $this->successResponse($responseObj, config('status_codes.success.created'));

        } 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            $this->logErrorJson($e, 'Error in finding the reservation : ');
            return back();
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e, 'Error updating the reservation');
            return back();
        }
    }

    public function show($id)
    {
        /**
         * Show
         * 
         * Doc: get the reservation details 
         *
         * @param Integer $id App\Models\Reservation
         *
         * @return \Illuminate\View\View
         */
        try 
        {
            $reservation = Reservation::findOrFail($id);

            return view('dashboard.reservations.show', compact('reservation'));
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e, 'Error creating the reservation : ');
            return back();
        }
    }

    public function softDelete($id)
    {
        /**
         * Soft Delete
         * 
         * Doc: soft delete the reservation 
         *
         * @param Integer $id App\Models\Reservation
         *
         *
         * @return \Illuminate\Http\RedirectResponse
         */

        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();
            return redirect()->route('dashboard.reservations.index')->with('success', 'The Deletion process has done successful');

        } 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            $this->logErrorAndRedirect($e, 'Error finding the reservation in edit dashboard : ');
            return back();
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e, 'Error finding the reservation in edit dashboard : ');
            return back();
        }
    }

    public function showSoftDelete(Request $request)
    {
        /**
         * Show Soft Delete
         * 
         * Doc: soft delete the reservation 
         *
         * @param Illuminate\Http\Request $request
         *
         *
         * @return \Illuminate\Http\RedirectResponse
         */

        try 
        {
            #page type for the table actions
            $pageType = 'trashed';

            #return deleted reservations
            $reservations = Reservation::onlyTrashed()
                ->orderBy('created_at', 'asc');

            #filter queries
            $searchQuery = $request->query('query');
            $statusQuery = $request->query('status');
            $startDateQuery = $request->query('startDate');
            $endDateQuery = $request->query('endDate');

            if ($searchQuery) {
                $reservations->where(function ($q) use ($searchQuery) {
                    $q->where('id', 'like', '%' . $searchQuery . '%')
                        ->orWhereHas('users', function ($user_q) use ($searchQuery) {
                            $user_q->where('first_name', 'like', '%' . $searchQuery . '%')
                                ->orWhere('last_name', 'like', '%' . $searchQuery . '%')
                                ->orWhere('pick_up_location', 'like', '%' . $searchQuery . '%')
                                ->orWhere('drop_off_location', 'like', '%' . $searchQuery . '%');
                        });
                });
            }
            if ($startDateQuery && $endDateQuery) {
                $reservations->whereBetween('pick_up_date', [$startDateQuery, $endDateQuery])
                    ->orWhereBetween('return_date', [$startDateQuery, $endDateQuery]);
            }

            if ($statusQuery) {
                $reservations->where('status', $statusQuery);
            }



            $reservations = $reservations->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.reservations.trashed', compact('reservations', 'pageType'));
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e, 'getting soft delete data reservation');
            return back();
        }
    }

    public function softDeleteRestore($id)
    {
        /**
         * Soft Delete Restore
         * 
         * Doc: restore the deleted reservation 
         *
         * @param App\Models\Reservation $id
         *
         *
         * @return \Illuminate\Http\RedirectResponse
         */

        try 
        {
            #restore the reservation and redirect the user to the softDelete index page 
            Reservation::onlyTrashed()
                ->findOrFail($id)
                ->restore();

            return redirect()->route('dashboard.reservations.showSoftDelete')->with('success', 'Restore Completed Successfully');
        } 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            $this->logErrorAndRedirect($e, 'Error in finding the reservation in soft delete :');
            return back();
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e, 'error in restoring the reservation');
            return back();
        }
    }

    public function export(Request $request)
    {
        /**
         * Export
         * 
         * Doc: export excel file with  
         *
         * @param Illuminate\Http\Request $request
         *
         *
         * @return Maatwebsite\Excel\Facades\Excel
         */

        try 
        {
            #initialize the reservations query builder
            $reservations = Reservation::query();

            #retrieve query parameters
            $resID = $request->query('resID');
            $firstName = $request->query('firstName');
            $lastName = $request->query('lastName');
            $statusQuery = $request->query('status');
            $startDateQuery = $request->query('startDate');
            $endDateQuery = $request->query('endDate');
            $driverQuery = $request->input('driverID');


            #apply filters
            if ($resID) {
                $reservations->where('id', $resID);
            }
            if ($driverQuery) {
                $reservations->where('driver_id', $driverQuery);
            }

            if ($firstName) {
                $reservations->whereHas('users', function ($query) use ($firstName) {
                    $query->where('first_name', 'like', '%' . $firstName . '%');
                });
            }

            if ($lastName) {
                $reservations->whereHas('users', function ($query) use ($lastName) {
                    $query->where('last_name', 'like', '%' . $lastName . '%');
                });
            }

            #star and end date filter
            if ($startDateQuery && $endDateQuery) {
                $reservations->where(function ($query) use ($startDateQuery, $endDateQuery) {
                    $query->whereBetween('pick_up_date', [$startDateQuery, $endDateQuery])
                        ->orWhereBetween('return_date', [$startDateQuery, $endDateQuery]);
                });
            }

            if ($statusQuery) {
                $reservations->where('status', $statusQuery);
            }

            #get the data need to exported
            $reservations = $reservations
                ->select('id', 'service_type', 'pick_up_date', 'pick_up_time', 'coupon_id', 'category_id', 'price', 'tip', 'price_with_tip', 'created_at')
                ->with('fleets') // Eager load the 'fleets' relationship
                ->get();

            return Excel::download(new ExportReservation($reservations), 'reservations.xlsx');
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e, 'Error exporting the reservation');
            return back();
        }
    }

    public function getPrice($id, Request $request)
    {
        /**
         * Get Price
         * 
         * Doc: get the price according to the selected fleet 
         *
         * @param Integer App\Models\FleetCategory $id
         * @param Illuminate\Http\Request $request
         *
         *
         * @return Json
         */

        try {
            #return to the front end with updated status
            $responseObj = [
                'msg' => 'Data was found',
                'data' => $this->getPriceAccordingToFleet($request, $id)
            ];

            return $this->successResponse($responseObj, config('status_codes.success.ok'));

        } 
        catch (Exception $e) 
        {
            return $this->logErrorJson($e, 'Error getting the price dashboard');
        }
    }

    public function checkReservationInfo(Request $request, $isEdit = null)
    {
        /**
         * Check Reservation Info
         * 
         * Doc: to check the reservation information and check if the data was entered is correct 
         *
         * @param Illuminate\Http\Request $request
         * @param Boolean $isEdit 
         *
         *
         * @return Json
         */


        try 
        {
            #init stripe
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            #init the basic data
            $email = User::select('email')->where('email', $request->email)->first();
            $phone = User::select('phone')->where('phone', $request->phone)->first();
            $customer = null;
            $paymentMessage = null;
            $paymentIntentMessage = false;
            $checkIfCustomerIdIsAvailable = true;
            $paymentLog = PaymentIntentLog::pluck('payment_intent_id')->toArray();

            #check if the paymentintent is already exist in stripe
            if (in_array($request->payment_id, $paymentLog)) 
            {
                $paymentIntentMessage = true;
            }


            #do try catch if the request customer id was sent. if it's in the catch mean it's not available for our stripe account
            if ($request->customerId) 
            {
                try 
                {
                    $customer = \Stripe\Customer::retrieve($request->customerId);
                    #IF THE CUSTOMER ID IS AVAILABLE IN OUR STRIPE ACCOUNT MEAN THAT IS AVAILABLE TO USE 
                    if ($customer) {
                        $checkIfCustomerIdIsAvailable = true;
                    }
                } 
                catch (Exception $e) 
                {
                    $checkIfCustomerIdIsAvailable = false;
                }

                #IF THE CUSTOMER ID WAS USED BY ONE OF OUR USERS IN THE SYSTEM IT WILL BE NOT AVAILABLE TO USE
                $customerTable = Customer::where('stripe_customer_id', $request->customerId)->first();
                $userTable = User::where('stripe_id', $request->stripe_id)->first();

                if ($customerTable || $userTable) {
                    $checkIfCustomerIdIsAvailable = false;
                }
            }

            #check if the payment id was sent check the payment id is attached to the customer account
            if ($request->payment_id) 
            {
                if ($isEdit) 
                {
                    try 
                    {
                        $paymentMessage = \Stripe\PaymentIntent::retrieve($request->payment_id);

                        if ($paymentMessage && $paymentMessage->customer !== $request->customerId) 
                        {
                            $paymentMessage = 'Not associated with the customer id ';

                        } 
                        else 
                        {
                            $paymentMessage = 'Found!';
                        }
                    } 
                    catch (Exception $e) 
                    {
                        $paymentMessage = 'Not associated with the customer id ';
                    }
                }
            }

            #return to the front end with updated status if there is an links with the same info return false
            $responseObj = [
                'data' => [
                    'is_email_exist' => $email ? true : false,
                    'is_phone_exist' => $phone ? true : false,
                    'is_customer_id_available' => $checkIfCustomerIdIsAvailable,
                    'paymentStatus' => $paymentMessage,
                    'payment_Intent_is_exist' => $paymentIntentMessage
                ]
            ];

            return $this->successResponse($responseObj, config('status_codes.success.ok'));

        } 
        catch (Exception $e) 
        {
            return $this->logErrorJson($e, 'Error checking for validation');
        }
    }

    public function autocomplete(Request $request)
    {
        /**
         * Auto complete
         * 
         * Doc: give the user account that available in the database according to the email
         *
         * @param Illuminate\Http\Request $request
         *
         *
         * @return Json
         */

        try 
        {
            $responseObj = User::where('email', 'LIKE', '%' . $request->get('email') . '%')->get();
            return $this->successResponse($responseObj, config('status_codes.success.ok'));
        } 
        catch (Exception $e) 
        {
            return $this->logErrorJson($e, 'autocomplete error');
        }
    }

    public function fetchCustomerIDs(Request $request)
    {
        /**
         * Fetch Customers ids
         * 
         * Doc: get the customer ids in stripe according to the email
         *
         * @param Illuminate\Http\Request $request
         *
         *
         * @return Json
         */

        try 
        {
            #init stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));

            #get the email search for it
            $email = $request->input('email');

            $customers = \Stripe\Customer::all(['email' => $email]);

            $customerIds = [];

            #loop throw it and get just the ids
            foreach ($customers->autoPagingIterator() as $customer) {
                $customerIds[] = $customer->id;
            }

            $responseObj = $customerIds;

            return $this->successResponse($responseObj, config('status_codes.success.ok'));
        } 
        catch (\Stripe\Exception\ApiErrorException $e) 
        {
            return $this->logErrorJson($e, 'error fetching ids');
        }
    }

    private function determineRiskLevel($riskInfo)
    {
        /**
         * Determine Risk Level
         * 
         * Doc: get the customer ids in stripe according to the email
         *
         * @param Object $riskInfo
         *
         *
         * @return String
         */
        if ($riskInfo && isset($riskInfo->cvc_check) && $riskInfo->cvc_check === 'fail') 
        {
            return 'high'; #CVV check failed, indicating potential fraud

        } 
        elseif ($riskInfo && isset($riskInfo->avs_check) && $riskInfo->avs_check === 'fail') 
        {
            return 'elevated'; #Address verification failed, indicating potential risk

        } 
        elseif ($riskInfo && isset($riskInfo->risk_level)) 
        {
            return $riskInfo->risk_level; #Use the risk level provided by Stripe
        } 
        else 
        {
            return 'normal'; #Default to normal if no specific risk information available
        }
    }

    private function filter($request, $reservation_query)
    {
        /**
         * Filter
         * 
         * Doc: filter data according to the request
         * 
         * @param Illuminate\Http\Request $request filtering data
         * @param App\Models\Reservation 
         * 
         * 
         * 
         * @return App\Models\Reservation
         */

        try {

            //retrieve query parameters
            $resID = $request->query('resID');
            $companyID = request()->query('companyID');
            $firstName = $request->query('firstName');
            $lastName = $request->query('lastName');
            $statusQuery = $request->query('status');
            $startDateQuery = $request->query('startDate');
            $endDateQuery = $request->query('endDate');
            $driverQuery = $request->input('driverID');
            $hideFailedQuery = $request->input('hideFailed');

            #apply filters
            if ($resID) {
                $reservation_query->where('id', $resID);
            }
            if ($companyID) {
                $reservation_query->where('company_id', $companyID);
            }
            if ($driverQuery) {
                $reservation_query->where('driver_id', $driverQuery);
            }

            if ($firstName) {
                $reservation_query->whereHas('users', function ($query) use ($firstName) {
                    $query->where('first_name', 'like', '%' . $firstName . '%');
                });
            }

            if ($lastName) {
                $reservation_query->whereHas('users', function ($query) use ($lastName) {
                    $query->where('last_name', 'like', '%' . $lastName . '%');
                });
            }

            if ($startDateQuery && $endDateQuery) {
                $reservation_query->where(function ($query) use ($startDateQuery, $endDateQuery) {
                    $query->whereBetween('pick_up_date', [$startDateQuery, $endDateQuery])
                        ->orWhereBetween('return_date', [$startDateQuery, $endDateQuery]);
                });
            }
            if($hideFailedQuery)
            {
                $reservation_query->where('status','!=', Reservation::FAILED);
            }
            if ($statusQuery) 
            {
                $reservation_query->where('status', $statusQuery);
            }

            return $reservation_query;
        } catch (Exception $e) {
            $this->logErrorAndRedirect($e, 'error in filtering : ');
        }
    }

    private function saveReservation($request, $customer, $reservation_modal = null)
    {
        /**
         * Save Reservation
         * 
         * Doc: save the reservation
         * 
         * @param Illuminate\Http\Request $request
         * @param App\Models\User $customer
         * @param App\Models\Reservation $reservation_modal
         *  
         *  
         *  
         * @return App\Models\Reservation 
         */

        #get the init vars
        $coupon = Coupon::find($request->coupon_code);
        $selectedChildSeats = $request->seats;
        $todayDate = Carbon::now(); // use to check if the coupon is active or not
        $editPrice = $request->has('edit_price') ? 1 : 0; // to determine if edit price or not
        $fcmTokens = [$customer->fcm];

        #get child seats
        $seatValues = $request->seats ? array_column($request->seats, "seat") : [];
        $childSeatsDataBase = ChildSeat::whereIn('id', $seatValues)->pluck('title')->toArray();
        $changeChildSeatsToString = is_array($request->seats) ? implode(",", $childSeatsDataBase) : null;

        #if round trip divided by two
        $checkPriceForRoundTrip = $request->transfer_type == 'Round' ? $request->price / 2 : $request->price;

        #update coupon data if there was one updated
        if ($coupon && $coupon->active_from->gte($todayDate)) {
            $coupon->update([
                'usage_limit' => $coupon->usage_limit != 0 ? $coupon->usage_limit - 1 : 0
            ]);
        };

        #fill reservation data
        $merge = array_merge($request->all(), [
            'dropoff_latitude' => $request->drop_latitude,
            'dropoff_longitude' => $request->drop_longitude,
            'edit_price' => $editPrice,
            'pick_up_date' => $request->start_date,
            'pick_up_time' => $request->start_time,
            'user_id' => $customer->id,
            'category_id' => $request->fleet_category,
            'phone_primary' => $request->phone,
            'mile' => $request->miles ?? 0,
            'price' => $checkPriceForRoundTrip,
            'price_with_tip' => $checkPriceForRoundTrip,
            'driver_id' => $request->chaffeur,
            'fleet_id' => $request->fleet,
            'child_seats' => $changeChildSeatsToString,
            'coupon_id' => $coupon ? $coupon->id : null,
            'city_id' => $request->city,
            'pickup_sign'=>$request->pickup_sign,
            'email_for_confirmation'=>$request->email_for_confirmation
        ]);

        #if the reservation modal was sent handel it like an update
        if ($reservation_modal) {
            $reservation = $reservation_modal;
            $reservation->update($merge);

            #remove the old child seats from the reservation
            if ($request->seats) {
                ChildSeatReservation::where('reservation_id', $reservation->id)->delete();
            }

            #if the reservation was round and the admin change it to one way the other reservation will be deleted
            if ($reservation->transfer_type == 'Round' && $request->transfer_type == 'One Way') {
                Reservation::where('parent_reservation_id', $reservation->id)->delete();
            }

            #send confirmation email for the update if it was selected
            if ($request->input('sendEmailConfirmation')) {
                $tripStatusService = new ReservationStatusService();
                $tripStatusService->sendNotifications($reservation->id, "update", null);
            }
        } 
        else 
        {
            $reservation = Reservation::create($merge);
        }

        #add child seats to the reservation
        if ($selectedChildSeats && is_array($selectedChildSeats)) 
        {
            $this->attachChildSeats($selectedChildSeats, $reservation->id);
        }

        #round trip handling and send email if the send email confirmation was checked
        if ($request->transfer_type == 'Round') {
            #round trip data
            $merge = array_merge($merge, [
                'latitude' => $request->drop_latitude,
                'longitude' => $request->drop_longitude,
                'pick_up_date' => $request->return_date,
                'pick_up_time' => $request->return_time,
            ]);
            

            $roundTrip = Reservation::create($merge);

            #send email for the round trip
            if ($request->input('sendEmailConfirmation')) {
                $tripStatusService = new ReservationStatusService();
                $tripStatusService->sendNotifications($roundTrip->id, "new reservation",  $roundTrip->email_for_confirmation??$roundTrip->users->email);
            }

            #send notification for the round trip if the user was enabled it in the settings
            if ($this->sendNotificationSetting($customer) && !$reservation_modal) {
                $this->sendCreateReservationNotification($roundTrip, $fcmTokens, $customer);
            }

            #add child seats to the 
            if ($selectedChildSeats && is_array($selectedChildSeats)) {
                $this->attachChildSeats($selectedChildSeats, $roundTrip->id);
            }
        }

        #sending emails for the reservation
        if ($request->input('sendEmailConfirmation')) 
        {
            $tripStatusService = new ReservationStatusService();
            
            #if the reservation already exist send an update reservation email
            if($reservation_modal)
            {
                $tripStatusService->sendNotifications($reservation->id, "update", $reservation->email_for_confirmation??$reservation->users->email);
            }
            else
            {
                $tripStatusService->sendNotifications($reservation->id, "new reservation", $reservation->email_for_confirmation??$reservation->users->email);
            }
        }


        #send notification to the user
        if ($this->sendNotificationSetting($customer) && !$reservation_modal) {
            $this->sendCreateReservationNotification($reservation, $fcmTokens, $customer);
        }
        return $reservation;
    }

    private function getCustomer($customer, $request)
    {
        /**
         * Get Customer
         * 
         * Doc: get the customer info and if the customer dose't exist or dose't have stripe id 
         * generate one 
         * 
         * @param App\Models\Customer $customer
         * @param Illuminate\Http\Request $request
         * 
         * 
         * @return App\Models\Customer
         */

        #get customer
        $customerId =  $request->customerId ? $request->customerId : $this->generateCustomerId($request->email, $request->first_name . ' ' . $request->last_name);

        #check customer info and if it's not available create one
        if (!$customer) 
        {
            #generate password for the customer
            $password = $this->userGeneratePassword(5);

            #check the old table and see if it's an old customer
            $customer = Customer::where('email', $request->email)->first();

            #save the user data
            $userData = [
                'first_name' => $customer ? $customer->first_name : $request->first_name,
                'last_name' => $customer ? $customer->last_name : $request->last_name,
                'email' => $customer ? $customer->email : $request->email,
                'password' => bcrypt($password),
                'country_code' => $request->country_code,
                'phone' => $request->phone,
                'stripe_id' => $customerId
            ];
            $customer = User::create($userData);

            #generate token so the so it will be sent to the user
            $token = $customer->createToken($customer->id . date('Y-m-d H:i:s'))->accessToken;

            #send the generated password to the user
            $resetPasswordEmail = new ConfirmationMail($password, $token, $customer);
            Mail::to($request->email)->send($resetPasswordEmail);
        }

        #if the customer don't have a stripe id generate one
        if ($customer->stripe_id == null) {
            $customer->stripe_id = $customerId;
            $customer->update();
        }

        return $customer;
    }

    private function attachChildSeats($selectedChildSeats, $id)
    {
        /**
         * Attach Child Seats
         * 
         * Doc: to attach child seats to the reservation
         * 
         * @param Array $selectedChildSeats
         * @param Integer App\Models\Reservation
         * 
         * 
         * @return void;
         */

        foreach ($selectedChildSeats as $index => $item) 
        {
            $item = (object) $item;
            $check = ChildSeatReservation::where([['reservation_id', $id], ['child_seats_id', $item->seat]])->first();

            #if the child seats already attached to the reservation plus the amount by one else create new one
            if ($check) 
            {
                $check->update([
                    'amount' => $item->amount + 1
                ]);
            } 
            else 
            {
                $data = [
                    'reservation_id' => $id,
                    'amount' => $item->amount,
                    'child_seats_id' => $item->seat
                ];
                ChildSeatReservation::create($data);
            }
        }

        return;
    }
    
}
