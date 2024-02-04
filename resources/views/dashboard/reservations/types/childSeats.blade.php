<div class="row mt-5">
    <div class="col-6 text-left d-flex align-items-center">
        <h5 class="align-middle">Change The Selected Child Seats <span id="warningLabel"></span></h5>

    </div>

    <div class="col-6 text-right mb-2">
        <button type="button" onclick="openChildSeatsModal()" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i>
        </button>
      
    </div>
    <hr />
    <div id="seatsSections">

    </div>

</div>



{{-- <div id="seatsSections">
        @if ($selectedChildSeats)
            @foreach ($selectedChildSeats as $selectedSeat)
                <div class="row mt-3">
                    <div class="col-6">
                        <label for="">Seat Name</label>
                        <select onchange="getPriceAccordingToFleet()" class="form-control select2" name="child_seats[][seats]" id="{{ $selectedSeat->idDivSeats }}">
                            <!-- Generate the options based on the selected seat -->
                            @foreach ($childSeats as $childSeat)
                                <option data-title="{{ $childSeat->title }}" data-price="{{ $childSeat->price }}" value="{{ $childSeat->id }}" {{ $childSeat->id == $selectedSeat->child_seat_id ? 'selected' : '' }}>{{ $childSeat->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="">Amount</label>
                        <select id="{{ $selectedSeat->idDivAmount }}" onchange="checkChildSeatsNumber()" class="form-control select2" name="child_seats[][amount]">
                            <!-- Generate the options based on the selected seat -->
                            @for ($i = 1; $i <= 3; $i++)
                                <option value="{{ $i }}" {{ $i == $selectedSeat->amount ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <div class="text-right">
                            <button onclick="deleteChildSeat({{ $selectedSeat->containerId }})" class="mt-2 btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div> --}}
