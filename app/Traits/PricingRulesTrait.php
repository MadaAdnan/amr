<?php

namespace App\Traits;

use App\Models\PriceRule;

trait PricingRulesTrait{


    /** 
     *  For Dashboard Fleet Category Pricing
     */
    public function fleetResponse($fleet,$city_id = null)
    {
        /**
         * Fleet Response:
         * 
         * @param App\Models\FleetCategory $fleet 
         * @param Integer $city_id
         * 
         * @return App\Models\FleetCategory with pricing added to it
         */
        
        #get the default pricing rules
        $pricingRules = (array)json_decode($fleet->pricing_rules);

        #add id to the pricing rules if it does't exist
        $pricingRules['id'] = 0;

        #add the default pricing for the both the "Hourly" and "Point to point" services
        $getHourlyPricing = $pricingRules;
        $getPointToPointPricing = $pricingRules;
        
        #if the city was sent check it's pricing
        if($city_id)
        {
            #get the pricing rules according to the service if it dose exist else get the default
            $searchForPricingCityHourly = PriceRule::where([
                ['service_id',PriceRule::SERVICE_TYPE_HOURLY],
                ['city_id',$city_id],
                ['category_id',$fleet->id]
                ])->first();

            $getPointToPointPricingPointToPoint = PriceRule::where([
                ['service_id',PriceRule::SERVICE_TYPE_POINT_TO_POINT],
                ['city_id',$city_id],
                ['category_id',$fleet->id]
                ])->first();

            #if the type was found get it's pricing rules
            if($searchForPricingCityHourly)
            {
               $getHourlyPricing =  $searchForPricingCityHourly->toArray();

            }
            if($getPointToPointPricingPointToPoint)
            {
                $getPointToPointPricing =  $getPointToPointPricingPointToPoint->toArray();
            }

        }

        #add the values of the pricing rules to the object
        $fleet->hourly = $getHourlyPricing;
        $fleet->pointToPoint = $getPointToPointPricing;

        #default pricing for both types
        $fleet->default_pricing = json_decode($fleet->pricing_rules);
        
        return $fleet;
    }

}