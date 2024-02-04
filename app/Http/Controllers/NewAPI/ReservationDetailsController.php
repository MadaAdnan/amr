<?php

namespace App\Http\Controllers\NewAPI;

use App\Models\PaymentIntentLog;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Http\Requests\Api\CreateClientSecretFromPaymentIntentRequest;
use App\Http\Requests\Api\DeleteReservationRequest;
use App\Http\Requests\Api\GetPriceRequest;
use App\Http\Requests\Api\ReportReservationRequest;
use App\Models\ChildSeatReservation;
use App\Models\Coupon;
use App\Models\Airline;
use App\Models\ChildSeat;
use App\Models\City;
use App\Models\NewBill;
use App\Models\Reservation;
use App\Models\ServiceLocationRestriction;
use Carbon\Carbon;
use App\Traits\CalculationTrait;
use App\Traits\NotificationTrait;
use Exception;
use Auth;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use App\Notifications\TestAction;
use App\Services\ReservationStatusService;
use App\Traits\EmailTrait;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\PricingTrait;
use App\Traits\ReservationResponse;
use Illuminate\Http\Request;

class ReservationDetailsController extends Controller
{
    use CalculationTrait, NotificationTrait, PricingTrait , EmailTrait , ReservationResponse,LogErrorAndRedirectTrait,JsonResponseTrait;

