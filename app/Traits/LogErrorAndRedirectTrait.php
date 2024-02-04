<?php

namespace App\Traits;

use Exception;
use RealRashid\SweetAlert\Facades\Alert;

trait LogErrorAndRedirectTrait
{
    use JsonResponseTrait;
    
    private function logErrorAndRedirect(Exception $e, $logMessage)
    {
        /**
         * Log Error And Redirect
         * @param e The error exception
         * @param logMessage message will be printed in the log file 
         */
        \Log::error($logMessage . ': ' . $e->getMessage());
        Alert::toast('Something went wrong, please contact us at ' . config('general.support_email'));
        return;
    }

    private function logErrorJson(Exception $e, $logMessage,$customerMsg = null)
    {
        /**
         * Log Error Json
         * @param e The error exception
         * @param logMessage message will be printed in the log file 
         */

         #log the Exception
         \Log::error($logMessage . ': ' . $e->getMessage());

         #an json with the error message
         return $this->errorResponse($customerMsg? $customerMsg : 'Something went wrong, please contact us at '.config('general.support_email'),config('status_codes.server_error.internal_error'));
    }
}
