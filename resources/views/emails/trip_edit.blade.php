@if($details['success'] && !isset($details['target']))
<p>
    Hi {{ $details['customer']->first_name }}
</p>
<p>
    Thank you for choosing Lavishride
</p>
<p>
    your booking was updated successfully
</p>
<p>
    If you need to get in touch, you can email or phone us directly at +1-888-816-1015
</p>
<p>
    Please feel free to contact us regarding any other inquiries or requests.
</p>
<p>
    LavishRide Team
</p>
@elseif(!$details['success'] && !isset($details['target']))
<p>
    Hi {{ $details['customer']->first_name }}
</p>
<p>
    Thank you for choosing Lavishride
</p>
<p>
    Unfortunately, There was a problem with the payment while trying to update the trip.
</p>
<p>
    We will get in touch with you very soon
</p>
<p>
    Feel free to contact us via reservation@lavishride.com from the email address linked to your account or call us at +1-888-816-1015
</p>
<p>
    LavishRide Team
</p>
@elseif(isset($details['target']) && !isset($details['new_status']))
<p>A Problem Happened While Updating Trip Number #{{$details['trip']->id}}</p>
@else
<p>Trip Number #{{$details['trip']->id}} has been canceled</p>
@endif