<?php

namespace App\Traits;

trait CalculationTrait {

    public function getHourlyPrice($pricePerHour,$customerTripMiles,$hours,$milePerHor,$extraMilePrice,$min_hours,$extra)
    {
        /**
         * Doc:
         * 
         * Price Per Hour => How Much Should We Charge Per Every Hour
         * Customer trip miles => The Total Amount Of Miles For The Trip
         * Hours => Number Of Hours The User Have Selected
         * Mile Per Hour => The maximum  Miles per Hour
         * Extra Mile Price => The Cost Of Every Extra Mile Exceeding The Maximum Miles For An Hour
         * Min Hours => The minium hours we should calculate the price for
         * Extra => The Extra Prices We Should Add To The Overall Price such as child seats
         */

        
        $tripMaxMilesWithOutCharge = $min_hours*$milePerHor;
        $tripOverAllDestines = (float) $customerTripMiles;
        /** GET MINIUM PRICING HOURS */
         if((int)$hours < (int)$min_hours)
         {

             $totalPrice = (float) $pricePerHour * $min_hours;

         }
         else
         {
            $totalPrice = (float) $pricePerHour * $hours;
            $tripMaxMilesWithOutCharge = $hours*$milePerHor;

           
         }
        
        /** Charge for the extra miles */
        if($tripMaxMilesWithOutCharge < $tripOverAllDestines)
        {
            
            $extraMiles = $tripOverAllDestines - $tripMaxMilesWithOutCharge;
            $extraPrice = ((float)$extraMilePrice * $extraMiles);
            $totalPrice = $totalPrice + $extraPrice;

         
        }

      

        return $totalPrice;
    }

    public function getPointToPointPrice($totalMiles,$minimumMiles,$pricePerMinimumMile,$pricePerExtraMile,$extra)
    {
        /**
         * Doc:
         * 
         * Total Miles => Miles for the whole trip.
         * Minimum Miles => The Miles If The User Choose Less It Will Not Cost Any Extra.
         * Price Per Minimum Mils => The Initial Price For The Trip This Will Depend on The Vehicle Type.
         * Price Per Extra Mile => The Price For Each Extra Mile Above The Initial Price.
         * Extra => The Extra Prices We Should Add To The Overall Price such as child seats.
         */

        $totalPrice = (float)$pricePerMinimumMile;
        $extraMiles = $totalMiles - $minimumMiles;
        if($extraMiles > 0)
        {

            $getExtraPrice = (float)$pricePerExtraMile * $extraMiles;

            $totalPrice = $totalPrice + $getExtraPrice;
        }


        return $totalPrice;

    }
    
}