<?php
namespace App\Traits;

use App\Models\ChildSeat;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\FleetCategory;
use Carbon\Carbon;

trait PricingTrait{
    
    use UtilitiesTrait,CalculationTrait;

    public function getPrice($data)
    {
        /** Essential variables to the price equation  
         * 
         * Distance: Trip overall distance on this information will consider the extra mile's.
         * Hours: if the trip is hourly this will be the number of hours the user have chosen.
         * Fleet Category : The chosen fleet the user have selected on it will consider the pricing rules if the city dose't exist.
         * Service Type : The service type is 1 point to point and 2 hourly.
         * City Name : if the city was selected the pricing rules of the city wil be implemented  if it's dose exist in the database.
         * Is Round Trip : if the trip is round the price will be multiply by 2
         * Childe Seats : every childe seats it will added the price accordingly
         * Pick Up Time : The pickup time of the trip this will effect the price and if the pick-up is inside an event or an city
         * Pick Time Request: This for the event query
         * Pick Up Date: The pick up date of the trip this will effect the price if the pickup date was less than 24 hour away.
         * Return Time: If the trip was round will be the time of the other trip
         * 
        */

        $tripDestinies = $data->distance;
        $tripHours = $data->duration;
        $fleetCategory = FleetCategory::where('status', 1)->whereHas('fleets');
        $serviceType = $data->service_id;
        $cityName = $data->city_name;
        $isRoundTrip = $data->is_round_trip;
        $chileSeats = $data->child_seats;
        $pickUpTimeRequest = $data->pick_up_time;
        $pickUpDate = $data->pick_up_date;
        $returnTime = $data->return_time;
        $pickUpTime = Carbon::parse($data->pick_up_time)->format('H:i:s');
        $today = Carbon::now();

        #GET THE CITY BASED ON THE NAME AND IF DOSE EXIST THE PRICING OF THE CITY WILL IMPLEMENT
        $city = City::where('status', 'Active')->where('title', $cityName)->first();

        #TAKE THE CITY FLEETS IF IT DOSE EXIST
        if ($city) 
        {
          # GET THE CITY TIMEZONE IF IT DOSE EXIST IF IT'S CHANGE THE TIMEZONE OF THE WEBSITE
           if($city->timezone)
            {
                $today = $this->setTimezoneGmt($city->timezone);
            }
            
            $fleetCategory = $fleetCategory->whereHas('cities', function ($q) use ($city) {
             $q->where('cities.id', $city->id);
            });
        }

         #loop throw the fleets categories and get the pricing for every one of them
       $fleetCategory =  $fleetCategory->get()->map(function ($item) use (
            $returnTime,
            $serviceType,
            $city,
            $tripDestinies,
            $isRoundTrip,
            $tripHours,
            $chileSeats,
            $pickUpDate,
            $pickUpTimeRequest,
            $today,
            $data,
            $pickUpTime,
            ) {


            #to return the names of the fleet's are attached to the fleet category to the client
            $fleets = implode(', ',$item->fleets()->pluck('title')->toArray());
            

            #get the default pricing for the fleet category if the city dose't have price or dose't exist
            $pricingRulesObj = json_decode($item->pricing_rules);
            
            $pricingRules = (object) [
                'initial_fee' => $pricingRulesObj->initial_fee,
                'minimum_hour' => $pricingRulesObj->minimum_hour,
                'minimum_mile_hour' => $pricingRulesObj->minimum_mile_hour,
                'minimum_mile' => $pricingRulesObj->minimum_mile,
                'price_per_hour' => $pricingRulesObj->price_per_hour,
                'extra_price_per_mile' => $pricingRulesObj->extra_price_per_mile_hourly,
                'point_to_point_extra_price_per_mile' => $pricingRulesObj->extra_price_per_mile ?? 1.6
            ];
            
            $response = (object) [
                'id' => $item->id,
                'name' => $item->title,
                'description' => $item->category_description,
                'image' => $item->avatar,
                'luggage' => $item->luggage,
                'passengers' => $item->passengers,
                'types' => $fleets,
                'is_flight_tracking' => $item->flight_tracking == 1 ? true : false,
                'short_title' => $item->short_title,
                'price'=>0
            ];

            #add time just for testing
            $response->time = $today;


            #if the city exist take there pricing for the fleet category
            if ($city) {
                $getCityPrice = $city->getCityPrice($item->id, $serviceType);
                if ($getCityPrice) {
                    $pricingRules = (object) [
                        'initial_fee' => $getCityPrice->initial_fee ?? $pricingRules->initial_fee,
                        'minimum_hour' => $getCityPrice->minimum_hour ?? $pricingRules->minimum_hour,
                        'minimum_mile_hour' => $getCityPrice->minimum_mile_hour ?? $pricingRules->minimum_mile_hour,
                        'minimum_mile' => $getCityPrice->minimum_mile ?? $pricingRules->minimum_mile,
                        'price_per_hour' => $getCityPrice->price_per_hour ?? $pricingRules->price_per_hour,
                        'extra_price_per_mile' => $getCityPrice->extra_price_per_mile_hourly ?? $pricingRules->extra_price_per_mile_hourly,
                        'point_to_point_extra_price_per_mile' => $getCityPrice->extra_price_per_mile ?? $pricingRules->point_to_point_extra_price_per_mile
                    ];
                }
            }

            /** POINT TO POINT */
            if ($serviceType == 1) {
                #CALCULATE
                $price = $this->getPointToPointPrice(
                    $tripDestinies,
                    $pricingRules->minimum_mile,
                    $pricingRules->initial_fee,
                    $pricingRules->point_to_point_extra_price_per_mile,
                    0
                );
                /** Test */
                $response->trip_destines = $tripDestinies;
                $response->minimum_miles = $pricingRules->minimum_mile;
                $response->initial_fee = $pricingRules->initial_fee;
                $response->point_to_point_extra_price_per_mile = $pricingRules->point_to_point_extra_price_per_mile;

            } else {
                /** HOURLY  CALCULATE*/
                $price = $this->getHourlyPrice(
                    $pricingRules->price_per_hour,
                    $tripDestinies,
                    $tripHours,
                    $pricingRules->minimum_mile_hour,
                    $pricingRules->extra_price_per_mile,
                    $pricingRules->minimum_hour,
                    0
                );
          


                $response->price_per_hour = $pricingRules->price_per_hour;
                $response->tripDestines = $tripDestinies;
                $response->tripHours = (int)$tripHours;
                $response->minimum_hour = $pricingRules->minimum_hour;
                $response->minimum_mile_hour = $pricingRules->minimum_mile_hour;
                $response->extra_price_per_mile = $pricingRules->extra_price_per_mile;

            
            }


            #ADD CHILD SEATS
            if ($chileSeats) 
            {
                foreach ($chileSeats as $value) {
                    $childSeats = ChildSeat::where('status', 'Published')->find($value);
                    if ($childSeats) {
                        $seatPrice = $childSeats->price;
                        $price = $price + $seatPrice;
                    }
                }
            }

            /** If it's Round trip should Multiply by tow  */
            $price = $isRoundTrip == '1' ? $price * 2 : $price;
            
            #add the daily time if the return or the pick time is within range
            if ($city && $city->daily_from && $city->daily_to) 
            {
                $startTime = Carbon::createFromFormat('H:i:s', $city->daily_from);
                $endTime = Carbon::createFromFormat('H:i:s', $city->daily_to);
                $dailyPickUpTime = Carbon::createFromFormat('h:i A', $pickUpTimeRequest);
                
            
                if($returnTime) $returnTime = Carbon::createFromFormat('h:i A', $returnTime);

                #handle the case where the end time is earlier than the start time
                if ($endTime->lt($startTime)) {
                    #if check time is after end time, add one day to end time
                    $endTime->addDay();
                }
                
                $startTime = $city->daily_from;
                $endTime = $city->daily_to;
               
                #add the price if the pick-up time is within the range
                if ($this->isTimeBetweenRange($dailyPickUpTime->format('H:i:s'),$startTime,$endTime))
                {
                   
                    $price = $price + $city->daily_price;
                }
                #add the price if the return time is with the range and the server is point to point
                if ($returnTime&&$serviceType == 1) {
                    if ($this->isTimeBetweenRange($returnTime->format('H:i:s'),$startTime,$endTime))
                        $price = $price + $city->daily_price;
                }

            }

            #add the daily time for the fleet category if the city don't have one
            if((!$city || !$city->daily_from || !$city->daily_to)&&($item->daily_from && $item->daily_to))
            {
                $startTime = Carbon::createFromFormat('H:i:s', $item->daily_from);
                $endTime = Carbon::createFromFormat('H:i:s', $item->daily_to);
                $dailyPickUpTime = Carbon::createFromFormat('h:i A', $pickUpTimeRequest);
                
            
                if($returnTime) $returnTime = Carbon::createFromFormat('h:i A', $returnTime);

                #handle the case where the end time is earlier than the start time
                if ($endTime->lt($startTime)) {
                    #if check time is after end time, add one day to end time
                    $endTime->addDay();
                }
                
                $startTime = $item->daily_from;
                $endTime = $item->daily_to;
               
                #add the price if the pick-up time is within the range
                if ($this->isTimeBetweenRange($dailyPickUpTime->format('H:i:s'),$startTime,$endTime))
                {
                   
                    $price = $price + $item->daily_price;
                }
                #add the price if the return time is with the range and the server is point to point
                if ($returnTime&&$serviceType == 1) {
                    if ($this->isTimeBetweenRange($returnTime->format('H:i:s'),$startTime,$endTime))
                        $price = $price + $item->daily_price;
                }
            }

            #reservation location information
            $locationInfo = (object) [
                'pick_up_lat' => $data->lat,
                'pick_up_long' => $data->long,
                'drop_off_lat' => $data->drop_off_lat,
                'drop_off_long' => $data->drop_off_long,
            ];

           
            #add the event price
            $response->price = $this->getEventPricing($pickUpDate,$pickUpTime,$locationInfo,$serviceType,$price);

            /** if it have return time that mean it's round trip, so change the coordinates   */
            if($returnTime&&$isRoundTrip == '1')
            {
                $locationInfoReturnTrip = (object) [
                    'pick_up_lat' => $data->drop_off_lat,
                    'pick_up_long' => $data->drop_off_long,
                    'drop_off_long' => $data->long,
                    'drop_off_lat' => $data->lat,
                ];

                $returnTimeFormat = Carbon::parse($returnTime)->format('H:i:s');

                $response->price = $this->getEventPricing($pickUpDate,$returnTimeFormat,$locationInfoReturnTrip,$serviceType,$response->price);                
            }

            #get the pricing of reserving time
            $formate = $pickUpDate . ' ' . $pickUpTimeRequest;
            $dateTimeForTrip = Carbon::parse($formate);
            $minutesDifference = $today->diffInMinutes($dateTimeForTrip);
            $chargedMinutes = 1440 - $minutesDifference;
           
            $response->diffInMinutes = $minutesDifference > 1440 ? 'Not Counting Reserving Time ' : $chargedMinutes;
            $reservingPrice = $this->getReservingTimes($chargedMinutes,$city,$item,$response->price);
            $response->price = $response->price + $reservingPrice;




            #format the price
            $response->price = number_format((float) $response->price, 2, '.', '');

            return $response;

        });

        
        
        return $fleetCategory;

    }

