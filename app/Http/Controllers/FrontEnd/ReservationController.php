<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEnd\CheckoutRequest;
use App\Http\Requests\FrontEnd\UpdateFleetRequest as FrontEndUpdateFleetRequest;
use App\Models\Airline;
use App\Models\ChildSeat;
use App\Models\ChildSeatReservation;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\ServiceLocationRestriction;
use App\Models\User;
use App\Services\ReservationStatusService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Traits\CalculationTrait;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\PricingTrait;
use App\Traits\UtilitiesTrait;
use App\Traits\EmailTrait;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentMethod;
use Illuminate\Support\Facades\Session;

class ReservationController extends Controller
{
    /*
  |--------------------------------------------------------------------------
  | Reservation Controller
  |--------------------------------------------------------------------------
  |
  | responsible for all the trash actions in the blog
  |
  */

    use CalculationTrait, PricingTrait, UtilitiesTrait, LogErrorAndRedirectTrait, JsonResponseTrait, EmailTrait;

    public function index()
    {
        /**
         * Index
         * 
         * Doc: send the user to the index reservation page
         *
         *@param Integer $id App\Models\Reservation
         *
         * @return \Illuminate\View\View
         */

        try {
            $user = Auth::user();

            #get tag coming reservations
            $comingTrips = $user->reservations()
                ->whereNotIn('status', config('general.forntend_status'))
                ->where('status','!=','failed')
                ->orderBy('created_at', 'desc')
                ->get();

            #get the previous reservation
            $previousTrips = $user->reservations()
                ->whereIn('status', config('general.forntend_status'))
                ->where('status','!=','failed')
                ->orderBy('created_at', 'desc')
                ->get();


            return view('trips.index', compact(['user', 'previousTrips', 'comingTrips']));
        } catch (Exception $e) {
            $this->logErrorAndRedirect($e, "error getting the reservation for the user");
            return back();
        }
    }

