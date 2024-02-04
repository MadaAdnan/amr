<div class="row">

    <div class="col-6  mb-2 mt-2" >
        <label for="">Duration</label>

        <select onchange="getPriceAccordingToFleet()" name="duration" id="duration" class="form-control select2" >
            <option value="2" {{ isset($reservation) && $reservation->duration == 2 ? 'selected' : '' }}>2 Hours
            </option>
            <option value="3" {{ isset($reservation) && $reservation->duration == 3 ? 'selected' : '' }}>3 Hours
            </option>
            <option value="4" {{ isset($reservation) && $reservation->duration == 4 ? 'selected' : '' }}>4 Hours
            </option>
            <option value="5" {{ isset($reservation) && $reservation->duration == 5 ? 'selected' : '' }}>5 Hours
            </option>
            <option value="6" {{ isset($reservation) && $reservation->duration == 6 ? 'selected' : '' }}>6 Hours
            </option>
            <option value="7" {{ isset($reservation) && $reservation->duration == 7 ? 'selected' : '' }}>7 Hours
            </option>
            <option value="8" {{ isset($reservation) && $reservation->duration == 8 ? 'selected' : '' }}>8 Hours
            </option>
            <option value="9" {{ isset($reservation) && $reservation->duration == 9 ? 'selected' : '' }}>9 Hours
            </option>
            <option value="10" {{ isset($reservation) && $reservation->duration == 10 ? 'selected' : '' }}>10 Hours
            </option>
            <option value="11" {{ isset($reservation) && $reservation->duration == 11 ? 'selected' : '' }}>11 Hours
            </option>
            <option value="12" {{ isset($reservation) && $reservation->duration == 12 ? 'selected' : '' }}>12 Hours
            </option>
        </select>
    </div>
</div>