    public function getPriceAccordingToFleet($data,$id)
    {
        
        /** Essential variables to the price equation  
         * 
         * City: To get the city pricing.
         * Fleet Category: To get it's fleets and pricing from the city.
         * Service Type : The service type is 1 point to point and 2 hourly.
         * Destines :  Trip overall distance on this information will consider the extra mile's.
         * Hours: if the trip is hourly this will be the number of hours the user have chosen
         * Childe Seats : every childe seats it will added the price accordingly
         * Extra price: This will be added from the child seats or if it's inside event
         * Coupon Code : The coupon code to add discount
         * Round Trip : Multiply by two
         * Return Time : Needed for the daily time
         * Default Price : if it was true it will ignore the city pricing and take the fleet.
         * Get Reserving Time : If it's true the dashboard will calculate the reserving time else not
         * 
        */


        $cityName = $data->cityName;
        $city = City::where([['title',$cityName],['status','Active']])->first();
        $fleetCategory = FleetCategory::find($id);
        $serviceType = $data->serviceType;
        $tripDestines = $data->distance;
        $tripHours = $data->hours;
        $pickUpDate = $data->pickUpDate;
        $pickUpTime = Carbon::parse($data->pick_up_time)->format('H:i:s');
        $pickUpTimeRequest = $data->pickUpTime;
        $isRoundTrip = $data->isRoundTrip;
        $childSeats = $data->childSeats;
        $extraPrice = 0;
        $couponCode = Coupon::find($data->couponCode);
        $defaultPrice = $data->fleetDefaultPricing;
        $getReservingTime = $data->getReservingTime;
        $today = Carbon::now();
        $returnTime = $data->returnTime;


     
        #GET THE CITY BASED ON THE NAME AND IF DOSE EXIST THE PRICING OF THE CITY WILL IMPLEMENT
        $city = City::where('status', 'Active')->where('title', $cityName)->first();
        #TAKE THE CITY FLEETS IF IT DOSE EXIST
        if ($city) 
        {
            
            # GET THE CITY TIMEZONE IF IT DOSE EXIST IF IT'S CHANGE THE TIMEZONE OF THE WEBSITE
            if($city->timezone)
            {
                
                $today = $this->setTimeZoneGmt($city->timezone);
            }
        }

        #to return the names of the fleet's are attached to the fleet category to the client
        $fleets = $fleetCategory->fleets()->select('id','title')->get();

        #get the default pricing for the fleet category if the city dose't have price or dose't exist
        $pricingRulesObj = json_decode($fleetCategory->pricing_rules);
        
        $pricingRules = (object) [
            'initial_fee' => $pricingRulesObj->initial_fee,
            'minimum_hour' => $pricingRulesObj->minimum_hour,
            'minimum_mile_hour' => $pricingRulesObj->minimum_mile_hour,
            'minimum_mile' => $pricingRulesObj->minimum_mile,
            'price_per_hour' => $pricingRulesObj->price_per_hour,
            'extra_price_per_mile' => $pricingRulesObj->extra_price_per_mile_hourly,
            'point_to_point_extra_price_per_mile' => $pricingRulesObj->extra_price_per_mile ?? 1.6
        ];

        $fleetInfo = (object) [
            'id' => $fleetCategory->id,
            'name' => $fleetCategory->title,
            'description' => $fleetCategory->category_description,
            'image' => $fleetCategory->avatar,
            'luggage' => $fleetCategory->luggage,
            'passengers' => $fleetCategory->passengers,
            'types' => $fleets,
            'is_flight_tracking' => $fleetCategory->flight_tracking == 1 ? true : false,
            'short_title' => $fleetCategory->short_title
        ];
          #if the city exist take there pricing
          if ($city&&(int)$defaultPrice == 0) 
          {

              $getCityPrice = $city->getCityPrice($fleetCategory->id, $serviceType);
            
            if ($getCityPrice) {
                $pricingRules = (object) [
                    'initial_fee' => $getCityPrice->initial_fee ?? $pricingRules->initial_fee,
                    'minimum_hour' => $getCityPrice->minimum_hour ?? $pricingRules->minimum_hour,
                    'minimum_mile_hour' => $getCityPrice->minimum_mile_hour ?? $pricingRules->minimum_mile_hour,
                    'minimum_mile' => $getCityPrice->minimum_mile ?? $pricingRules->minimum_mile,
                    'price_per_hour' => $getCityPrice->price_per_hour ?? $pricingRules->price_per_hour,
                    'extra_price_per_mile' => $getCityPrice->extra_price_per_mile_hourly ?? $pricingRules->extra_price_per_mile_hourly,
                    'point_to_point_extra_price_per_mile' => $getCityPrice->extra_price_per_mile ?? $pricingRules->point_to_point_extra_price_per_mile
                ];

            }
          }

           /** POINT TO POINT */
        if ($serviceType == 1) 
        {
            #CALCULATE
             $price = $this->getPointToPointPrice(
                $tripDestines,
                $pricingRules->minimum_mile,
                $pricingRules->initial_fee,
                $pricingRules->point_to_point_extra_price_per_mile,
                0
            );


        

                /** Test */
            $fleetInfo->trip_destines = $tripDestines;
            $fleetInfo->minimum_miles = $pricingRules->minimum_mile;
            $fleetInfo->initial_fee = $pricingRules->initial_fee;
            $fleetInfo->point_to_point_extra_price_per_mile = $pricingRules->point_to_point_extra_price_per_mile;

            #IF ROUND TRIP MULTIPLY BY TWO
            $fleetInfo->price = $isRoundTrip ? $price * 2 : $price;
        } 
        else 
        {
            /** HOURLY  CALCULATE*/
            $price = $this->getHourlyPrice(
                $pricingRules->price_per_hour,
                $tripDestines,
                $tripHours,
                $pricingRules->minimum_mile_hour,
                $pricingRules->extra_price_per_mile,
                $pricingRules->minimum_hour,
                0
            );

        }

        /** check if there is an event and the pickup location is inside */
        $locationInfo = (object) [
            'pick_up_lat' => $data->lat,
            'pick_up_long' => $data->long,
            'drop_off_lat' => $data->drop_off_lat,
            'drop_off_long' => $data->drop_off_long,
        ];

        /** If it's Round trip should Multiply by tow  */
        $price = $serviceType == 1 && $isRoundTrip ? $price * 2 : $price;

         #add the daily time if the return or the pick time is within range
         $timeInfoData = (object)[
            'pickUpTime'=>$pickUpTimeRequest,
            'returnTime'=>$returnTime
         ];
         
         
         #add the daily time price
         $price = $price + $this->getDailyTime($city,$timeInfoData,1,'24');
         
         #add the event price
         $price = $this->getEventPricing($pickUpDate,$pickUpTime,$locationInfo,$serviceType,$price);

        #add reserving time price

        #get the pricing of reserving time
        $formate = $pickUpDate . ' ' . $pickUpTimeRequest;
        $dateTimeForTrip = null;

        #check if the user is selected a pickup time and date
        if($pickUpDate != '----' && $pickUpTimeRequest)
        {
            $dateTimeForTrip = Carbon::parse($formate);
        }

        $minutesDifference = $today->diffInMinutes($dateTimeForTrip);

        if($getReservingTime == 1&& $dateTimeForTrip && $dateTimeForTrip->gte($today))
        {
            $reservingPrice = $this->getReservingTimes($minutesDifference,$city,$fleetCategory,$price);
            $price = $price + $reservingPrice;
        }

       


        /** add child seats pricing */
        if (is_array($childSeats)) {
            foreach ($childSeats as $value) {
                $extraPrice = $extraPrice + $value['price'] * $value['amount'];
            }
        }

        $price = $price + $extraPrice;

        /** implement the the coupon code if it dose exist */
        if ($couponCode) {
            if ($couponCode->discount_type == 'Percentage') {
                $discountAmount = ($price * $couponCode->percentage_discount) / 100;
            } else {
                $discountAmount = $couponCode->percentage_discount;
            }
            
            $price = $price - $discountAmount;
            
            //** decrees the amount of usage when the user uses the discount */
            $couponCode->update([
                'usage_limit'=>$couponCode->usage_limit > 0 ? $couponCode->usage_limit - 1 : 0
            ]);
        }


        return [
            'price'=>$price,
            'fleets'=>$fleets
        ];


    }