    public function show($id)
    {
        /**
         * Show
         * 
         * Doc: send the user to the details page reservation
         *
         *@param Integer $id App\Models\Reservation
         *
         * @return \Illuminate\View\View
         */

        try {
            #check if the user have the reservation
            $trip = Auth::user()->reservations()->findOrFail($id);

            return view('trips.details', compact('trip'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->logErrorAndRedirect($e, 'Error finding reservation: ');
            return back();
        } catch (Exception $e) {
            $this->logErrorAndRedirect($e, 'Error getting the details for the reservation:');
            return back();
        }
    }

    public function cancel($id)
    {
        /**
         * Cancel
         * 
         * Doc: Cancel reservation
         *
         *@param Integer $id App\Models\Reservation
         *
         * @return \Illuminate\View\View
         */

        try {
            #get reservations
            $reservation = Auth::user()
                ->reservations()
                ->findOrFail($id);

            #update the status
            $reservation->update([
                'status' => Reservation::CANCELED
            ]);

            #send email to the user 
            $tripStatusService = new ReservationStatusService();
            $tripStatusService->sendNotifications($reservation->id, "update", null);

            $responseObj = [
                'status' => 'success',
                'data' => $reservation
            ];

            return $this->successResponse($responseObj, config('status_codes.success.ok'));
        } catch (Exception $e) {
            return $this->logErrorJson($e, "Error canceling the reservation.");
        }

    }

    public function choose_location()
    {
        /**
         * Choose location
         * 
         * Doc: send the user to the page to choose the location from
         *
         *
         * @return \Illuminate\View\View
         */

        try {
            #get the airline 
            $airlines = Airline::get();
            $childSeats = ChildSeat::where('status', ChildSeat::STATUS_PUBLISHED)
                ->get()
                ->take(config('general.max_child_seats'));

            $hoursDurations = config('general.max_hours_durations');

            return view('frontEnd.reservations.index', compact('airlines', 'childSeats', 'hoursDurations'));
        } catch (Exception $e) {
            $this->logErrorAndRedirect($e, 'error in choosing location reservation');
            return back();
        }
    }

    public function select_fleet(Request $request, $city = null)
    {
        /**  
         * Select fleet
         * 
         * DOC: go to second page this page is responsible for choosing the fleet
         * 
         * 
         * @param Illuminate\Http\Request $request
         * @param String $city if the city was sent to check if it's available in the database if it is take it's pricing
         * 
         * @return \Illuminate\View\View
         */

        try 
        {
            #get reservation data from the session
            $reservationData = Session::get('reservation_data');

            #if session not found return to the first page of the reservation steps
            if(!$reservationData)
            {
                return redirect()->route('reservations.choose_location');
            }

            #get only the draft reservation in this page because it will be created in the previous page.
            $data = Reservation::where([['id', $reservationData['reservation_id']], ['status', Reservation::Draft], ['price', 0]])->firstOrFail();

            #get child seats so it will calculated with the price
            $childSeats = $data->childSeats()->get();

            #get the child seats with the amount of time the user have selected them
            $childSeatsFormatted = $this->format_child_seats($childSeats);

            #get flight details
            $flightDetails = $this->get_reservation_flight_details($data->flight_number, $data->airlines);

            #pickUpTime 
            $pickupTime = date("h:iA", strtotime($data->pick_up_time));


            $pricingAttributes = (object) [
                'distance' => $data->mile,
                'duration' => $data->duration,
                'lat' => $data->latitude,
                'long' => $data->longitude,
                'drop_off_lat' => $data->dropoff_latitude,
                'drop_off_long' => $data->dropoff_longitude,
                'service_id' => $data->service_type,
                'city_name' => $reservationData['cityName'],
                'is_round_trip' => $data->parent || $data->child && $data->duration < 0 ? '1' : '0',
                'child_seats' => $childSeatsFormatted->ids,
                'pick_up_time' => $pickupTime,
                'pick_up_date' => $data->pick_up_date->format('Y-m-d'),
                'return_time' => $data->parent || $data->child ? date("h:i A", strtotime($data->parent->pick_up_time)) : null
            ];

            $fleetCategory = $this->getPrice($pricingAttributes);

            return view('frontEnd.reservations.fleet', compact('fleetCategory', 'data', 'childSeatsFormatted', 'flightDetails'));
        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e, 'error in selecting fleet');
            return back();
        }
    }

    public function checkout(Request $request)
    {
        /**  
         * Checkout
         * 
         * DOC: go to the checkout page with the reservation information
         * 
         * 
         * @return \Illuminate\View\View
         */

        try {

            #get the reservation data
            $reservationData = Session::get('reservation_data');

            #check the reservation to have status draft and price more than zero
            $data = Reservation::where([['id', $reservationData['reservation_id'] ?? $request->trip_id], ['status', Reservation::Draft], ['price', '>', 0]])
                ->firstOrFail();

            #get all countries from a json file
            $jsonData = file_get_contents('countries.json');
            $countries = json_decode($jsonData, true);

            #get child seats
            $childSeats = $data->childSeats()->get();

            #get the child seats with the amount of time the user have selected them
            $childSeatsFormatted = $this->format_child_seats($childSeats);

            #get flight details
            $flightDetails = $this->get_reservation_flight_details($data->flight_number, $data->airlines);


            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $customer_id = Auth::user()->stripe_id;

            $uniqueCards = [];

            $paymentMethods = PaymentMethod::all([
                'customer' => $customer_id,
                'type' => 'card',
            ]);

            foreach ($paymentMethods as $paymentMethod) {
                #Use card fingerprint (preferred) or id as the unique identifier
                $uniqueIdentifier = $paymentMethod->card->fingerprint; #or $paymentMethod->id

                #Check if the card is already in the uniqueCards array (by unique identifier)
                $existingCard = array_filter($uniqueCards, function ($card) use ($uniqueIdentifier) {
                    return $card->unique_identifier === $uniqueIdentifier;
                });

                #If the card is not a duplicate, add it to the uniqueCards array as an object
                if (empty($existingCard)) {
                    $uniqueCard = (object) [
                        'id' => $paymentMethod->id,
                        'brand' => $paymentMethod->card->brand,
                        'last4' => $paymentMethod->card->last4,
                        'unique_identifier' => $uniqueIdentifier,
                    ];

                    $uniqueCards[] = $uniqueCard;
                }

            }
          
            return view('frontEnd.reservations.checkout', compact('countries', 'uniqueCards', 'data', 'childSeatsFormatted', 'flightDetails'));


        } 
        catch (Exception $e) 
        {
           
            return $this->logErrorAndRedirect($e, 'check out front-end');
        }
    }

    public function thank_you()
    {
        /**  
         * Thank You
         * 
         * DOC: go to the thank you page after making a reservation
         * 
         * 
         * @return \Illuminate\View\View
         */

        try 
        {
            return view('frontEnd.reservations.thank_you');

        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e, 'going to the thank you page');
            return back();
        }

    }

