@extends('dashboard.layouts.index')

@section('content')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>

        </div>
        <div class="card">

            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="row p-2 pl-4 ">
                        <div class="form-group col-6 p-4">

                            <h4 class=" font-weight-bold">Customer Details </h4>
                        </div>

                        <div class=" form-group col-6 ">
                            <div class="info ">
                                <div>

                                    <div>
                                        <span style="display: inline-block; font-weight: bold;">Passenger Name:</span>
                                        <span
                                            style="display: inline-block;">{{ $reservation->users->fullname ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span style="display: inline-block; font-weight: bold;">Phone:</span>
                                        <span
                                            style="display: inline-block;">{{ isset($reservation->users->phone) ? $reservation->users->phone : 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span style="display: inline-block; font-weight: bold;">E-mail:</span>
                                        <span
                                            style="display: inline-block;">{{ $reservation->users->email ? $reservation->users->email : 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span style="display: inline-block;font-weight: bold;">Service Type:</span>
                                        <span style="display: inline-block;">
                                            @if (isset($reservation->service_type) && $reservation->service_type == 1)
                                                {{ ' Point To Point ' }}
                                            @elseif(isset($reservation->service_type) && $reservation->service_type == 2)
                                                {{ ' Hourly ' }}
                                            @else
                                                {{ ' Airport Transfer ' }}
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <span style="border: 1px;display: inline-block; font-weight: bold;">Pickup Date &
                                            time:</span>
                                        <span style="border: 1px;display: inline-block; ">
                                            {{ \Carbon\Carbon::parse($reservation->pick_up_date)->format('D, m-d-Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($reservation->pick_up_time)->format('h:i A') }}</span>
                                    </div>
                                    @if ($reservation->distance)
                                        <div>
                                            <span style="display: inline-block;font-weight: bold;">Distance:</span>
                                            @if (isset($reservation->transfer_type) && $reservation->transfer_type == 'Round')
                                                <span style="display: inline-block;">{{ $reservation->mile }} Miles</span>
                                            @else
                                                <span style="display: inline-block;">{{ $reservation->distance }}
                                                    Miles</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if (isset($reservation->service_type) && $reservation->service_type == 2)
                                        <div>
                                            <span style="display: inline-block; font-weight: bold;">Duration:</span>
                                            <span style="display: inline-block;">{{ $reservation->duration }}
                                                {{ ' Hours ' }}

                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <span style="display: inline-block; font-weight: bold;">Trip Status:</span>
                                        <span style="display: inline-block;">{{ $reservation->status }}</span>

                                    </div>
                                    @if (isset($reservation->childSeats) && $reservation->childSeats->count() > 0)
                                        <div>
                                            <div>
                                                <span style="display: inline-block; font-weight: bold;">Child Seats:</span>
                                                @foreach ($reservation->childSeats as $key => $seat)
                                                    <span style="display: inline-block;">{{ $seat->title }}@if ($key + 1 < $reservation->childSeats->count())
                                                        ,@else.
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if (isset($reservation->tip) && $reservation->tip != 0)
                                        <div>
                                            <span style="display: inline-block; font-weight: bold;">Tip:</span>
                                            <span style="display: inline-block;">${{ $reservation->tip }}</span>

                                        </div>
                                    @endif
                                </div>
                             
                            </div>
                            {{-- <div class="  float-right mt-3">
                                <a href="{{ route('dashboard.reservations.showSoftDelete') }}" type="button"
                                    class="btn btn-primary remove-border ">Archive</a>
                            </div> --}}

                        </div>
                    </div>



                </div>
            </div>
        </div>


        <div class="card">

            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="row p-2 pl-4 ">
                        <div class="form-group col-6 p-4">

                            <h4 class=" font-weight-bold">Route </h4>
                        </div>

                        <div class=" form-group col-6 ">

                                <div class="info">
                                    <div>
                                        <div>
                                            <span style="display: inline-block; font-weight: bold;">From:</span>
                                            <span
                                                style="display: inline-block;">{{ $reservation->pick_up_location }}</span>
                                        </div>
                                        @if (isset($reservation->drop_off_location))
                                            <div>
                                                <span style="display: inline-block; font-weight: bold;">To:</span>
                                                <span
                                                    style="display: inline-block;">{{ $reservation->drop_off_location }}</span>
                                            </div>
                                        @endif

                                    </div>


                                </div>
                            {{-- <div class="  float-right mt-3">
                                <a href="{{ route('dashboard.reservations.showSoftDelete') }}" type="button"
                                    class="btn btn-primary remove-border ">Archive</a>
                            </div> --}}

                        </div>
                    </div>



                </div>
            </div>
        </div>
        <div class="card">

            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="row p-2 pl-4 ">
                        <div class="form-group col-6 p-4">

                            <h4 class="font-weight-bold">Chauffeur & Vehicle</h4>
                        </div>

                        <div class=" form-group col-6 ">

                            @if (isset($reservation->fleets))
                            <div class="info">
                                @if (isset($driver->id))
                                    <div>
                                        <div>
                                            <span style="display: inline-block;font-weight: bold;">Name:</span>
                                            <span class="capitalize"
                                                style="display: inline-block;">{{ $driver->fullname }}</span>
    
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <span style="display: inline-block;font-weight: bold;">Phone
                                                Number::</span>
                                            <span style="display: inline-block;">{{ $driver->phone }}</span>
    
                                        </div>
                                    </div>
                                @endif
    
    
    
                                <div>
                                    <span style="display: inline-block;font-weight: bold;">Fleet
                                        Category:</span>
                                    <span style="display: inline-block;">{{ $reservation->fleets->title }}</span>
                                </div>
                                @if (isset($reservation->vehicle))
                                    <div>
                                        <span style="display: inline-block;font-weight: bold;">Vehicle
                                            Type:</span>
                                        <span
                                            style="display: inline-block;">{{ $reservation->vehicle ? $reservation->vehicle->title : 'N/A' }}</span>
                                    </div>
                                @endif
                                <div>
                                    <span style="display: inline-block;font-weight: bold;">Vehicle
                                        Capacity:</span>
                                    <span
                                        style="display: inline-block;">{{ isset($reservation->vehicle->passengers) ? $reservation->vehicle->passengers : 'N/A' }}
                                        Passengers</span>
                                </div>
    
    
    
                            </div>
                        @endif
                            {{-- <div class="  float-right mt-3">
                                <a href="{{ route('dashboard.reservations.showSoftDelete') }}" type="button"
                                    class="btn btn-primary remove-border ">Archive</a>
                            </div> --}}

                        </div>
                    </div>



                </div>
            </div>
        </div>
     
    @endsection
