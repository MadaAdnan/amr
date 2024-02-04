<?php

namespace App\Traits;

use App\Models\NotificationSetting;
use App\Notifications\TestAction;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Services\ReservationStatusService;


trait NotificationTrait{
    
    protected $upComingStatus =['accepted','pending','assigned', 'on the way', 'arrived at the pickup location', 'passenger on board'];
    protected $canceledStatus = ['late canceled','canceled','failed'];
    protected $compleatedStatus = ['no show','Extra time or mile','completed'];

    function sendNotificationSetting($user)
    {
        $jsonSettings = NotificationSetting::where('user_id', $user->id)->first();
        $userSettings = $user->notificationSetting;

        if ($jsonSettings) {
            $decodedSettings = json_decode($jsonSettings->type, true); // true converts it to an array

            // Check if 'types' key exists in the decoded settings
            if (isset($decodedSettings['types']) && is_array($decodedSettings['types'])) {
                $types = $decodedSettings['types'];

                foreach ($types as $jsonSetting) {

                    if ($jsonSetting['notification_type'] === 'Trip status' && $jsonSetting['status'] === false) {
                        // Return false if the status of "Trip status" is false
                        return false;
                    }

                    // Find the corresponding setting in the user's settings
                    $notificationType = $jsonSetting['notification_type'];
                    $userSetting = $userSettings->where('type->types[*].notification_type', $notificationType)->first();

                    // Check if the status in the JSON object is false while the user setting is true
                    if ($jsonSetting['status'] === false && $userSetting && $userSetting->status === true) {
                        return true; // The user has turned off some other notification
                    }
                }
            }
        }

        return true; // Default to true if there's no "Trip status" setting or it's true
    }

    public function sendNotifications($tokens,$data,$topic = false)
    {

        $apiKey = env('GOOGLE_SERVER_KEY');
        $url = 'https://fcm.googleapis.com/fcm/send';
        $data['sound'] = 'default';
        $data['notification_count'] = 150;
        $data['icon'] = 'ic_launcher';
        $fields = array(
            'notification' => $data,
            'data' => $data
        );
        if($topic){
            $fields['topic'] = $topic;
        }else{
            $fields['registration_ids'] = $tokens;
        }
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
        // Set the URL, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields));
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // Execute post
        $result = curl_exec($ch);
        curl_close($ch);
        if ($httpcode != 200) {
			Log::error('result fcm: ' . $result);
            Log::error('Curl failed: ' . curl_error($ch));
        }
        /** Add the notification to the database */
      
    }
    
    public function response($item, $unreadCount=0)
    {
        #This is a format If there is no data
        $obj = [
            'title' => '',
            'type' => '',
            'message' => '',
            'reservation_id' => '',
            'reservation_status' => '',
            'query_status' => '',
            'date' => $item->created_at,
            'badge'=>$unreadCount,
        ];

        if(array_key_exists("reservation_status",$item['data']))
        {
            if(in_array($item['data']['reservation_status'],$this->upComingStatus))
            {
                $query_status='Upcoming';
            }
            else if(in_array($item['data']['reservation_status'],$this->canceledStatus))
            {
                $query_status='Canceled';
            }
            else if(in_array($item['data']['reservation_status'],$this->compleatedStatus))
            {
                $query_status='Completed';
            }
        }
        //If there i data 
        if (count($item['data']) > 0) 
        {
            $obj['message'] = $item['data']['text'];
            $obj['title'] = $item['data']['title'] ?? 'No Title';
            $obj['type'] = $item['data']['type'] ?? "none";
            $obj['data'] = array_key_exists("data",$item['data'])?strval($item['data']['data']):'';
            $obj['reservation_status'] = array_key_exists("reservation_status",$item['data'])?$item['data']['reservation_status']:'';
            $obj['query_status'] = $query_status??'';
        }

        return $obj;
    }

    public function sendCreateReservationNotification($reservation,$fcms,$customer)
    {
        /**
         * Send Create Reservation Notification
         * 
         * Doc: when the user create a reservation this notification will be sent and save it into the database
         * 
         * @param App\Models\Reservation $reservation
         * @param App\Models\User $user
         * @param Array $fcm array of string inside all the fcms the need to be sent to the notification
         * 
         * 
         * 
         * @return void
         */

         try
         {
            #save the notification information into the database
            $notificationContent = [
                'title' => 'Trip # ' . $reservation->id . ' Created!',
                'text' => " Your trip will be reviewed and accepted shortly",
                'type' => 'Trip Status Update',
                'reservation_id' => $reservation->id,
                'reservation_status' => $reservation->status,

            ];
            $customer->notify(new TestAction($notificationContent));

            #send notification to the mobile using firebase
            $this->sendNotifications(
                $fcms,
                [
                    "title" => 'Trip # ' . $reservation->id . ' Created!',
                    "body" => 'Your trip will be reviewed and accepted shortly',
                    "icon" => "icon-url",
                    'type' => 'in_app_message',
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                    'android' => [
                        'channelId' => 'high_priority_alerts',
                    ],
                ]
            );

            return;

         }
         catch(Exception $e)
         {
            return $this->logErrorJson($e,'Error sending create reservation notification');
         }
    }

