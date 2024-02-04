<?php
namespace App\Traits;

use Carbon\Carbon;
use Stripe\StripeClient;

use Exception;

trait UtilitiesTrait
{

   public function isLocationInsideRadius($targetedLocationLat, $targetedLocationLong, $centerLat, $centerLon, $radiusMeters)
    {
        /**
         * DOC:
         *  TO CHECK IF THE USER/LOCATION INSIDE A CRETIN RADIUS USING Haversine formula
         * 
         *  targetedLocationLat => chosen lat for the user/location 
         *  targetedLocationLong => chosen long for the user/location 
         *  centerLat => is the center Lat of the location the radius will be starting from 
         *  centerLon => is the center Long of the location the radius will be starting from 
         *  radiusMeters => how big is the radius is
         */

        $targetedLocationLat = (float)$targetedLocationLat;
        $userLng = (float)$targetedLocationLong;
        $centerLat = (float)$centerLat;
        $centerLon = (float)$centerLon;

        $earthRadius = 6371000; // Radius of the Earth in meters

        $dLat = deg2rad($centerLat - $targetedLocationLat);
        $dLng = deg2rad($centerLon - $userLng);
    
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($targetedLocationLat)) * cos(deg2rad($centerLat)) * sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        $distance = $earthRadius * $c; // Distance in meters
        return $distance <= $radiusMeters;
    }

   public function areRadiusOverlapping($centerLat1, $centerLon1, $radius1, $centerLat2, $centerLon2, $radius2) 
    {
        /**
         * DOC:
         *  CHECK IF THE TOW RADIUS ARE OVER LAPPING
         */

        $earthRadius = 6371000; // Radius of the Earth in meters

        $centerLat1 = (float)$centerLat1;
        $centerLon1 = (float)$centerLon1;
        $centerLat2 = (float)$centerLat2;
        $centerLon2 = (float)$centerLon2;
    
        $dLat = deg2rad($centerLat2 - $centerLat1);
        $dLng = deg2rad($centerLon2 - $centerLon1);

    
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($centerLat1)) * cos(deg2rad($centerLat2)) * sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        $distance = $earthRadius * $c; // Distance in meters
        
        return $distance < (int)($radius1 + $radius2);
    }

    public function getReservingTimes($minutes,$city,$fleet,$price)
    {
        /**
         * GET THE RESERVING TIME ACCORDING TO CITY IF EXIST IF NOT 
         * GET THE FLEET PRICING
         * 
         * HOURS: NUMBER OF HOURS BETWEEN THE PICKUP TIME AND THE TRIP TIME
         * INTERVAL: MINUTES THAT THE USER WILL BE CHARGED FOR 
         * EX. IF THE HOURS IS 5 AND THE INTERVAL 15MIN
         * CAL: 5 * 60 = 300 
         * 300 / 15 = 20 (NUMBER OF INTERVAL)
         * TAKE THE NUMBER OF INTERVAL
         * @param CITY : IF THE CITY EXIST TAKE THE CITY PRICING
         * @param FLEET : IF THE CITY DOSE'T EXIST TAKE THE FLEET PRICING
         * @param PRICE: RESERVATION PRICE 
         */

        /**
            * Ranges:
             * 1 to 6 Hours
             * 7 to 12 Hours
             * 13 to 19 Hours
             * 20 to 24 Hours
             * 
             * note: if there a city it will take the reserving time from it else it will take it from the fleet category
        */

         if ($minutes <= 1440) {
             
             #get the number of hours in the reminding hours
            //  $latesHours = 24 - ($minutes / 60);
             $latesHours = ($minutes / 60);

             #get the number of min in the reminding hours
             $totalMinutesIn24Hours = $minutes;


          
            $timeRange = (array) [
                [
                    'hour_range_from'=>1,
                    'hour_range_to'=>6,
                    'max_minutes'=>360
                ],
                [
                    'hour_range_from'=>7,
                    'hour_range_to'=>12,
                    'max_minutes'=>360
                ],
                [
                    'hour_range_from'=>13,
                    'hour_range_to'=>19,
                    'max_minutes'=>360
                ],
                [
                    'hour_range_from'=>20,
                    'hour_range_to'=>24,
                    'max_minutes'=>360
                ]
            ];
            $overAllReservingPrice = 0;
            
            
            foreach($timeRange as $index => $range )
            {
               
                $range = (object)$range;

                if($range->hour_range_from <= $latesHours)
                {
                    #take the fleet category pricing
                    $reservingTime = $fleet->reservingTimes()->where('from_hour', $range->hour_range_from)->first();

                    #take the city pricing if the city dose exist
                    if($city)$reservingTime = $city->reservingTimes()->where('from_hour', $range->hour_range_from)->first();

                    #if reserving time was found take the price
                    if ($reservingTime && $totalMinutesIn24Hours != 0 ) 
                    {
                       
                        #charge for every minutes
                        $splitMinutesMechanism = (float) $reservingTime->charge;

                        #get the added price by takeing percentage from the current overall price
                        $added_price = $this->getPriceFromPercentage($price, $splitMinutesMechanism);

                        
                        #get the number of minutes will be charged for                        
                        $chargedMinutes = $totalMinutesIn24Hours <= $range->max_minutes ? $totalMinutesIn24Hours : $range->max_minutes;
                        

                        #The number of minutes that will be charge for example 15,30,45,60 minutes
                        $pricePerMinutes = $added_price / $reservingTime->period;

                        
                        #get added price
                        $added_price = $chargedMinutes * $pricePerMinutes;

                        
                        #add the added price to the overall price
                        $overAllReservingPrice = $overAllReservingPrice + $added_price;

                        #subtract number of minutes for the range 
                        $totalMinutesIn24Hours = $totalMinutesIn24Hours <= $range->max_minutes ? $totalMinutesIn24Hours : $totalMinutesIn24Hours - $range->max_minutes;
                    }

                }
               
            }
            return $overAllReservingPrice;
        }
    }

    public function generateCustomerId($email, $name)
    {
        /**
         * Doc: Create an customer id for stripe
         */

        try 
        {
            $stripe = new StripeClient(env('STRIPE_SECRET'));
            $customer = $stripe->customers->create([
                'email' => $email,
                'name' => $name,
            ]);

            $stripe_id = $customer->id;
            return $stripe_id;
        } 
        catch (Exception $e) 
        {
            throw $e;
        }
    }

    public function userGeneratePassword($length = 10)
    {
        /**
         * Doc: generate an strong password
         */

        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#?';

        $password = '';
        $charCount = strlen($chars);

        while (strlen($password) < $length) {
            $randomChar = $chars[rand(0, $charCount - 1)];
            $password .= $randomChar;
        }

        if (
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[!@#?]/', $password)
        ) {
            return $this->userGeneratePassword($length);
        }

        return $password;
    }

    public function setTimezoneGmt($gmt)
    {
        /** Get The word and decide if it's minus or plus  */
        $timeInfo = $this->splitGMTWord($gmt);
        $currentTime = Carbon::now('GMT');
        
        if($timeInfo[0] == '+') $currentTime->addHours($timeInfo[1]);
        else $currentTime->subHours($timeInfo[1]);
        return $currentTime;
    }

    public function serviceLocationRestrictionsConditions($service,$coordinates)
    {
        /**
         * DOC: this function determine if the location is available for service or not 
         * @param service : the service need to check point to point / hourly
         * @param coordinates : to check if it's for pickup or drop-off
         */
        $checkPointToPoint = $service == 'point_to_point' || $service == 'both' ? false : true;
        $checkHourly = $service == 'hourly' || $service == 'both' ? false : true;
        $checkPickUp = $coordinates == 'pick_up' || $coordinates == 'both'? false: true;
        $checkDropOff = $coordinates == 'drop_off' || $coordinates == 'both'? false: true;

        return [
            'point_to_point'=>$checkPointToPoint,
            'hourly'=>$checkHourly,
            'pick_up'=> $checkPickUp,
            'drop_off'=>$checkDropOff
        ];
    }

    public function isUserInsideRadiusMiles($userLat, $userLon, $centerLat, $centerLon, $radiusKM)
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

    private function generate_slug($slug = null)
    {
        return $slug?str_replace(' ', '-', $slug):$this->generateRandomSlug(8);

    }
    
    private function getPriceFromPercentage($price, $percentage)
    {
        // Calculate the discounted price
        $discountedPrice = ($percentage / 100) * $price;

        return $discountedPrice;
    }

    private function isTimeBetweenRange($checkTime, $startTime, $endTime) 
    {
        // Parse the provided time as a Carbon instance
        $checkDateTime = Carbon::parse($checkTime);
    
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = Carbon::parse($endTime);
    
        // Handle the case where the end time is earlier than the start time
        if ($endDateTime->lt($startDateTime)) {
            // If check time is after end time, add one day to end time
            if ($checkDateTime->gte($endDateTime)) {
                $endDateTime->addDay();
            } else {
                // If check time is before start time, subtract one day from start time
                $startDateTime->subDay();
            }
        } 
    
        return $checkDateTime->between($startDateTime, $endDateTime);
    }

    function isTimeInRange12HourFormat($startTime, $endTime, $checkTime) 
    {
        // Convert 12-hour format times to 24-hour format
        $startDateTime = Carbon::createFromFormat('h:i A', $startTime);
        $endDateTime = Carbon::createFromFormat('h:i A', $endTime);
        $checkDateTime = Carbon::createFromFormat('h:i A', $checkTime);
    
        // Adjust end time if it's before start time to handle overnight ranges
        if ($endDateTime->lt($startDateTime)) {
            $endDateTime->addDay(); // Move the end time to the next day
        }
    
        // Check if the check time is within the range
        return $checkDateTime->between($startDateTime, $endDateTime);
    }

    private function splitGMTWord($word) 
    {
        $pattern = '/^GMT([+-]\d{1,2})$/';
    
        if (preg_match($pattern, $word, $matches)) {
            $offsetPart = $matches[1];
            $sign = substr($offsetPart, 0, 1); // Extract the sign (+ or -)
            $number = substr($offsetPart, 1);   // Extract the number without the sign
            
            return array($sign, $number);
        } else {
            return false;
        }
    }

    private function generateRandomSlug($length = 8) 
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $slug = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, strlen($characters) - 1);
            $slug .= $characters[$randomIndex];
        }
    
        return $slug;
    }


    
    
     
}