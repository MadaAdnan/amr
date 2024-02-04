<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Spatie\Activitylog\Models\Activity;
use App\Traits\LogErrorAndRedirectTrait;

class LogsController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Logs Controller
    |--------------------------------------------------------------------------
    |
    | user all users action in the dashboard
    |
    */

    use LogErrorAndRedirectTrait;


    public function userLogs()
    {
          /**
        * User logs
        * 
        * Doc: get the users logs 
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #get the activity data
            $activities = Activity::orderBy('created_at', 'desc')
            ->paginate(config('general.dashboard_pagination_number'));

            $changesData = [];
    
            #loop throw the activities to format it
            foreach ($activities as $activity) 
            {

                $propertiesJson = $activity->properties;
    
                if ($propertiesJson !== null) {
                    $propertiesArray = json_decode($propertiesJson, true);
    
                    // Check if the activity is related to the Reservation model
                    if (isset($activity['subject_type']) && $activity['subject_type'] === 'App\Models\Reservation') {
    
                        if (isset($propertiesArray['attributes']) && isset($propertiesArray['old'])) 
                        {
                            $attributes = $propertiesArray['attributes'];
                            $oldAttributes = $propertiesArray['old'];
    
                            $changes = [];
    
                            foreach ($attributes as $attribute => $newValue) 
                            {
                                if (array_key_exists($attribute, $oldAttributes)) 
                                {
                                    $oldValue = $oldAttributes[$attribute];
                                    if ($newValue !== $oldValue) 
                                    {
                                        $changes[] = [
                                            'attribute' => $attribute,
                                            'old' => $oldValue,
                                            'new' => $newValue,
                                        ];
                                    }
                                }
                            }
    
                            $subjectId = $activity->subject_id;
                            $causer = User::find($activity->causer_id);
                            $causerName = $causer ? $causer->first_name : 'Unknown User';
    
                            $changesData[] = [
                                'activity_id' => $activity->id,
                                // Use the activity ID, not attributes
                                'changes' => $changes,
                                'causer' => $causerName,
                                'subject_id' => $subjectId,
                            ];
                        }
                    }
                }
            }

            return view('dashboard.logs.index', compact('changesData', 'activities'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting countries in get location: ');
            return back(); 
        }

    }

    private function isValidProperties($properties)
    {
        return isset($properties['attributes'], $properties['old']);
    }
    
    private function getChanges($properties)
    {
        return collect($properties['attributes'])
            ->map(function ($newValue, $attribute) use ($properties) {
                $oldValue = $properties['old'][$attribute] ?? null;
                return compact('attribute', 'oldValue', 'newValue');
            })
            ->filter(function ($change) {
                return $change['oldValue'] !== $change['newValue'];
            })
            ->toArray();
    }

    public function getModelLogs($modelType)
    {
        $activities = Activity::where('subject_type', 'App\Models\\' . $modelType)->orderByDesc('created_at')->paginate(10);
        $changesData = [];

        foreach ($activities as $activity) {
            $properties = json_decode($activity->properties, true);

            if ($this->isValidProperties($properties)) {
                $changes = $this->getChanges($properties);
                $causerName = optional(User::find($activity->causer_id))->first_name ?? 'Unknown User';

                $changesData[] = compact('activity', 'changes', 'causerName');
            }
        }
        return view('dashboard.logs.' . strtolower($modelType) . '_logs', compact('changesData', 'activities'));
    }

    public function blogLogs()
    {
        return $this->getModelLogs('Post');
    }

    public function serviceLogs()
    {
        return $this->getModelLogs('Services');
    }

    
}