    public function sendNotificationAccordingToReservationStatus($reservation,$fcms,$chauffeur = null,$customer)
    {
        /**
         * Send Notification According To Reservation Status
         * 
         * Doc: send the user notification according to it's status
         * 
         * @param Array $fcms
         * @param String App\Models\Reservation
         * 
         * 
         * @return void;
         */

         $msg = '';
         $title = '';

         #get the correct message according to the status
         switch ($reservation->status) 
         {
            case "pending":
                $title = 'Trip #' . $reservation->id . ' Update!';
                $msg = 'Your trip will be reviewed and accepted shortly';
                break;
            case 'accepted':
                $title = 'Trip #' . $reservation->id . ' Update!';
                $msg = 'Your trip is accepted and will be assigned to a chauffeur shortly.';
                break;
            case 'canceled':
                $title = 'Trip #' . $reservation->id . ' Canceled!';
                $msg = 'Your trip is canceled and will be fully refunded.';
                break;
            case 'assigned':
                $title = 'Trip #' . $reservation->id . ' Update!';
                $msg = "Your trip # " . $reservation->id . " chauffeur," . $chauffeur . " is assigned";
                break;
            case "on the way":
                $title = 'Trip #' . $reservation->id . ' Update!';
                $msg = "Your chauffeur " . $chauffeur . " is on their way to you.";
                break;
            case 'arrived at the pickup location':
                $title = 'Trip #' . $reservation->id . ' Update!';
                $msg = "Your chauffeur of trip #" . $reservation->id . " arrived at " . $reservation->pick_up_location . ".";
                break;
            case 'passenger on board':
                $title = 'Trip #' . $reservation->id . ' Update!';
                $msg = "You are on your way to " . $reservation->drop_off_location . ".";
                break;
            case 'completed':
                $title = 'Trip #' . $reservation->id . ' Completed!';
                $msg = "Your trip #" . $reservation->id . " is completed successfully.";
                break;
            case 'late canceled':
                $title = 'Trip #' . $reservation->id . ' Canceled!';
                $msg = "Your trip #" . $reservation->id . " is late canceled.";
                break;
                
            case 'failed':
                $title = 'Trip #' . $reservation->id . ' Canceled!';
                $msg = "Your trip #" . $reservation->id . " is failed.";
                break;

            case 'no show':
                $title = 'Trip #' . $reservation->id . ' Canceled!';
                $msg = "Your trip # " . $reservation->id . " is marked as 'No Show'.";
                break;
         }

         
         if($this->sendNotificationSetting($customer))
         {
             #notification content and create it in the database
             $notification_data = 
             [
                'title' => $title,
                'text' => $msg,
                'type' => 'Trip Status Update',
                'reservation_id'=>$reservation->id,
                'reservation_status'=>$reservation->status,
             ];
             $customer->notify(new TestAction($notification_data));

             $this->sendNotifications($fcms,[
                "title" => $title,
                "body" => $msg,
                "icon" => "icon-url",
                'reservation_id' => $reservation->id,
                'type' => 'in_app_message',
                // "click_action" => "action-url"
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                'android' => [
                    'channelId' => 'high_priority_alerts',
                ],
             ]);

             $tripStatusService = new ReservationStatusService();
             $tripStatusService->sendNotifications($reservation->id, "update", null);
         }

    }

    public function sendTripStatusUpdate($reservation,$tokens,$user)
    {
        //appears in history
         $notification_data = [
            'title' => 'Trip # ' . $reservation->id . ' Created!',
            'text' => " Your trip will be reviewed and accepted shortly",
            'type' => 'Trip Status Update',
            'reservation_id' => $reservation->id,
            'reservation_status' => $reservation->status,

        ];

        #save the notification to the user
        $user->notify(new TestAction($notification_data));

        $this->sendNotifications(
            $tokens,
            [
                "title" => 'Trip # ' . $reservation->id . ' Created!',
                "body" => 'Your trip will be reviewed and accepted shortly',
                "icon" => "icon-url",
                'reservation_id' => $reservation->id,
                'type' => 'in_app_message',
                // "click_action" => "action-url"
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",

                'android' => [
                        'channelId' => 'high_priority_alerts',
                    ],
            ]
        );



    }



}