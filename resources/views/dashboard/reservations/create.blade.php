@extends('dashboard.layouts.index')


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
        .loader {
            display: none;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 2s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

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

        .trip-hours-section {
            border-radius: 5px;
            color: #ffffff;
            font-weight: bold;
            text-transform: capitalize !important;
            padding: 5% !important;
            border: 5px;
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
            <div class="row p-4">
                <h4 class="w-50 font-weight-bold">Create Reservation</h4>
                <div class="w-50 text-right">
                    {{-- <a href="{{ route('dashboard.reservations.create') }}" type="button" class="btn btn-primary remove-border w-50">Create</a> --}}
                </div>
            </div>

            <form enctype="multipart/form-data" action="{{ route('dashboard.reservations.store') }}" id="AddForm"
                method="post">

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
                                <option value="1">
                                    Point to Point
                                </option>
                                <option selected value="2">
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
                                <option selected disabled value="0">
                                    ------
                                </option>
                                <option value="One Way">One Way</option>
                                <option value="Round">Round</option>
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



        </div>




        <div class="col mt-5 text-right mb-4">

            <button onclick="validateData()" id="createButton" type="button" class="btn btn-primary remove-border">
                Create
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
                        <option value="1">Name 1</option>
                        <option value="2">Name 2</option>
                        <option value="3">Name 3</option>
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

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places"></script>
@include('dashboard.reservations.script')
@endsection
