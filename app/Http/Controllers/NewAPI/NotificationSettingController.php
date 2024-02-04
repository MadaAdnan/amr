<?php

namespace App\Http\Controllers\NewAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Traits\CalculationTrait;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\NotificationTrait;

class NotificationSettingController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Notification Setting Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the notification apis in the mobile
    |
    */

    use CalculationTrait, NotificationTrait,LogErrorAndRedirectTrait , JsonResponseTrait;

    public function addNotificationSetting(Request $request)
    {
        /**
        * Add Notification Setting
        * 
        * Doc: save notification setting for the user 
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

      try 
      {
        
            $user = auth()->user();
            
            #Get the existing notification settings or create a new record if they don't exist
            $existingSettings = DB::table('notification_settings')
                ->where('user_id', $user->id)
                ->first();
        
            if ($existingSettings) 
            {
                #Get the data as an array
                $typeInfo = json_decode($existingSettings->type, true);

                #check if the type is correct
                if (is_array($typeInfo) && isset($typeInfo['types'])) 
                {
                    
                    $requiredType = $request->notification_type;
                    $newStatus = $request->status == 'true' || $request->status == 'True' ? true : false;
                    $found = false;

                    foreach ($typeInfo['types'] as &$type) {
                        if ($type['notification_type'] === $requiredType) 
                        {
                            $type['status'] = $newStatus;
                            $found = true;
                            break;
                        }
                    }
        
                    if (!$found) 
                    {
                        return $this->NewResponse(true, "You can not add new type", null, 402);
                    }
        
                    #Update the entire record with the merged data
                    DB::table('notification_settings')
                        ->where('user_id', $user->id)
                        ->update(['type' => json_encode($typeInfo)]);
                } 
                else 
                {
                    return $this->NewResponse(true, "Invalid", null, 402);
                }
            } 
            else 
            {
                #If there are no settings for this user, create them
                $this->create_notification_setting($user);
            }
        
            #Retrieve the updated settings from the database
            $updatedSettings = DB::table('notification_settings')
                ->where('user_id', $user->id)
                ->first();
        
            #Rebuild the $notificationSettings array
            $notificationSettings = [];
            if ($updatedSettings) {
                $typeInfo = json_decode($updatedSettings->type, true);
                if (is_array($typeInfo) && isset($typeInfo['types'])) {
                    foreach ($typeInfo['types'] as $type) {
                        $notificationSettings[] = [
                            'notification_type' => $type['notification_type'],
                            'status' => $type['status'],
                        ];
                    }
                }
            }
        
            return $this->NewResponse(
                true,
                $notificationSettings,
                null,
            config('status_codes.success.ok'));
        }
        catch (Exception $e) 
        {
            return $this->logErrorJson($e, 'Error in adding notification setting: ');
        }
    }

    public function getNotificationSetting()
    {
        /**
        * Get Notification Setting
        * 
        * Doc: get the notification for the user 
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            #get the user setting
            $user = auth()->user();
            $userTypes = DB::table('notification_settings')
            ->where('user_id', $user->id)
            ->select('type')
            ->first();

            #get the user info
            if ($userTypes) 
            {
                $typeInfo = json_decode($userTypes->type, true);
        
                if (is_array($typeInfo) && isset($typeInfo['types'])) {
                    $typesAndStatuses = [];
        
                    foreach ($typeInfo['types'] as $typeData) {
                        $notificationType = $typeData['notification_type'];
                        $status = $typeData['status'];
                        $typesAndStatuses[] = [
                            'notification_type' => $notificationType,
                            'status' => $status,
                        ];
                    }
        
                    return $this->NewResponse(
                        true,
                        $typesAndStatuses,
                        null,
                        200
                    );
                } 
                else 
                {
                    return $this->NewResponse(
                        true,
                        "There is no notification data for the user",
                        null,
                        200
                    );
                }

            } 
            else 
            {
                #create notification for the user
                $newTypeData = $this->create_notification_setting($user);
        

                    // Return the newly created settings as an array of objects
                    return $this->NewResponse(
                        true,
                        $newTypeData['types'],
                        null,
                        200
                    );
        
            }
        
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error getting notification: ');
        }
    }

    private function create_notification_setting($user)
    {
         /**
        * Create notification setting
        * 
        * Doc: create a notification setting for the user 
        *
        * @param App\Model\User $user
        *
        * @return Array
        */
        try
        {
            $newTypeData = [
                'types' => [
                    [
                        'notification_type' => 'Trip status',
                        'status' => true,
                    ],
                    [
                        'notification_type' => 'updates',
                        'status' => true,
                    ],
                ],
            ];
    
            DB::table('notification_settings')->insert([
                'user_id' => $user->id,
                'type' => json_encode($newTypeData),
            ]);
    
            return $newTypeData;
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,"Error creating notification: ");
        }
    }


}


