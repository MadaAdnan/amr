<?php
namespace App\Traits;

use App\Models\Reservation;
use Carbon\Carbon;
use Exception;

trait ReservationResponse {

    /**
     * Doc:
     *  The response formate for all trips
     */
    protected $status = [
        'pending' => 'Pending',
        'accepted' => 'Accepted',
        'assigned'=>'Assigned',
        'on the way' => 'On the way',
        'arrived at the pickup location' => 'Arrived at the pickup location',
        'passenger on board' => 'passenger on board',
        'completed' => 'Completed',
        'canceled' => 'Canceled',
        'late canceled' => 'Late Cancel',
        'finished' => 'Finished',
        'no show' => 'No Show',
        'failed' => 'Failed'
      
    ];



    public function reservationResponse($item, $add_card_info = false)
    {

        // Get the child seats with the pivot data (amount)
        $childSeatsWithPivot = $item->childSeats()->withPivot('amount')->get();
        $coupon_code = $item->coupons;
        $price_after_discount = $item->price ;
        $discountAmount = 0.0;

        if (isset($coupon_code)) {
            if ($coupon_code->discount_type == 'Percentage') {
                $discountAmount = ($item->price  * $coupon_code->percentage_discount) / 100;


            } 
            else 
            {
                $discountAmount = $coupon_code->percentage_discount;
            }


            $price_after_discount =  $item->price - $discountAmount;
        }

        // Initialize an empty array to store the seat titles
        $seatTitles = [];

        // Iterate through the child seats and add their titles to the array based on the pivot amount
        foreach ($childSeatsWithPivot as $childSeat) {
            // Get the seat title
            $seatTitle = $childSeat->title;

            // Get the pivot amount (how many times the seat was chosen)
            $pivotAmount = $childSeat->pivot->amount;

            // Add the seat title to the array $pivotAmount times
            for ($i = 0; $i < $pivotAmount; $i++) {
                $seatTitles[] = $seatTitle;
            };
        }

        // Now $seatTitles contains an array of seat titles with appropriate repetitions
        $today = Carbon::today();
        $noDataMessage = '';
        $cancelMessage = '';

        $pick_up_dateTimeString = $today->toDateString() . ' ' . $item->pick_up_time;
        $return_dateTimeString = $item->parent ? $today->toDateString().' '.$item->parent->pick_up_time :$today->toDateString() . ' ' . $item->return_time;
        $pick_up_time_formatted = Carbon::parse($pick_up_dateTimeString);

        if ($item->parent&&$item->parent->pick_up_time) {
            $return_time_formatted = Carbon::parse($return_dateTimeString);
        } else {

            $return_time_formatted = null;
        }

        $pickUpTime = Carbon::parse($item->pick_up_time);
        $currentTime = Carbon::now();
        if ($pickUpTime->diffInHours($currentTime) < 24) {
            $cancelMessage = 'Are you sure you want to cancel the trip?';
        } else {

            $cancelMessage = 'Late cancelled';
        }



        $response = [
            'id' => $item->id,
            'pick_up_location' => $item->pick_up_location,
            'first_name' => $item->users->first_name,
            'last_name' => $item->users->last_name,
            'drop_off_location' => $item->drop_off_location ?? $noDataMessage,
            'pick_up_date' => $item->pick_up_date ? Carbon::parse($item->pick_up_date)->format("F d, Y") : null,
            'pick_up_time' => $pick_up_time_formatted->format('g:i A'),
            'transfer_type' => $item->transfer_type,
            'duration' => number_format($item->duration) ?? number_format(0, 2),
            'distance' => number_format($item->distance) ?? number_format(0, 2),
            'is_round' => $item->parent || $item->child ? true : false,
            'phone_primary' => $item->phone_primary ?? $noDataMessage,
            'phone_secondary' => $item->phone_secondary ?? $noDataMessage,
            'country_code' => $item->users ? $item->users->country_code : $noDataMessage,
            'service_type' => $item->service_type,
            'tip' => number_format($item->tip,2) ?? $noDataMessage,
            'price' => number_format($item->price ,2),
            'total_price' => number_format($price_after_discount + $item->tip ,2) ,
            'price_after_discount' => number_format($discountAmount,2), //TODO: discount amount name need to be changed TO discount amount
            'latitude' => $item->latitude,
            'longitude' => $item->longitude,
            'dropoff_latitude' => $item->dropoff_latitude,
            'dropoff_longitude' => $item->dropoff_longitude,
            'comment' => $item->comment ?? $noDataMessage,
            'child_seats' => $seatTitles ? $seatTitles : [],
            'airline' => $item->airlines ? $item->airlines->name : '',
            'flight_number' => $item->flight_number ?? $noDataMessage,
            'coupon' => $item->coupons ? $item->coupons->percentage_discount : 0.0,
            'discount_type' => isset($item->coupons) ? $item->coupons->discount_type == 'Price' ? 1 : 0 : 2,
            'vehicle_type' => $item->fleets ? $item->fleets->title : $noDataMessage,
            'driver_name' => $item->drivers ? $item->drivers->full_name : "",
            'driver_phone' => $item->drivers ? $item->drivers->phone : '',
            'status' => $this->status[$item->status],
            'is_late_canceled' => $item->status == Reservation::LATE_CANCELED ? true : false,
            'canceled_message' => $cancelMessage,
            'license' => isset($item->vehicle) ? $item->vehicle->license : '',
            'short_title' => isset($item->fleets) ? $item->fleets->short_title : '',
        ];

        if ($add_card_info) {
            $response['card'] = $this->get_card_info($item);
        }

        return $response;
    }


    private function get_card_info($reservation)
    {
        try {
            $bill = $reservation->bills()->where('type', 'Trip')->first();
            return [
                'brand' => $bill ? $bill->card_brand : '',
                'last4' => $bill ? $bill->last_four : '',
            ];
        } catch (Exception $e) {
            /** HANDEL THE STRIPE ERROR AND RETURN EMPTY STRING */
            return '';
        }
    }

    private function check_is_late_cancel($item)
    {
        try {
            $refundable = false;
            $data = $item;
            $data->status = 'late canceled';


            $pick_up_date = new Carbon($data->pick_up_date . ' ' . $data->pick_up_time);
            $checkHours = !Carbon::now()->isAfter($pick_up_date) ? Carbon::now()->diffInHours($pick_up_date) : 0;

            $checkVan = false;

            //        168 is hours number in 7 days (24*7) , and check the vehicle if Van
            //        if the vehicle is not Van, just check if more than 24h

            if (($checkVan && $checkHours > 168) || (!$checkVan && $checkHours > 24)) {
                $data->status = 'pending refund';
            }

            $data->save();

            return $this->NewResponse(true, "The ride has been canceled successfully.", null, 200);
        } catch (Exception $e) {
            return $this->NewErrorResponse('Something wrong with updating');
        }
    }







}