    public function get_city_timezone($city_name = null)
    {
        // Make the time utc to prevent any conflict in the timezone and avoid time zones issues
        date_default_timezone_set('UTC');

        // The default time for the api
        $defaultTime = config('general.default_time_zone');
        
        $data = City::where('title',$city_name)->first();

        // Get the city timezone
        if($data&&$data->timezone) $defaultTime = $data->timezone;

        $timeNow = Carbon::now();


        return $this->NewResponse(true, [
            'city_exist'=>$data ? true : false,
            'time'=>$timeNow->setTimezone($defaultTime)->toDateTimeString()
        ], null, config('status_codes.success.ok'));
    }

    private function getEventPricing($pickUpDate,$pickUpTime,$locationInfo,$reservation_service_type,$reservation_price)
    {
       
        $events = Event::where('status', 'Active')
        ->whereRaw('? BETWEEN start_date AND end_date', [$pickUpDate])
        ->where(function ($query) use ($pickUpTime) {
            $query->where(function ($innerQuery) use ($pickUpTime) {
                // Check if start_time is not null and within the time range
                $innerQuery->whereNotNull('start_time')
                    ->whereNotNull('end_time')
                    ->whereRaw('? BETWEEN TIME_FORMAT(start_time, "%H:%i:%s") AND TIME_FORMAT(end_time, "%H:%i:%s")', [$pickUpTime])
                    ->orWhereRaw('TIME_FORMAT(start_time, "%H:%i:%s") > TIME_FORMAT(end_time, "%H:%i:%s") AND (? BETWEEN TIME_FORMAT(start_time, "%H:%i:%s") AND "23:59:59" OR ? BETWEEN "00:00:00" AND TIME_FORMAT(end_time, "%H:%i:%s"))', [$pickUpTime, $pickUpTime]);
            })->orWhereNull('start_time'); // Include events where start_time is null
        })
        ->get();
        
        $addedPrice = $reservation_price;
        $isInside = false;
      
        foreach($events as $item)
        {
            $eventServiceType = $item->service_type;
            $eventApplyFor = $item->apply_for;
            $radius = $item->radius;


            $isInside = false;

                /**
                 * Service type Is 
                 * 0 for Both
                 * 1 for point to point 
                 * 2 for hourly
                 */

                #check the type of the event and if dose it match to the sent service to it's pricing
                if ($eventServiceType == $reservation_service_type || $eventServiceType == 0) 
                {

                    if ($eventApplyFor == 'Both') {
                        $isInside = $this->isLocationInsideRadius($locationInfo->pick_up_lat, $locationInfo->pick_up_long,$item->latitude,$item->longitude, $radius);
                        if (!$isInside)
                        $isInside = $this->isLocationInsideRadius($locationInfo->drop_off_lat, $locationInfo->drop_off_long,$item->latitude,$item->longitude, $radius);
                    }
                    elseif ($eventApplyFor == 'Pickup') {
                        $isInside = $this->isLocationInsideRadius($locationInfo->pick_up_lat, $locationInfo->pick_up_long,$item->latitude,$item->longitude, $radius);

                    }
                    elseif($eventApplyFor == 'DropOff') {
                        $isInside = $this->isLocationInsideRadius($locationInfo->drop_off_lat, $locationInfo->drop_off_long,$item->latitude,$item->longitude, $radius);

                    }
                }

                if($isInside)
                {
                    $getEventPrice = $item->discount_type == 'Price' ? $item->price : ($item->price / 100) * $reservation_price;
                    $addedPrice = (float) $reservation_price + $getEventPrice;
                }

        }
        return $addedPrice;
        



    }