    public function checkout_submit(CheckoutRequest $request, $id)
    {
        /**  
         * Check out submit
         * 
         * DOC: update the reservation for the check out
         * 
         * @param Illuminate\Http\Request $request
         * @param Integer $id App\Models\Reservation
         * 
         * 
         * @return Json
         */

        try 
        {
            #int stripe
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            #find the drafted reservation was previously created for the user 
            $data = Reservation::where([['id', $id], ['price', '>', 0]])->firstOrFail();

            #check if the reservation is round trip
            $isRoundTrip = $data->parent ? true : false;

            #implement coupon 
            $data = $this->implement_coupon($request->coupon_code, $data);;

            #get customer info
            $customer = Auth::user();

            #if user dose't have a strip id generate one and update it
            if (!$customer->stripe_id) {

                $customerId = $this->generateCustomerId($customer->email, $customer->first_name . ' ' . $customer->last_name);

                $customer = User::findOrFail($customer->id);
                
                $customer->update([
                    'stripe_id' => $customerId
                ]);
            }

            #check if a card is selected
            $selectedCardId = $request->card_id;

            if (!$selectedCardId) {
                $card = $this->create_payment_method_stripe($request, $customer->stripe_id);
            }

            #add tip to price
            $tip = $this->calculatePriceTip($data->price, $request->tip);
            
            #over all price and check if it's round trip if the reservation is parent that mean this is a round trip
            $overAllPrice = $isRoundTrip ? ($data->price + $tip) / 2 : $data->price + $tip;
            $priceWithNoTip = $isRoundTrip ? $data->price / 2 : $data->price;

            #create payment intent
            $paymentIntent = $this->create_payment_intent($overAllPrice, $customer->stripe_id, $card->id ?? $selectedCardId, $isRoundTrip);
            
            #reservation object with the reservation pending so the admin can review it after submit it
            $reservation_obj = [
                'payment_intent_id' => $paymentIntent->id,
                'price_with_tip' => $overAllPrice,
                'price' => $priceWithNoTip,
                'user_id' => Auth::user()->id,
                'comment' => $request->comment,
                'status' => Reservation::PENDING,
            ];

            #if round trip update the child reservation info
            if ($isRoundTrip) 
            {
                #get all the reservation childe's
                $getChild = $data->parent;

                #update child
                $getChild->update($reservation_obj);

                $tripStatusService = new ReservationStatusService();
                $tripStatusService->sendNotifications($getChild->id, "new reservation", $getChild->users->email);

            }

            #update reservation information
            $data->update($reservation_obj);

            $tripStatusService = new ReservationStatusService();

            $tripStatusService->sendNotifications($data->id, "new reservation", $customer->email);

            #response object
            $responseObj = [
                'msg' => 'reservation was created',
                'data' => [
                    'url' => route('reservations.thank_you')
                ],
                'status' => config('status_codes.success.created')
            ];

            return $this->successResponse($responseObj, config('status_codes.success.ok'));
        } 
        catch (\Stripe\Exception\CardException $e)
        {

            $mailData = [
                'title'=>'Payment Alert!',
                'message'=>"A potential customer's payment is failing to go through your system. You can check it out by clicking on the button below",
                'subject'=>'Failed Payment Attempt',
            ];

            $this->sendFailedReservation($mailData);
            #Update the reservation status to failed
            $data->update([
                'status'=>Reservation::FAILED,
                'reject_note'=>$e->getMessage()
            ]);

            return $this->logErrorJson($e, 'error in getting the cards: ',$e->getMessage());
        } 
        catch (Exception $e) 
        {
            return $this->logErrorJson($e, 'Error in checkout reservation front end',$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        /**  
         * Store
         * 
         * DOC: Save the reservation info in the first page
         * 
         * @param Illuminate\Http\Request $request
         * 
         * 
         * @return Json
         */

        try {
            #main variables
            $selectedChildSeats = json_decode($request->seats);
            $reservation = null;
            $roundReservation = null;

            #check for child seats
            $seatValues = $selectedChildSeats ? array_column($selectedChildSeats, "seat") : [];
            $childSeatsDataBase = ChildSeat::whereIn('id', $seatValues)->pluck('title')->toArray();
            $changeChildSeatsToString = is_array($selectedChildSeats) ? implode(",", $childSeatsDataBase) : null;

            #get location's information from google
            $getPickupLocation = json_decode($this->places_details($request->pickup_location_google_id));
            $getDropOffLocation = json_decode($this->places_details($request->dropOff_location_google_id));


            #get the locations coordinates
            $pickupLocationCoordinates = $getPickupLocation->result->geometry->location;
            $getDropOffLocationCoordinates = $getDropOffLocation->result->geometry->location;

            #get trip mils throw sending the coordinates
            $tripInfo = (object) [
                'origin_lat' => $pickupLocationCoordinates->lat,
                'origin_lng' => $pickupLocationCoordinates->lng,
                'destination_lat' => $getDropOffLocationCoordinates->lat,
                'destination_lng' => $getDropOffLocationCoordinates->lng
            ];
            $tripMiles = $this->getDistance($tripInfo);

            #get the city name           
            $cityName = $this->getCityNameFromGoogleApi($getPickupLocation);

            $isPointToPoint = $request->service_type == 1 ? true : false;

            #create reservation with status draft without the price( the price will be selected in the select fleet page )
            $oneWayTransferTypeData = array_merge($request->all(), [
                'latitude' => $pickupLocationCoordinates->lat,
                'longitude' => $pickupLocationCoordinates->lng,
                'dropoff_latitude' => $getDropOffLocationCoordinates->lat,
                'dropoff_longitude' => $getDropOffLocationCoordinates->lng,
                'drop_off_location' => $request->drop_off_location ?? $request->pick_up_location,
                'pick_up_date' => $request->start_date,
                'pick_up_time' => $request->start_time,
                'start_date' => $request->start_date,
                'airline_id' => $request->airline_name,
                'flight_number' => $request->flight_number,
                'child_seats' => $changeChildSeatsToString,
                'pick_up_location' => $request->pick_up_location,
                'mile' => $tripMiles,
                'distance' => $tripMiles,
                'duration' => !$isPointToPoint ? $request->duration : 0,
                'transfer_way' => $isPointToPoint ? $request->transfer_way : 'One Way',
                'category_id' => 0,
                'user_id' => 0,
                'phone_primary' => 0,
                'price' => 0,
                'price_with_tip' => 0,
                'status' => Reservation::Draft
            ]);

            $reservation = Reservation::create($oneWayTransferTypeData);



            #if round trip create another trip with the same info but flip the coordinates and the locations and make the return time pickup time
            if ($request->transfer_way == 'Round' && $request->service_type == 1) {
                $roundData = array_merge($oneWayTransferTypeData, [
                    'latitude' => $getDropOffLocationCoordinates->lat,
                    'longitude' => $getDropOffLocationCoordinates->lng,
                    'dropoff_latitude' => $pickupLocationCoordinates->lat,
                    'dropoff_longitude' => $pickupLocationCoordinates->lng,
                    'drop_off_location' => $request->pick_up_location,
                    'pick_up_location' => $request->drop_off_location ?? $request->pick_up_location,
                    'pick_up_time' => $request->return_time,
                    'pick_up_date' => $request->return_date,
                    'parent_reservation_id' => $reservation->id
                ]);

                $roundReservation = Reservation::create($roundData);

            }

            #attach chair types and amounts to the pivot table reservation
            $this->addChildSeats($selectedChildSeats, $reservation);

            #attach seats types and amounts to the pivot table reservation round trip
            if ($roundReservation) {
                $this->addChildSeats($selectedChildSeats, $roundReservation);
            }

            #get the redirected url with the city name if founded
            $redirectedUrl = route('reservations.select_fleet');

            #put the data inside the session to send it to the other pages
            Session::put('reservation_data', [
                'reservation_id' => $reservation->id,
                'cityName' => $cityName,
            ]);

            #response object
            $responseObj = [
                'data' => [
                    'url' => $redirectedUrl
                ],
                'msg' => 'Data was returned'
            ];

            return $this->successResponse($responseObj, config('status_codes.success.ok'));
        } catch (Exception $e) {

            return $this->logErrorJson($e, 'Error adding reservation');
        }

    }

    public function update_fleet(FrontEndUpdateFleetRequest $request, $id)
    {
        /**  
         * Update Fleet
         * 
         * DOC: When the user select fleet category
         * 
         * @param Integer $id App\Models\Reservation
         * @param Illuminate\Http\Request $request
         * 
         * 
         * @return Json
         */

        try 
        {
            $user = Auth::user();

            #Check if the user is authenticated
            if ($user) {
                #User is authenticated, obtain the user ID
                $userId = $user->id;

                #updated info
                $data = [
                    'price' => $request->price,
                    'category_id' => $request->fleet_category_id,
                    'user_id' => $userId,
                ];

                #get the reservation and updated
                $reservation = Reservation::where([['id', $id], ['status', Reservation::Draft]])
                    ->firstOrFail();

                $reservation->update($data);

                #if it's round trip update the parent to
                if ($reservation->parent) {
                    $updateReservation = Reservation::find($reservation->parent->id);
                    $updateReservation->update($data);
                }

                #response object
                $responseObj = [
                    'msg' => 'Data was updated',
                    'status' => config('status_codes.success.updated')
                ];

                return $this->successResponse($responseObj, config('status_codes.success.ok'));

            } else {
                return $this->errorResponse('User not authenticated', config('status_codes.client_error.unauthorized'));

            }

        } 
        catch (Exception $e) 
        {
            return $this->logErrorJson($e, 'error updating the fleet reservation');
        }
    }

    private function places_details($place_id)
    {
        /**  
         * Place Details
         * 
         * DOC: get the place details from google api
         * 
         * @param String $place_id
         * 
         * 
         * @return Object
         */

        try 
        {
            header('Content-Type: application/json');

            #int vars
            $apiKey = env('GOOGLE_API_KEY');
            $placeId = $place_id;
            $fields = 'geometry,address_component';

            #Construct the URL for the Place Details request
            $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=$placeId&fields=$fields&key=$apiKey";

            #Initialize cURL session
            $ch = curl_init($url);

            #Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            #Execute the cURL request
            $response = curl_exec($ch);

            #Check for cURL errors
            if (curl_errno($ch)) {
                echo json_encode(array('error' => 'An error occurred'));
            } else {
                #return the response
                return $response;
            }

            #close cURL session
            curl_close($ch);

        } 
        catch (Exception $e) 
        {
            $this->logErrorAndRedirect($e, 'Error getting place details');
            return back();
        }
    }

    public function check_info(Request $request)
    {
        /**  
         * Check info
         * 
         * DOC: To check if the reservation is doable according to our rules
         * 
         * @param Illuminate\Http\Request $request
         * 
         * 
         * @return Json
         */

        try {
            #init vars
            $pickUpLocation = $request->query('pickUpLocation');
            $dropOffLocation = $request->query('dropOffLocation');
            $pickupTime = $request->query('pickupTime');
            $pickupDate = $request->query('pickupDate');
            $getPickupLocation = json_decode($this->places_details($pickUpLocation));
            $getDropOffLocation = json_decode($this->places_details($dropOffLocation));


            #check the restrictions for the pickup and drop off
            $pickup_restrictions = $this->checkLocationRetractions($getPickupLocation);
            $drop_off_restrictions = $this->checkLocationRetractions($getDropOffLocation);

            #get the city name from google api if the information found check the time was sent with the city time
            $cityName = $this->getCityNameFromGoogleApi($getPickupLocation);

            $currentTime = Carbon::now();

            if ($cityName) 
            {
                $city = City::where([['status', City::STATUS_ACTIVE], ['title', $cityName]])->first();

                #if the city was found in the database change the timezone to the city time zone
                $currentTime = $this->setTimezoneGmt($city && $city->timezone ? $city->timezone : config('general.default_time_zone'));

            }


            #check if the time was sent is more than tow hours in the futures
            $requestedTime = $pickupDate . ' ' . $pickupTime;

            #formate the time
            $givenTime = Carbon::createFromFormat('Y-m-d H:i', $requestedTime)->format('Y-m-d H:i:s');

            #add tow hours to the current time to determine the minimum time for making reservation 
            $miniumAvailableTime = $currentTime->copy()->addHours(2);

            #check if the time is available or not 
            $checkPickupTimeAvailability = $givenTime > $miniumAvailableTime;

            #response object
            $responseObj = [
                'data' => [
                    'service_restriction_pick_up' => $pickup_restrictions,
                    'service_restriction_dropoff' => $drop_off_restrictions,
                    'check_pickup_time_availability' => $checkPickupTimeAvailability,
                    'miniumAvailableTime' => $miniumAvailableTime,
                    'requestedTime' => $requestedTime,
                    'givenTime' => $givenTime
                ],
            ];

            return $this->successResponse($responseObj, config('status_codes.success.ok'));

        } catch (Exception $e) 
        {
            return $this->logErrorJson($e, 'Error Getting check info front end');
        }
    }

    public function login(Request $request)
    {
        /**  
         * Login
         * 
         * DOC: Make the user login in the front end
         * 
         * @param Illuminate\Http\Request $request
         * 
         * 
         * @return Json
         */

        try 
        {
            #init vars
            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];
            $msg = 'wrong credentials';
            $isLogged = false;
            $statusCode = config('status_codes.client_error.unauthorized');

            if (Auth::attempt(array_merge($credentials, ['password' => trim($request->password)]))) {
                $msg = 'correct credentials';
                $isLogged = true;
                $statusCode = config('status_codes.success.ok');
            }

            #response object
            $responseObj = [
                'msg' => $msg,
                'data' => [
                    'isLogged' => $isLogged
                ]
            ];

            return $this->successResponse($responseObj, $statusCode);

        } 
        catch (Exception $e) 
        {
            return $this->logErrorJson($e, 'Error login reservation system');
        }
    }

    private function getDistance($data)
    {
        /**  
         * Get Distance
         * 
         * DOC: get how many miles the trip is
         * 
         * @param $data
         *  - Lat
         *  - Long
         * 
         * 
         * @return Json
         */

        try 
        {
            #Get the api key
            $apiKey = env('GOOGLE_API_KEY');

            #Define the origin and destination coordinates
            $origin = "$data->origin_lat,$data->origin_lng";
            $destination = "$data->destination_lat,$data->destination_lng";

            #Construct the URL for the Directions API request
            $url = "https://maps.googleapis.com/maps/api/directions/json?origin=$origin&destination=$destination&key=$apiKey";

            #Initialize cURL session
            $ch = curl_init($url);

            #Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            #Execute the cURL request
            $response = curl_exec($ch);

            #Check for cURL errors
            if (!curl_errno($ch)) {
                #Decode the JSON response
                $data = json_decode($response, true);

                #Check if the request was successful
                if ($data['status'] === 'OK') {
                    #Access the distance and duration information
                    $meters = $data['routes'][0]['legs'][0]['distance']['value'];
                    $convertToMiles = $meters / 1609.34;
                    $format = round($convertToMiles, 3);
                    return $format;

                } else {
                    return "Directions request failed: " . $data['status'];
                }
            } else {
                return "An error occurred.";
            }

            #Close cURL session
            curl_close($ch);

        } 
        catch (Exception $e) 
        {
            return $this->logErrorAndRedirect($e, 'Error in getting the destination');
        }
    }

    private function addChildSeats($childSeats, $reservation)
    {
        /**  
         * Add Child Seats
         * 
         * DOC: add child seats to the reservation
         * 
         * @param Illuminate\Http\Request $request
         * @param App\Models\Reservation $reservation
         * 
         * 
         * @return Json
         */

        #check if the child seats is not null and its array
        if ($childSeats && is_array($childSeats)) {
            foreach ($childSeats as $index => $item) {

                $item = (object) $item;

                #check if the child seat is attached to the reservation if it is plus the amount by one else attach it
                $check = ChildSeatReservation::where([['reservation_id', $reservation->id], ['child_seats_id', $item->child_seats_id]])->first();

                if ($check) 
                {
                    $check->update([
                        'amount' => $item->amount
                    ]);
                } 
                else 
                {
                    $data = [
                        'reservation_id' => $reservation->id,
                        'amount' => $item->amount,
                        'child_seats_id' => $item->child_seats_id
                    ];

                    ChildSeatReservation::create($data);
                }
            }
        }
    }

    private function getCityNameFromGoogleApi($selectedAddress)
    {
        /**  
         * Get City Name From Google Api
         * 
         * DOC: get the city name from google api response
         * 
         * @param Object $selectedAddress
         * 
         * 
         * @return String
         */

        try {
            $cityName = null;

            #get the address information 
            $addressInformation = $selectedAddress->result->address_components;

            #loop throw all the address components to get the locality if exist
            foreach ($addressInformation as $key => $value) {
                $locationTypes = $selectedAddress->result->address_components[$key]->types;

                #check if "locality" or 'administrative_area_level_3' exist in types this means the city name exists in the response
                if (in_array('locality', $locationTypes) || in_array('administrative_area_level_3', $locationTypes)) {
                    #get the second index of the response it will be the city information if the response have locality type in it
                    $cityName = $selectedAddress->result->address_components[$key]->long_name;

                    break;
                }
            }

            return $cityName;
        } catch (Exception $e) {
            return $this->logErrorJson($e, 'error getting name from google api');
        }
    }

    private function checkLocationRetractions($locationCoordinates)
    {
        /**  
         * Check Location Retractions
         * 
         * DOC: check if the selected location is available to book
         * 
         * @param Object $locationCoordinates from google response
         * 
         * 
         * @return Boolean
         */

        try {
            #check if the selected location is allow inside this area
            $restrictedLocations = ServiceLocationRestriction::where('status', ServiceLocationRestriction::ACTIVE_STATUS)->get();

            #get the locations coordinates
            $pickupLocationCoordinates = $locationCoordinates->result->geometry->location;

            #check for any restrictions
            $restrictions = null;

            foreach ($restrictedLocations as $key => $value) {

                $isInsidePickUp = $this->isLocationInsideRadius($pickupLocationCoordinates->lat, $pickupLocationCoordinates->lng, $value->latitude, $value->longitude, $value->radius);

                #check availability for pickup
                if ($isInsidePickUp) {
                    $pickUpAvailability = $value->service;
                    $servicePickUp = $value->service_limitation;
                    $restrictions = $this->serviceLocationRestrictionsConditions($pickUpAvailability, $servicePickUp);
                }

            }

            return $restrictions;
        } 
        catch (Exception $e) 
        {
            return $this->logErrorJson($e, 'Error check the location restriction');
        }

    }

    public function get_coupon_code($code)
    {
        /**  
         * Check Location Retractions
         * 
         * DOC: check if the selected location is available to book
         * 
         * @param String $code for App\Models\Coupon
         * 
         * 
         * @return Json
         */

        try {
            #check today date
            $today = Carbon::today();

            #check if the coupon dose exist it no return error to the user
            $data = Coupon::where('coupon_code', $code)->first();
            if (!$data) {
                return response([
                    'err' => 'Coupon code not found',
                    'status' => config('status_codes.client_error.unprocessable')
                ], config('status_codes.client_error.unprocessable'));
            }

            #if the not exceeded the usage limit and it's end date more than today and the start date is past
            $checkCouponCondition = $data && $data->usage_limit != 0 && ($data->active_from->isPast($today) || $data->active_to->isSameDay($today)) && !$data->active_to->lt($today);

            if ($checkCouponCondition) {
                return response([
                    'data' => [
                        'discount' => $data->percentage_discount,
                        'discount_type' => $data->discount_type
                    ],
                    'status' => config('status_codes.success.ok')
                ], config('status_codes.success.ok'));

            } 
            else
             {
                if($data->usage_limit <= 0)
                {
                    return response([
                        'err' => 'This coupon has exceeded the limit.',
                        'status' => config('status_codes.client_error.unprocessable')
                    ], config('status_codes.client_error.unprocessable'));
                }
                else
                {
                    return response([
                        'err' => 'This coupon has expired.',
                        'status' => config('status_codes.client_error.unprocessable')
                    ], config('status_codes.client_error.unprocessable'));
                }
            }

        } 
        catch (Exception $e) 
        {
            return $this->logErrorJson($e, 'Error get the coupon front end');
        }
    }

    private function create_payment_method_stripe($request, $customer_id)
    {
        /**  
         * Create payment method from stripe
         * 
         * DOC: create a payment method from strip when the user check out
         * 
         * @param Illuminate\Http\Request $request
         * @param String $customer_id
         * 
         * 
         * @return Json
         */

        try {
            #format the card expiration date 
            list($month, $year) = explode('/', $request->exp_year);

            #format the year an month to understandable way for stripe 
            $newFormat = sprintf('%d / %d', ltrim($month, '0'), 2000 + $year);
            
            #get the year and the month as vars
            list($monthFormatted, $yearFormatted) = explode('/', $newFormat);

            #fill the card info
            $cardDetails = [
                'number' => $request->card_number ? $request->card_number : '',
                'exp_month' => (int) $monthFormatted,
                'exp_year' => (int) $yearFormatted,
                'cvc' => $request->cvc,
            ];

            $billingDetails = [
                'name' => $request->name,
                'address' => [
                    'line1' => $request->street,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal_code' => $request->postal_code,
                    'country' => "US",
                ],
                'email' => $request->email,
                'phone' => $request->phone,
            ];
            $card = PaymentMethod::create([
                'type' => 'card',
                'card' => $cardDetails,
                'billing_details' => $billingDetails
            ]);

            $card->attach(['customer' => $customer_id]);
            return $card;

        } 
        catch (\Stripe\Exception\CardException $e) {
            // Handle specific card errors
            throw $e;
        }
        catch (Exception $e) {

            return response()->json([
                'err' => 'Please provide correct credit card information',
                'status' => config('status_codes.client_error.unprocessable')
            ], config('status_codes.client_error.unprocessable'));
        }
    }

    private function implement_coupon($coupon_code, $reservation)
    {
        /**  
         * Implement coupon
         * 
         * DOC: implement the coupon to the reservation with updating the coupon usage
         * 
         * @param String  $coupon_code
         * @param App\Models\Reservation $reservation
         * 
         * 
         * @return App\Models\Reservation;
         */

        try 
        {
            #get the coupon
            $coupon = Coupon::where('coupon_code', $coupon_code)->first();
            $todayDate = Carbon::now();
            $price = $reservation->price;

            #if the not exceeded the usage limit and it's end date more than today and the start date is past
            $checkCouponCondition = $coupon && $coupon->usage_limit != 0 && ($coupon->active_from->isPast($todayDate) || $coupon->active_to->isSameDay($todayDate) && !$coupon->active_to->lt($todayDate));
           
            if ($checkCouponCondition) {
                #if coupon type is percentage decrease the amount by it else just minus the price.
                if ($coupon->discount_type == 'Percentage') 
                {
                    $price = $reservation->price * (1 - ($coupon->percentage_discount / 100));
                } 
                else 
                {
                    $discountedPrice = $reservation->price - $coupon->percentage_discount;
                    $price = $discountedPrice < 0 ? 0 : $discountedPrice;
                }

                $reservation->price = $price;
                $reservation->save();

                $coupon->update([
                    'usage_limit' => $coupon->usage_limit != 0 ? $coupon->usage_limit - 1 : 0
                ]);
            };


            return $reservation;

        } catch (Exception $e) {
            throw $e;
            //return $this->logErrorJson($e, 'Error create payment method');
        }
    }

    private function create_customer($request)
    {
        /**  
         * Create customer
         * 
         * DOC: create a customer
         * 
         * @param Illuminate\Http\Request $request
         * 
         * 
         * @return App\Models\User;
         */

        try {
            #generate password
            $password = $this->userGeneratePassword(5);
            #check if the customer is an old customer
            $customer = Customer::where('email', $request->email)->first();

            #if the customer is an old customer take it's info else take the sent by the request
            $customer_id = $customer && $customer->stripe_customer_id ? $customer->stripe_customer_id : $this->generateCustomerId($request->email, $request->first_name . ' ' . $request->last_name);
            $user_data = [
                'first_name' => $customer ? $customer->first_name : $request->first_name,
                'last_name' => $customer ? $customer->last_name : $request->last_name,
                'email' => $customer ? $customer->email : $request->email,
                'password' => bcrypt($password),
                'country_code' => $request->country_code,
                'phone' => $request->phone,
                'stripe_id' => $customer_id
            ];
            $customer = User::create($user_data);

            return $customer;
        } catch (Exception $e) {
            return $this->logErrorJson($e, 'Error create customer');
        }


    }

    private function calculatePriceTip($originalPrice, $tipPercentage)
    {
        /**  
         * Calculate the price tip
         * 
         * DOC: calculate the tip
         * 
         * @param Integer $originalPrice
         * @param Integer $tipPercentage
         * 
         * 
         * @return Integer;
         */

        try 
        {
            #Check if the input is numeric and the tip percentage is valid
            if (is_numeric($originalPrice) && is_numeric($tipPercentage) && $tipPercentage >= 0) {
                #calculate the tip amount
                $tipAmount = $originalPrice * ($tipPercentage / 100);
                return $tipAmount;

            } else {
                return 0;
            }
        } catch (Exception $e) {
            return $this->logErrorJson($e, 'Error create customer');
        }
    }

    private function create_payment_intent($price, $customer_id, $card_id, $is_round_trip = false)
    {
        /**  
         * Create a payment intent
         * 
         * DOC: create a payment intent in stripe
         * 
         * @param Integer $price price need to be sent to stripe
         * @param String $customer_id customer was created in stripe 
         * @param String $card_id the card id the payment need to be attached to
         * @param Boolean $is_round_trip is round trip to decide if need to be multiply by tow or not
         * 
         * 
         * @return \Stripe\PaymentIntent;
         */

        try 
        {
          
            $price = intval($price * 100);
            return \Stripe\PaymentIntent::create([
                'amount' => $is_round_trip ? $price * 2 : $price,
                'currency' => 'usd',
                'customer' => $customer_id,
                'payment_method_types' => ['card'],
                'payment_method' => $card_id,
                'capture_method' => 'manual',
                'use_stripe_sdk' => true,
                // 'metadata' => [
                //     'distance' => 1000, // Add the distance to the metadata
                // ],
                // 'billing_details' => [ //TODO: complete to get the billing info
                //     'address' => [
                //         'city' => 'sadasd',
                //         'country' => 'US',
                //         'line1' => 'asdasd',
                //         'line2' => null,
                //         'postal_code' => 'asd',
                //         'state' => 'dfdsfasd',
                //     ],
                //     'email' => 'new_email@example.com', // Update with the desired email
                //     'name' => 'asd',
                //     'phone' => '(888) 816-1015',
                // ],
            ])->confirm();
        } 
        catch (Exception $e) 
        {
            throw $e;
        }


    }

    private function format_child_seats($childSeats)
    {
        /**  
         * Format child seats
         * 
         * DOC: create a payment intent in stripe
         * 
         * @param Array $childSeats array of the child seats
         * 
         * 
         * @return Object;
         */

        try {
            $childSeatsName = '';
            $array_of_ids_child_seats = [];
            #get number of child seats to decide if we want to add a , or not.
            $numberOfChildSeats = count($childSeats);

            #get child seats names with the amount of have chosen
            foreach ($childSeats as $index => $seat) {

                #logic to determine adding "," or not (Plus one to the index because it's start from 0 not one so it will be the same as the count method)
                $symbol = $index + 1 < $numberOfChildSeats ? ',' : '';
                #add the seat name with the amount with 
                $seatName = $seat->title . ' ' . $seat->pivot->amount . $symbol;
                $childSeatsName = $childSeatsName . $seatName;
                #add the items the sames "amount" of times
                for ($i = 0; $i < $seat->pivot->amount; $i++) {
                    array_push($array_of_ids_child_seats, $seat->id);
                }

            }

            return (object) [
                'names' => $childSeatsName,
                'ids' => $array_of_ids_child_seats
            ];
        } catch (Exception $e) {
            return $this->logErrorJson($e, 'Error in formatting the child seats ');
        }
    }

    public function deleteTrip(Request $request, $id)
    {
        /**
         * deleteTrip reservation 
         * 
         * @param $id $flight_number
         * @param Illuminate\Http\Request 
         * 
         * 
         * 
         * @return \Illuminate\Http\Response
         */

        $trip = Reservation::findOrFail($id);

        if ($trip) {
            $trip->delete();
            return response()->json(['status' => 'success']);
        }
        else{
            return response()->json(['message'=>'No Trip Found'],404);
        }

    }

    private function get_reservation_flight_details($flight_number, $airline)
    {
        /**
         * Get reservation Flight Details
         * 
         * @param String $flight_number
         * @param String $airline
         * 
         * 
         * 
         * @return String
         */

        try {
            $word = '';
            if ($airline) {
                $splitWord = explode(' - ', $airline->name);
                $word = $splitWord[0] . ' ' . $flight_number;
            }

            return $word;
        } catch (Exception $e) {
            $this->logErrorJson($e, 'Error getting the reservation flight details');
        }

    }


}