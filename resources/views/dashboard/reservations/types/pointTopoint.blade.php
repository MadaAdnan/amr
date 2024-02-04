<div id="return_info_section" class="row">
    <div class="col-6">
        <label for="">Return Date</label>
        <input type="text" name="return_date" id="return_date" class="form-control datepicker"  value="{{isset($reservation->return_date)? $reservation->return_date : ''}}">
    </div>
    <div class="form-group col-md-6">
        <label for="time">Return Time</label>
        <div class="input-group date" id="return_time_div">
            <input id="return_time" name="return_time" type="time" class="form-control timeInput" value="{{isset($reservation->return_time)? $reservation->return_time : ''}}">
            <span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
        </div>
        <div id="returnTimeInput-error" class="error-container">
            <!-- Error message will be inserted here -->
        </div>
    </div>
</div>