    private function getDailyTime($city,$timeInfo,$serviceType,$type = '12')
    {
        /**
         * Doc: get daily time will check if there is any daily time and if it's 
         * it will check the return and pickup time if it's within the range it will added
         * 
         * arguments:
         *  City: This is the city need to be checked for daily time
         *  Time Info: Will have the time information like the pick-up/drop off location
         *  Service Type : 1 is Point to point and 2 is hourly
         *  Type: is the time format 12 Hour or 24 Hour
         */


        $price = 0;
        
        if ($city && $city->daily_from && $city->daily_to&&$timeInfo->pickUpTime) {
            $returnTime = null;
            $startTime = Carbon::createFromFormat('H:i:s', $city->daily_from);
            $endTime = Carbon::createFromFormat('H:i:s', $city->daily_to);
            
            if($type == '24')
            {
                $dailyPickUpTime = Carbon::createFromFormat('h:i A', date("g:i a", strtotime($timeInfo->pickUpTime)));
                if(property_exists($timeInfo, 'returnTime')&&$timeInfo->returnTime) $returnTime = Carbon::createFromFormat('h:i A',date("g:i a", strtotime($timeInfo->returnTime)));
            }
            else
            {
                $dailyPickUpTime = Carbon::createFromFormat('h:i A', $timeInfo->pickUpTime);
                if(property_exists($timeInfo, 'returnTime')&&$timeInfo->returnTime) $returnTime = Carbon::createFromFormat('h:i A', $timeInfo->returnTime);
            }

            #handle the case where the end time is earlier than the start time
            if ($endTime->lt($startTime)) 
            {
                #if check time is after end time, add one day to end time
                $endTime->addDay();
            }
            
            $startTime = $city->daily_from;
            $endTime = $city->daily_to;

            
            #add the price if the pick-up time is within the range
            if ($this->isTimeBetweenRange($dailyPickUpTime->format('H:i:s'),$startTime,$endTime))
            {
                $price = $price + $city->daily_price;
            }

            #add the price if the return time is with the range and the server is point to point
            if ($returnTime&&$serviceType == 1) {
                if ($this->isTimeBetweenRange($returnTime->format('H:i:s'),$startTime,$endTime))
                    $price = $price + $city->daily_price;
            }
        }

        return $price;
    }

}