    public function index()
    {
        /**
         * Index
         * 
         * 
         * 
         * Doc: get the reservation information
         */

        try 
        {

            $duration = config('general.hours_duration');
            $airlines = Airline::all()->makeHidden(['created_at', 'updated_at']);

            $childSeats = ChildSeat::where('status', 'Published')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'price' => $item->price,
                ];
            });
           

            return $this->NewResponse(true, [
                'childSeats' => $childSeats,
                "duration" => $duration,
                "airlines" => $airlines,
            ], null, config('status_codes.success.ok'));

        } 
        catch (Exception $e) 
        {
            return $this->logErrorJson($e,'Error getting reservation details: ');
        }

    }

    public function getPrices(GetPriceRequest $request)
    {
        /**
         * Doc
         *  service type 1 = Point to point
         *  service type 2 = Hourly
         */
        
        try {

            #Check if the coming date is correct and in the future if not return validation error
            $today = $this->setTimezoneGmt(config('general.default_time_zone'));
            $pickUpTimeValidation = $request->pick_up_date . "" . $request->pick_up_time;
            $targetDateCarbon = Carbon::parse($pickUpTimeValidation);

            
            return $this->NewResponse(true, $this->getPrice($request), null, 200);

        } 
        catch (Exception $e) 
        {
            throw $e;
            return $this->NewErrorResponse('Error getting the price', 422);
        }
    }

    function isUserInsideRadiusMiles($userLat, $userLon, $centerLat, $centerLon, $radiusKM)
    {

        $userLat = (float) $userLat;
        $userLng = (float) $userLon;
        $centerLat = (float) $centerLat;
        $centerLon = (float) $centerLon;

        $earthRadius = 6371000; // Radius of the Earth in meters

        $dLat = deg2rad($centerLat - $userLat);
        $dLng = deg2rad($centerLon - $userLng);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($userLat)) * cos(deg2rad($centerLat)) * sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; // Distance in meters

        return $distance <= $radiusKM;

        //return $inside;
    }

    private function createPaymentIntent($total_amount, $customer_id, $card_id)
    {
        try 
        {
            $price = intval($total_amount * 100);

            /** Work in progress */
            $billing_address = [
                'line1' => '123 Main St',
                'city' => 'City',
                'state' => 'CA',
                'postal_code' => '12345',
                'country' => 'US',
            ];
            
            $paymentIntent = PaymentIntent::create([
                'amount' => $price,
                'currency' => 'usd',
                'customer' => $customer_id,
                'capture_method' => 'manual',
                'payment_method' => $card_id,
            ])->confirm();
            
            // Return the PaymentIntent object if successful
            return $paymentIntent;
        } 
        catch (\Stripe\Exception\ApiErrorException $e) {
            throw $e;
            return null;
        }
    }

    private function transformRequestData($trip_number,$overAllPrice, $request, $user, $pickUpTime24Hour, $returnTime24Hour, $coupons, $applePaymentToken, $paymentIntent)
    {
        $tip = $request->is_round ? $request->tip / 2 : $request->tip;
        $data = [
            'child_seats' => isset($request->availableSeats) ? implode(",", $request->availableSeats) : null,
            'user_id' => $user->id,
            'price_with_tip' => $overAllPrice + $tip,
            'pick_up_time' => ($trip_number > 1) ? $returnTime24Hour : $pickUpTime24Hour,
            'return_time' => null,
            "coupon_id" => $coupons ? $coupons->id : null,
            'payment_intent_id' => $applePaymentToken ? $applePaymentToken : $paymentIntent->id,
            'mile' => $request->distance
        ];


        if ($request->is_round_trip) {
            if ($trip_number == 1) {
                ++$trip_number;
                $data['pick_up_date'] = $request->pick_up_date;
                $data['pick_up_location'] = $request->pick_up_location;
                $data['drop_off_location'] = $request->drop_off_location;

            } else {
                // Include additional fields for round trip
                $data['pick_up_date'] = $request->return_date;
                $data['pick_up_location'] = $request->drop_off_location;
                $data['drop_off_location'] = $request->pick_up_location;

            }

        }

        return array_merge($request->all(), $data);

    }

    function createBillAndPaymentLog($billNumber, $user, $totalAmount, $reservationId, $request, $coupons, $card = null)
    {
        try {
            $cardBrand = $billNumber === 'Apple Pay' ? 'Apple Pay' : ($card ? $card->brand : 'Apple Pay');
            $lastFour = $billNumber === 'Apple Pay' ? 'Apple Pay' : ($card ? $card->last4 : 'Apple Pay');

            NewBill::create([
                'bill_number' => $billNumber,
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'reservation_id' => $reservationId,
                'card_brand' => $cardBrand,
                'last_four' => $lastFour,
                'coupon_id' => $coupons ? $coupons->id : null,
            ]);

            PaymentIntentLog::create([
                'payment_intent_id' => $billNumber,
                'reservation_id' => $reservationId,
                'price' => $request->price,
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function notification($user,$reservation){

        $usersFcmTokens = [$user->fcm];
        $reservation = Reservation::find($reservation->id);

        //appears in history
        $data = [
            'title' => 'Trip # ' . $reservation->id . ' Created!',
            'text' => " Your trip will be reviewed and accepted shortly",
            'type' => 'trip',
            'reservation_id' => $reservation->id,
            'reservation_status' => $reservation->status,
            'data' => $reservation->id,

        ];

        if ($this->sendNotificationSetting($user)) {
            $user->notify(new TestAction($data));

            //what appears in out side

            $this->sendNotifications(
                $usersFcmTokens,
                [
                    "title" => 'Trip # ' . $reservation->id . ' Created!',
                    "body" => 'Your trip will be reviewed and accepted shortly',
                    "icon" => "icon-url",
                    'reservation_id' => $reservation->id,
                    'type' => 'in_app_message',
                    // "click_action" => "action-url"
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",

                    'android' => [
                            'channelId' => 'high_priority_alerts',
                        ],
                ]
            );
        }
    }

    public function checkout(CheckoutRequest $request)
    {
        /**
         * Info:
         *  Take the price and send it to stripe
         *  Handel Apple pay
         *  Check if there is any coupon
         *  Save The Trip
         */

        $pickUpTime = Carbon::createFromFormat('h:i A', $request->pick_up_time);
        $pickUpTime24Hour = $pickUpTime->format('H:i');
        $returnTime = null;
        $returnTime24Hour = null;
        $finalArray = [];


        if ($request->return_time) {
            $returnTime = Carbon::createFromFormat('h:i A', $request->return_time);
            $returnTime24Hour = $returnTime->format('H:i');
        }

        $user = Auth::user();
        $price = floatval($request->price);
        $overAllPrice = floatval($price);

        $priceWithTip = floatval($price + $request->tip);

        $applePaymentToken = $request->paymentIntent_Id;
        $customer_id = $request->customer_id;
        $paymentIntent = null;



        $coupons = Coupon::where('coupon_code', $request->coupon)->first();

        /** CHECK FOR COUPONS */
        $today = Carbon::today();
        if ($coupons && $coupons->usage_limit != 0 && ($coupons->active_from->isPast($today) || $coupons->active_to->isSameDay($today)) && !$coupons->active_to->lt($today)) 
        {

            if ($coupons->discount_type == 'Percentage') 
            {
                $percent = $coupons->percentage_discount;
                $percentAmount = $overAllPrice * ($percent / 100);
            } 
            else 
            {
                $percentAmount = $coupons->percentage_discount;
            }

            $overAllPrice = $overAllPrice - $percentAmount;

            $coupons->update([
                'usage_limit' => $coupons->usage_limit - 1
            ]);
        } 
        else 
        {
            $coupons = null;
        }

        #database pricing
        $reservationPrice = floatval($request->is_round_trip ? $overAllPrice / 2 : $overAllPrice);
        $tipPricing = floatval($request->is_round_trip ? $request->tip / 2 : $request->tip);
        $priceWithTip = floatval(($request->is_round_trip ? $overAllPrice + $request->tip : $overAllPrice + $request->tip));

        // $request['price'] = $reservationPrice;
        
        $request['price'] = $price;
        $request['tip'] = $tipPricing;
        $request['price_with_tip'] = $priceWithTip;


        if (!$applePaymentToken && !$request->card_id) 
        {
            return $this->NewErrorResponse('Please add payment method', 422);
        }

        /** Stripe */
        try 
        {

            //sets up the Stripe API key using the provided secret key
            Stripe::setApiKey(env('STRIPE_SECRET'));

            /** CREATE AN INVOICE FOR THE CUSTOMER  AND CHANGE THE PRICE FROM DOLLARS TO CENT*/
            try 
            {
                $total_amount = $priceWithTip;

                /** IF CUSTOMER PAID USING APPLE PAY */
                
                if (!$request->is_round_trip) 
                {
                    if (!$applePaymentToken && $request->card_id) {

                        /** CREATE PAYMENT IN STRIPE */
                        $paymentIntent = $this->createPaymentIntent($total_amount, $customer_id, $request->card_id);
                    }
                } 
                else 
                {
                    if (!$applePaymentToken && $request->card_id) 
                    {
                        /** CREATE PAYMENT IN STRIPE */
                        $paymentIntent = $this->createPaymentIntent($total_amount, $customer_id, $request->card_id);
                    }
                }


            } 
            catch (Exception $e) 
            {
                
                return $this->NewErrorResponseApi($e->getMessage(), 500);
            }

        } 
        catch (Exception $e) 
        {
            throw $e;
            return $this->NewErrorResponse('Error with payment', 500);
        }

        /** Create a trip */
        try 
        {

            $user = Auth::user();

            // Get the selected child seat types from the request (child_seats[0], child_seats[1], etc.)
            $selectedSeats = $request->input('child_seats', []);

            // Check if the selected seat types exist in the database
            $availableSeats = ChildSeat::whereIn('id', $selectedSeats)->pluck('title');
            // Calculate the count of each selected seat type
            foreach ($selectedSeats as $seatType) {
                 $childSeat = ChildSeat::find($seatType);
                 $childSeatTitle = $childSeat->title;
                if (in_array($childSeatTitle, $availableSeats->toArray())) {
                    $nameCounts = array_count_values($request->input('child_seats', []));

                    $finalArray = collect($nameCounts)->map(function ($count, $id) {
                        $ChildSeat = ChildSeat::where('id', $id)->first();

                        return [
                            'title' => $ChildSeat->title,
                            'amount' => $count,
                            'child_seats_id' => $ChildSeat->id,
                        ];
                    })->values()->toArray();




                } else {
                    return $this->NewResponse(false, "Child seat type '$seatType' is not available.", null, 422);
                }
            }

            /**=============================== Create a trip if not round trip======================== */
             $request->availableSeats = $availableSeats->toArray();
            if (!$request->is_round_trip) {
                

                $transformedData = $this->transformRequestData(0,$overAllPrice, $request, $user, $pickUpTime24Hour, $returnTime24Hour, $coupons, $applePaymentToken, $paymentIntent);

                $reservation = Reservation::create($transformedData);
                $reservation = Reservation::find($reservation->id);

                foreach ($finalArray as $name => $value) {
                    ChildSeatReservation::create([
                        'reservation_id' => $reservation->id,
                        'amount' => $value['amount'],
                        'child_seats_id' => $value['child_seats_id'],
                    ]);
                }
                /**================== GET THE CARD INFO=================== */
                if (!$applePaymentToken) {

                    $paymentMethodId = $paymentIntent->payment_method;
                    $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
                    $card = $paymentMethod->card;
                    $this->createBillAndPaymentLog($paymentIntent->id, $user, $total_amount, $reservation->id, $request, $coupons, $card);
                    $this->notification($user,$reservation);

                  
                    $tripStatusService = new ReservationStatusService();

                   $tripStatusService->sendNotifications($reservation->id, "new reservation", $reservation->users->email);

                    return $this->NewResponse(true, "Thank you for choosing lavish ride", null, 200);

                } 
                else 
                {

                    /** SAVE BILL IN THE*/
                    $this->createBillAndPaymentLog($applePaymentToken, $user, $total_amount, $reservation->id, $request, $coupons);

                    $this->notification($user,$reservation);
                    $tripStatusService = new ReservationStatusService();

                   $tripStatusService->sendNotifications($reservation->id, "new reservation", $reservation->users->email);
                    return $this->NewResponse(true, (string) $reservation->id, null, 200);
                }

            }
            //round trip
            else {

                $trip_number = 1;
                $overAllPrice= $overAllPrice/2;
                $transformedData = $this->transformRequestData($trip_number,$overAllPrice, $request, $user, $pickUpTime24Hour, $returnTime24Hour, $coupons, $applePaymentToken, $paymentIntent);

                ++$trip_number;

                $reservation = Reservation::create($transformedData);

                foreach ($finalArray as $name => $value) {
                    ChildSeatReservation::create([
                        'reservation_id' => $reservation->id,
                        'amount' => $value['amount'],
                        'child_seats_id' => $value['child_seats_id'],
                    ]);
                }
                if (!$applePaymentToken) {

                    /** GET THE CARD INFO */
                    $paymentMethodId = $paymentIntent->payment_method;
                    $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
                    $card = $paymentMethod->card;
                    $this->createBillAndPaymentLog($paymentIntent->id, $user, $total_amount, $reservation->id, $request, $coupons, $card);

                    $this->notification($user,$reservation);
                    $tripStatusService = new ReservationStatusService();

                    $tripStatusService->sendNotifications($reservation->id, "new reservation", $reservation->users->email);
                } else {

                    /** SAVE BILL IN THE*/
                    $this->createBillAndPaymentLog($applePaymentToken, $user, $total_amount, $reservation->id, $request, $coupons);

                   $this->notification($user,$reservation);
                    $tripStatusService = new ReservationStatusService();

                   $tripStatusService->sendNotifications($reservation->id, "new reservation", $reservation->users->email);
                }


                //============ the Second create==========

                $transformedData = $this->transformRequestData($trip_number,$overAllPrice, $request, $user, $pickUpTime24Hour, $returnTime24Hour, $coupons, $applePaymentToken, $paymentIntent);
                $reservation = Reservation::create(array_merge($transformedData,[
                    'parent_reservation_id'=>$reservation->id
                ]));


                foreach ($finalArray as $name => $value) {
                    ChildSeatReservation::create([
                        'reservation_id' => $reservation->id,
                        'amount' => $value['amount'],
                        'child_seats_id' => $value['child_seats_id'],
                    ]);
                }
                if (!$applePaymentToken) {

                    /** GET THE CARD INFO */
                    $paymentMethodId = $paymentIntent->payment_method;
                    $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
                    $card = $paymentMethod->card;
                    $this->createBillAndPaymentLog($paymentIntent->id, $user, $total_amount, $reservation->id, $request, $coupons, $card);
                    $this->notification($user,$reservation);
                    $tripStatusService = new ReservationStatusService();

                   $tripStatusService->sendNotifications($reservation->id, "new reservation", $reservation->users->email);
                } else {

                    /** SAVE BILL IN THE*/
                    $this->createBillAndPaymentLog($applePaymentToken, $user, $total_amount, $reservation->id, $request, $coupons);
                    $this->notification($user,$reservation);
                    $tripStatusService = new ReservationStatusService();

                   $tripStatusService->sendNotifications($reservation->id, "new reservation", $reservation->users->email);
                    return $this->NewResponse(true, (string) $reservation->id, null, 200);
                }


               

                $tripStatusService = new ReservationStatusService();

               $tripStatusService->sendNotifications($reservation->id, "new reservation", $reservation->users->email);


                return $this->NewResponse(true, "Thank you for choosing lavish ride", null, 200);
            }
        } catch (Exception $e) 
        {
            return $this->logErrorJson($e,'Error adding the reservation', 500);
        }
    }

    public function create_client_secret_from_payment_intent(CreateClientSecretFromPaymentIntentRequest $request)
    {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => floatval($request->price * 100) ,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'capture_method' => 'manual',
                'customer' => Auth::user()->stripe_id
            ]);
            $clientSecret = $paymentIntent->client_secret;

            return $this->NewResponse(true, [
                'client_secret' => $clientSecret,
                'id' => $paymentIntent->id
            ], "");

        } catch (Exception $e) {
            return $this->NewErrorResponse($e->getMessage(), 500);
        }
    }

    public function get_coupon($code)
    {
        try {
            $today = Carbon::today();
            $data = Coupon::where('coupon_code', $code)->first();
            if (!$data) {
                return $this->NewErrorResponse('Coupon code not found');
            }
            if ($data && $data->usage_limit != 0 && ($data->active_from->isPast($today) || $data->active_to->isSameDay($today)) && !$data->active_to->lt($today)) {
                return $this->NewResponse(true, ['discount' => $data->percentage_discount, 'discount_type' => $data->discount_type], "");
            } else {
                return $this->NewErrorResponse('This coupon has exceeded the limit of usage or expired');
            }

        } catch (Exception $e) {
            return $this->NewErrorResponse('Error getting coupon code');
        }
    }

    public function delete(DeleteReservationRequest $request, $id)
    {
        try {
            if ($request->delete == 1) {

                // Find the reservation by ID
                $reservation = Reservation::find($id);

                if (!$reservation) {
                    return $this->NewResponse(true, 'Reservation was not found', null, 404);
                }

                $paymentIntentId = $reservation->payment_intent_id;

                if ($paymentIntentId) {
                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                    $paymentIntent = PaymentIntent::retrieve($paymentIntentId);


                    // Check if the payment intent is in an incomplete state (e.g., 'requires_payment_method')
                    if ($paymentIntent->status === 'requires_payment_method') {
                        // Cancel the payment intent in Stripe
                        $paymentIntent->cancel();

                        // Now you can delete the reservation and associated invoice
                        DB::transaction(function () use ($reservation) {
                            $reservation->delete();
                            NewBill::where('reservation_id', $reservation->id)->delete();
                        });

                        return $this->NewResponse(true, 'Reservation and associated incomplete payment deleted', null, 200);
                    }
                }

                // If there's no payment intent ID or it's not incomplete, delete the reservation only
                $reservation->delete();

            } else {
                $user = Auth::user();
                $reservation = Reservation::find($id);

                if (!$reservation) {
                    return response()->json([
                        'success' => false,
                        'message' => '',
                        'error' => 'reservation not found',
                    ], 422);
                }
                $usersFcmTokens = [$user->fcm];
                $data = [
                    'title' => 'Trip # ' . $reservation->id . ' Created!',
                    'text' => " Your trip will be reviewed and accepted shortly",
                    'type' => 'trip',
                    'reservation_id' => $reservation->id,
                    'reservation_status' => $reservation->status,
                ];
                $user->notify(new TestAction($data));

                $this->sendNotifications(
                    $usersFcmTokens,
                    [
                        "title" => 'Trip # ' . $reservation->id . ' Created!',
                        "body" => 'Your trip will be reviewed and accepted shortly',
                        "icon" => "icon-url",
                        'reservation_id' => $reservation->id,
                        'type' => 'in_app_message',
                        // "click_action" => "action-url"
                        "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                        'android' => [
                            'channelId' => 'high_priority_alerts',
                        ],
                    ]
                );
            }

            return $this->NewResponse(true, 'Reservation updated', null, 200);
        } catch (Exception $e) {
            throw $e;
            return $this->NewErrorResponse('Error deleting the reservation', 500);
        }
    }

    public function check_service_availability(Request $request)
    {
        try {
            $pickUpAvailability = null;
            $servicePickUp = null;

            $pick_up_lat = $request->input('pick_up_lat');
            $pick_up_long = $request->input('pick_up_long');


            /**
             * Get all restriction
             * and check if the user is inside the radius
             */
            $restrictions = ServiceLocationRestriction::where('status', 'active')->get();

            foreach ($restrictions as $key => $value) {
                $isInsidePickUp = $this->isUserInsideRadiusMiles($pick_up_lat, $pick_up_long, $value->latitude, $value->longitude, $value->radius);
                if ($isInsidePickUp) {
                    $pickUpAvailability = $value->service;
                    $servicePickUp = $value->service_limitation;
                }
            }

            $checkPointToPoint = $pickUpAvailability == 'point_to_point' || $pickUpAvailability == 'both' ? false : true;
            $checkHourly = $pickUpAvailability == 'hourly' || $pickUpAvailability == 'both' ? false : true;
            $checkPickUp = $servicePickUp == 'pick_up' || $servicePickUp == 'both' ? false : true;
            $checkDropOff = $servicePickUp == 'drop_off' || $servicePickUp == 'both' ? false : true;

            return $this->NewResponse(true, [
                'point_to_point' => $checkPointToPoint,
                'hourly' => $checkHourly,
                'pick_up' => $checkPickUp,
                'drop_off' => $checkDropOff

            ], null, 200);

        } catch (Exception $e) {
            return $this->NewErrorResponse('Server Error!');
        }
    }

    public function get_city_timezone($city_name = null)
    {
        /**
         * Doc: Return the time zone for the device 
         */

        $data = City::where('title', $city_name)->first();

        /** if there is no city return null */
        $timeNow = $this->setTimezoneGmt($data && $data->timezone ? $data->timezone : config('general.default_time_zone'));


        return $this->NewResponse(true, [
            'city_exist' => $data ? true : false,
            'time' => $timeNow->toDateTimeString(),
        ], null, config('status_codes.success.ok'));
    }

    public function getCustomerReservations(Request $request)
    {
         /**
        * Get Customer Trips
        * 
        * Doc: get customer reservations 
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $user = Auth::user();
        
            #get trips
            $trips = $user->reservations()->orderBy('created_at','desc');
            $statusQuery = $request->query('status');
    
    
            #filter according to query
            if (isset($statusQuery)) 
            {
                
                if ($statusQuery == "Upcoming") 
                {
                    $trips->whereIn('status', config('general.upcoming_status_tap_mobile'));
                }
                elseif ($statusQuery == "Canceled")
                {
                    $trips->whereIn('status', config('general.canceled_status_tap_mobile'));
                }
                elseif ($statusQuery == "Completed")
                {
                    $trips->whereIn('status', config('general.completed_status_tap_mobile'));
                } 
                else 
                {
                    $trips->where('status', $statusQuery);
                }
            }

           $trip =  $trips->paginate(config('general.api_pagination'))->map(function($item){
                return $this->reservationResponse($item);
           });
    
            return $this->NewResponse(true , $trip, null , config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in get customer trips: ');
        }
      
    }

    public function getInfo($id)
    {
         /**
        * Get Customer Trips
        * 
        * Doc: get the reservation details
        *
        * @param Integer $id App\Models\Reservation
        *
        * @return Json
        */

        try
        {
            $user = Auth::user();
            $trips = $user->reservations()->findOrFail($id);
            
            if (!$trips) 
            {
                return $this->NewResponse(false , null , 'The trip is not found' , config('status_codes.client_error.not_found'));
            }

            return $this->NewResponse(true , $this->reservationResponse($trips,true) ,  null ,config('status_codes.success.ok'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
        {
            return $this->logErrorJson($e,"Error not finding reservation api: ");

        } 
        catch(Exception $e)
        {
            return $this->logErrorJson($e,"Get trip details: ");
        }
    }

    public function reportReservation(ReportReservationRequest $request)
    {
         /**
        * Report a trip
        * 
        * Doc: report a reservation
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $user = Auth::user();
            
            #send email
            $this->sendReportReservationEmail($request->trips_id,$user,$request->message);

            return $this->NewResponse(true , config('general.reporting_message') ,  null ,config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,"Error in reporting reservation: ");
        }
    }

    public function cancelTrip($id)
    {
         /**
        * Cancel Trip
        * 
        * Doc: cancel the trip and if it's van waite 7 days is 24 hours for the normal
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {

            $user= Auth::user();

            #get the user 
            $data = $user->reservations()->findOrFail($id);

            $data->status = 'late canceled';

            #get the time info
            $time = $data->pick_up_date->format('Y-m-d').' '.$data->pick_up_time;
            $pick_up_date = new Carbon($time);
            $checkHours = !Carbon::now()->isAfter($pick_up_date)?Carbon::now()->diffInHours($pick_up_date):0;
    
            $checkVan = false;
    
            #168 is hours number in 7 days (24*7) , and check the vehicle if Van
            #if the vehicle is not Van, just check if more than 24h
            if(($checkVan && $checkHours > config('general.van_late_cancel_time')) || (!$checkVan && $checkHours > config('general.car_fleets_late_cancel_time')))
            {
                $data->status = 'canceled';
            }
    
            $data->save();
    
            return $this->NewResponse(true , "The ride has been canceled successfully." ,  null ,config('status_codes.success.ok'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorAndRedirect($e,'Error in finding tag: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in get reservations controller');
        }
    }

}