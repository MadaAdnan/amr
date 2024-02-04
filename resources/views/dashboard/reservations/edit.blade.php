@extends('dashboard.layouts.index')
{{-- Calculate the continuous index --}}
@php
    $continuousIndex = ($paymentIntentLogs->currentPage() - 1) * $paymentIntentLogs->perPage() + 1;
@endphp

@section('content')
    {{-- =========================================================== --}}
    {{-- ================== Sweet Alert Section ==================== --}}
    {{-- =========================================================== --}}
    <div>
        @if (session()->has('success'))
            <script>
                swal("Great Job !!!", "{!! Session::get('success') !!}", "success", {
                    button: "OK",
                });
            </script>
        @endif
        @if (session()->has('danger'))
            <script>
                swal("Oops !!!", "{!! Session::get('danger') !!}", "error", {
                    button: "Close",
                });
            </script>
        @endif
    </div>
    {{-- ============================================== --}}
    {{-- ================== Header ==================== --}}
    {{-- ============================================== --}}

    <style>
        .custom-dropdown {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        #dropdownButton {
            /* padding: 5px 10px; */
            cursor: pointer;
        }

        #selectedValue {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
        }

        .dropdown-list {
            display: none;
            position: absolute;
            width: 100%;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            list-style: none;
            padding: 0;
            margin: 0;
            z-index: 9999;
        }

        .dropdown-list button {
            padding: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .dropdown-list button:hover {
            background-color: #f4f4f4;
        }

        .reset-button {
            border: none;
            background: none;
            padding: 0;
            margin: 0;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            cursor: pointer;
            outline: none;
        }

        .iti {
            position: relative;
            display: block;
        }

        .section-container {
            /* background: linear-gradient(to bottom, #d45458, #ffffff);
                                                        padding: 5%;
                                                        border-radius: 9px;
                                                        color: #fff;
                                                        font-weight: 300;
                                                        margin-bottom: 5%; */
        }

        .trip-hours-section {
            /* background-color: #e2e2e2; */
            border-radius: 5px;
            color: #ffffff;
            font-weight: bold;
            text-transform: capitalize !important;
            padding: 5% !important;
            border: 5px;
            /* border: 1px solid red; */
            box-shadow: 5px 5px 10px 0 rgba(0, 0, 0, 0.3);
        }

        .trip-hours-section-icon {
            color: lightgray !important;
        }

        .drop-off-location {
            margin-top: 1%;
        }
    </style>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>

        </div>
        <div class="row p-2 card">
            <div>
                @if ($reservation->reject_note)
                <div class="alert alert-danger" role="alert">
                    <p>Stripe Reject Note: {{ $reservation->reject_note }}</p>
                  </div>                  
                @endif
            </div>
            <div class="col-12 ">
                <div class="row">
                    <div class="col-md-6 p-4">
                        <h4 class="w-50 font-weight-bold">Edit Reservation #{{ $reservation->id }}</h4>
                    </div>
                    <div class="col-md-6">
                        @if ($pageType == 'edit' && isset($riskLevel))
                            @php
                                $riskClass = '';
        
                                // Check the value of $riskLevel and set the appropriate class
                                if (isset($riskLevel)) {
                                    if ($riskLevel === 'high') {
                                        $riskClass = 'btn-danger';
                                    } elseif ($riskLevel === 'normal') {
                                        $riskClass = 'btn-success';
                                    } elseif ($riskLevel === 'elevated') {
                                        $riskClass = 'btn-warning';
                                    } else {
                                        $riskClass = 'btn-warning';
                                    }
                                }
                            @endphp
        
                            <div class="row mb-2">
                                <div class="col-3">
                                    
                                </div>
                               
                                <div class="col-6 p-4 ">
                                    <input min="1" type="button" id="risk" class="form-control text-center rounded-pill text-white  {{ $riskClass }}"
                                        name="risk" inputmode="numeric" style="text-transform: uppercase; width:75%; margin-left:60%"
                                        value="{{ isset($riskLevel) ? $riskLevel : '' }} RISK" disabled>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <form enctype="multipart/form-data" action="{{ route('dashboard.reservations.update', $reservation->id) }}"
                id="AddForm" method="post">

                @csrf

                <div class="d-flex mt-4">
                    <h5 class="mr-auto">Location</h5>
                    <i class="fa fa-location ml-auto h3 icon-color"></i>
                </div>
                <hr />
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="serviceType">Service</label>
                            <select onchange="changeServiceType(event)" class="form-control" name="service_type"
                                id="service_type">
                                <option value="1"
                                    {{ isset($reservation) && $reservation->service_type == 1 ? 'selected' : '' }}>
                                    Point to Point
                                </option>
                                <option value="2"
                                    {{ isset($reservation) && $reservation->service_type == 2 ? 'selected' : '' }}>
                                    Hourly
                                </option>


                            </select>
                            <small id="service_type_note" class="form-text text-muted">Pick a service type</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="serviceType">Transfer Type</label>
                            <select onchange="changeTransferType(event)" disabled class="form-control" name="transfer_type"
                                id="transfer_type">
                                <option selected disabled value="">
                                    ------
                                </option>
                                <option value="One Way" {{ isset($reservation) && $reservation->transfer_type == 'One Way' ? 'selected' : '' }}>One Way</option>
                                <option value="Round" {{ isset($reservation) && $reservation->transfer_type == 'Round' ? 'selected' : '' }}>Round</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="section-container">
                    @include('dashboard.reservations.types.locations')
                </div>

                <div id="hourly">
                    @include('dashboard.reservations.types.hourly')
                </div>


                <div class="d-flex align-items-center mt-4">
                    <h5 class="mr-auto">Information</h5>
                    <i class="ml-auto fa fa-info ml-auto h3 icon-color"></i>
                </div>
                <hr />

                @include('dashboard.reservations.types.sharedInputs')

                @include('dashboard.reservations.types.childSeats')

                <div class="customers">
                    @include('dashboard.reservations.types.customer')
                </div>
                <div class="row  flex-row">

                </div>



        </div>




        <div class="col mt-5 text-right mb-4">

            <button onclick="validateData()" id="editButton" type="button" class="btn btn-primary remove-border">
                Edit
            </button>
        </div>
        </form>
    </div>


    </div>

    <div class="modal" id="createChildSeats">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Seat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title">Name</h5>
                    <select class="form-control select2" name="name" id="childSeatName">
                     
                    </select>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Add</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="placesModal" tabindex="-1" role="dialog" aria-labelledby="placesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="placesModalLabel">Nearby Places</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" id="modalPlacesGrid"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCY7UP_ICqDk1BwzqxxOnEVAw4Mt-uk_ik&libraries=places">
    < script src = "https://code.jquery.com/jquery-3.6.0.min.js" >
</script>

</script>


@section('scripts')
@include('dashboard.reservations.script')
@endsection
