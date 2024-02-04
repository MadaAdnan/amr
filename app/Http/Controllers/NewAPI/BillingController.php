<?php

namespace App\Http\Controllers\NewAPI;

use App\Http\Requests\Api\GenerateSerialRequest;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\ReservationResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class BillingController extends Controller
{

     /*
    |--------------------------------------------------------------------------
    | Billing Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the billing apis in the mobile
    |
    */

    use ReservationResponse,LogErrorAndRedirectTrait;

    public function getBillsHistory(Request $request)
    {
         /**
        * Get Billing History
        * 
        * Doc: get the billing history 
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $user = Auth::user();

            #get the user bills 
            $bills = $user->bills()->whereHas('reservations')->orderBy('created_at','desc');
            
            #filter date
            if (isset($request->from_date) && isset($request->to_date)) 
            {
                $startDate = date('Y-m-d 00:00:00', strtotime($request->from_date));
                $endDate = date('Y-m-d 23:59:59', strtotime($request->to_date));

                $bills->whereBetween('created_at', [$startDate, $endDate]);
            }
    
           $bills = $bills->paginate(config('general.api_pagination'))->map(function($item){
                return [
                    'id'=>$item->reservations->id,
                    'tripDate'=>$item->created_at->format('Y/m/d'),
                    'price'=>$item->reservations->price,
                ];
            });
    
            return $this->NewResponse(true ,$bills, null , 200);
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error getting getting the billing history: ');
        }
    }

    public function generateSerial(GenerateSerialRequest $request)
    {
         /**
        * Generate Serial
        * 
        * Doc: generate a serial number for the invoice 
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $user = Auth::user();

            #get last ID from Invoice table
            $latestInvoiceId = Invoice::max('id');
            $invID = str_pad($latestInvoiceId + 1, 4, '0', STR_PAD_LEFT);
            $serial_number = "LR" . date('Ym').$invID;
    
            #Sort trip ids to check
            $trips = $request->trips_ids;
            sort($trips);
    
            #check the invoice if duplicate
            $invoices = Invoice::where('trips_ids', implode(',',$trips ))->first();
    
            if($invoices)
            {
                return $this->NewResponse(true , [
                    'serial_number' => $invoices->serial_number,
                    'total' => $invoices->total
                ], null , config('status_codes.success.ok'));
            }
            else
            {
                #get total price of trips
                $total_price = Reservation::whereIn('id', $trips)->sum('price');
    
                $input_data = [
                    'serial_number'=>$serial_number,
                    'trips_ids'=> implode(',',$trips ),
                    'total'=>$total_price,
                    'user_id'=>$user->id
                ];
                $invoice = Invoice::create($input_data);
    
                return $this->NewResponse(true , [
                    'serial_number' => $invoice->serial_number,
                    'total' => strval($invoice->total)
                ], null , config('status_codes.success.ok'));
            }
    
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error generating serial number: ');  
        }
    }
}