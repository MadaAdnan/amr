<?php

namespace App\Http\Controllers\NewAPI;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddCardRequest;
use App\Http\Requests\Api\DeleteCardRequest;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;
use Stripe\StripeClient;
use Stripe\PaymentMethod;
use Auth;


class StripeController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Stripe Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the stripe in the mobile
    |
    */

    public $stripe;

    use LogErrorAndRedirectTrait,JsonResponseTrait;

    public function __construct()
    {

    }

    public function addCard(AddCardRequest $request)
    {
         /**
        * Add Card
        * 
        * Doc: get the info needed to make a reservation
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $user = Auth::user();

            #get the card is already was added to the user
            if($this->checkCardAlreadyExist($request->customer_id,$request->card_number))
            {
                return $this->NewErrorResponseApi('card already exist to this account',config('status_codes.client_error.unprocessable'));
            };

          \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

          #fill the card details
          $cardDetails = 
          [
            'number' => $request->card_number,
            'exp_month' => $request->exp_month,
            'exp_year' => $request->exp_year,
            'cvc' => $request->cvc,
          ];
     
         #billing details
         $billingDetails = 
         [
             'name' => $request->name,
             'address' => [
                 'line1' => $request->street,
                 'city' => $request->city,
                 'state' => $request->state,
                 'postal_code' => $request->postel_code,
                 'country' => $request->country,
             ],
             'email' => $user->email,
             'phone' => $user->phone,
            ];

             
            #create a payment method
             $card = PaymentMethod::create([
                 'type' => 'card',
                 'card' => $cardDetails,
                 'billing_details'=>$billingDetails
             ]);
     
             #attach the card to the customer
             $card->attach(['customer' => $request->customer_id]);
             
             #return json
             return $this->NewResponse(true , 
                'The card has been added successfully', 
                null ,
                config('status_codes.success.ok')
            );

        }
        catch(Exception $e)
        {
            $this->logErrorJson($e,"Error adding :");
            return $this->NewErrorResponseApi($e->getMessage(),config('status_codes.client_error.unprocessable'));
        }

    }

    public function getCards()
    {
        /**
        * Get Cards
        * 
        * Doc: get the cards from the customer
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try 
        {
            #Create an associative array to store unique cards
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $uniqueCards = [];
            $customer_id = Auth::user()->stripe_id;

            $paymentMethods = PaymentMethod::all([
                'customer' => $customer_id,
                'type' => 'card',
            ]);

            foreach ($paymentMethods as $paymentMethod) 
            {
                #Use card fingerprint (preferred) or id as the unique identifier
                $uniqueIdentifier = $paymentMethod->card->fingerprint; #or $paymentMethod->id
        
                #Check if the card is already in the uniqueCards array (by unique identifier)
                $existingCard = array_filter($uniqueCards, function ($card) use ($uniqueIdentifier) {
                    return $card->unique_identifier === $uniqueIdentifier;
                });

                #If the card is not a duplicate, add it to the uniqueCards array as an object
                if (empty($existingCard)) 
                {
                    $uniqueCard = (object)[
                        'id' => $paymentMethod->id,
                        'brand' => $paymentMethod->card->brand,
                        'last4' => $paymentMethod->card->last4,
                        'unique_identifier' => $uniqueIdentifier,
                    ];

                    $uniqueCards[] = $uniqueCard;
                }
            }

            return $this->NewResponse(true , $uniqueCards, null , config('status_codes.success.ok'));
        } 
        catch (Exception $e) 
        {
            return $this->logErrorJson($e,'Error getting cards: ');
        }

    }

    public function deleteCard(DeleteCardRequest $request)
    {
         /**
        * Delete Cards
        * 
        * Doc: delete the card from the user
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $detachedPaymentMethod = \Stripe\PaymentMethod::retrieve($request->card_id);
            $detachedPaymentMethod->detach();

            return $this->NewResponse(true , 'Card was removed', null);

        }
        catch(Exception $e)
        {
            $this->logErrorJson($e,"Error in deleting the cards: ");

            return $this->NewErrorResponse($e->getMessage());
        }
    }

    private function checkCardAlreadyExist($customer_id,$cardNumber)
    {
         /**
        * Check card already exist
        * 
        * Doc: check if the card is need to be added is not already added
        *
        * @param Integer $customer_id
        * @param Integer $cardNumber
        *
        * @return Json
        */

        try
        {
            #check if the card exist throw going throw the payment the user made
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $cardNumbersToString = (string)$cardNumber;
            $getLastFourDigits = str_split($cardNumbersToString);
            $lasFour = array_slice($getLastFourDigits, -4);
    
    
            $paymentMethods = PaymentMethod::all([
                'customer' => $customer_id,
                'type' => 'card',
            ]);

            foreach ($paymentMethods as $paymentMethod) 
            {
    
               #Check if the card is already in the uniqueCards array (by unique identifier)
              if(implode($lasFour) == $paymentMethod->card->last4)
              {
                return true;
              };
    
              return false;
            }

        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,"Error in checking the card: ");
        }
    }

}
