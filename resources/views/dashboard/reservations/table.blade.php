@extends('dashboard.layouts.index')

@section('content')
    @php
        $statusQuery = request()->get('status');
        $query = request()->query('query');
        $res_id = request()->query('resID');
        $company_id = request()->query('companyID');
        $first_name = request()->query('firstName');
        $last_name = request()->query('lastName');
        $driversQuery = request()->query('driverID');
        $hideFailedQuery = request()->query('hideFailed');
        
        $startDateQuery = request()->query('startDate');
        $endDateQuery = request()->query('endDate');
        $route = Route::currentRouteName();
        $nowDate = \Carbon\Carbon::now();
        $excel_url = 'dashboard.reservations.export';
        $id;
        $status = [
            'pending' => [
                'name' => 'pending',
                'color' => '#E69138',
            ],
            'accepted' => [
                'name' => 'accepted',
                'color' => '#F1C232',
            ],
            'assigned' => [
                'name' => 'assigned',
                'color' => '#6AA84F',
            ],
            'on the way' => [
                'name' => 'on the way',
                'color' => '#38761D',
            ],
            'passenger on board' => [
                'name' => 'Passenger On Board',
                'color' => '#3D85C6',
            ],
            'arrived at the pickup location' => [
                'name' => 'arrived at the pickup location',
                'color' => '#45818E',
            ],
            'completed' => [
                'name' => 'completed',
                'color' => '#351C75',
            ],
            'canceled' => [
                'name' => 'canceled',
                'color' => '#808080',
            ],
            'trip began' => [
                'name' => 'trip began',
                'color' => '#42f5e9',
            ],
            'late canceled' => [
                'name' => 'Late canceled',
                'color' => '#741B47',
            ],
            'pending refund' => [
                'name' => 'Pending refund',
                'color' => '#42f5e9',
            ],
            'canceled' => [
                // Define the canceled status here
                'name' => 'Canceled',
                'color' => '#808080',
            ],
            'late canceled' => [
                // Define the canceled status here
                'name' => 'late canceled',
                'color' => '#741B47',
            ],
            'no show' => [
                // Define the canceled status here
                'name' => 'no show',
                'color' => '#660000',
            ],
            'draft' => [
                // Define the canceled status here
                'name' => 'draft',
                'color' => '#783F04',
            ],
            'failed'=>[
                'name' => 'Failed',
                'color' => '#CC0000'
            ]
        ];
        
        $service_type = [
            1 => [
                'name' => 'point to point',
                'color' => '#58f000',
            ],
            2 => [
                'name' => 'Hourly',
                'color' => '#42f5e9',
            ],
        ];
        
    @endphp

    <style>
        .tablex> :not(caption)>*>* {
            padding: 6pt;
            background-color: var(--bs-table-bg);
            background-image: linear-gradient(var(--bs-table-accent-bg), var(--bs-table-accent-bg));
            border-bottom-width: 1px;
        }

        .chart-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .small-text {
            font-size: 14px;
            /* Adjust the font size as needed */
        }

        .custom-border {
            border: 1px solid #ccc;
            /* Add a border with a color of your choice */
            border-radius: 5px;
            /* Add rounded corners to the border (optional) */
            padding: 10px;
            /* Add some padding to the fieldset (optional) */
            position: relative;
            /* Required for absolute positioning of the title */
            box-shadow: 0.5px 1px;

        }

        .custom-border::before {
            content: "Filter Options";
            /* Your title text here */
            display: block;
            position: absolute;
            top: -20px;
            /* Adjust the vertical position of the title */
            left: 10px;
            /* Adjust the horizontal position of the title */
            background-color: white;
            /* Match the background color to the page background */
            padding: 0 5px;
            /* Add padding to the title (optional) */
            font-size: 12px;
            /* Adjust the font size of the title */
        }

        .input-container {
            width: 100%;
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
        }


        .input-container input {
            width: 100px;
            margin-right: 5px;
        }


        /* Style the custom select container */
        .custom-select {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        /* Hide the default select arrow */


        /* Add your custom arrow using a pseudo-element */
        .custom-select::after {
            content: '\25BC';
            /* Downward-pointing arrow */
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            pointer-events: none;
            /* Make sure the arrow doesn't interfere with the select box */
        }
    </style>
   
    @if ($pageType === 'index')
           @endif
    {{-- ============================================== --}}
    {{-- ================== Header ==================== --}}
    {{-- ============================================== --}}


    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>

        </div>
        <div class="card">

            <div class="row" id="basic-table">
                @if ($pageType === 'index')
                    <div class="col-12">
                        <div class="row p-2 pl-4 ">
                            <div class="form-group col-6 p-4">

                                <h4 class=" font-weight-bold">Reservations </h4>
                            </div>

                            <div class=" form-group col-6 ">
                                <div class="  float-right mt-3 ml-2 ">
                                    <a href="{{ route('dashboard.reservations.create') }}" type="button"
                                        class="btn btn-secondary remove-border ">Create</a>
                                </div>

                            </div>
                        </div>



                    </div>
                @else
                    <div class="row p-4">
                        <h4 class="w-50 font-weight-bold">Reservations Archive</h4>

                    </div>
                @endif

                {{-- ======================Filter=================== --}}
                {{-- ================Lujain Samdi=================== --}}
                {{-- =============================================== --}}
                <div class="card-content  ">


                    <div class="container ">
                        <div class="row p-4 d-flex">
                            <fieldset class="col-12 custom-border">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">

                                            <h6>ID</h6>
                                            <input onkeypress="handle(event)" value="{{ $res_id }}" id="resID"
                                                class="form-control" type="text" name="res_id">
                                        </div>
                                        <div class="col-2">

                                            <h6>Company ID</h6>
                                            <input onkeypress="handle(event)" value="{{ $company_id }}" id="companyID"
                                                class="form-control" type="text" name="company_id">
                                        </div>
                                        <div class="col-2">
                                            <h6>First Name</h6>
                                            <input onkeypress="handle(event)" value="{{ $first_name }}"
                                                class="form-control" type="text" name="first_name" id="firstName">
                                        </div>
                                        <div class="col-2">
                                            <h6>Last Name</h6>
                                            <input onkeypress="handle(event)" value="{{ $last_name }}"
                                                class="form-control" type="text" name="last_name" id="lastName">
                                        </div>
                                        @role('Super-admin')
                                            <div class="col-2">
                                                <h6>Driver</h6>
                                                <div class="custom-select">
                                                    <select name="driver" id="driverID" class="form-control">
                                                        <option value=''>All</option>
                                                        @if (isset($drivers) && count($drivers) > 0)
                                                            @foreach ($drivers as $item)
                                                                <option {{ $driversQuery == $item->id ? 'selected' : '' }}
                                                                    value='{{ $item->id }}'>{{ $item->first_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        @endrole
                                        <div class="form-group col-2 ">
                                            <h6>Status</h6>
                                            <div class="custom-select">
                                            <select name="status" id="status" class="form-control ">
                                                <option {{ $statusQuery == '' ? 'selected' : '' }} value="">All
                                                </option>
                                                <option {{ $statusQuery == 'pending' ? 'selected' : '' }} value="pending">
                                                    Pending
                                                </option>
                                                <option {{ $statusQuery == 'accepted' ? 'selected' : '' }} value="accepted">
                                                    Accepted
                                                </option>
                                                <option {{ $statusQuery == 'assigned' ? 'selected' : '' }}
                                                    value="assigned">Assigned
                                                </option>
                                                <option {{ $statusQuery == 'on the way' ? 'selected' : '' }}
                                                    value="on the way">On The
                                                    way
                                                </option>
                                                <option {{ $statusQuery == 'passenger on board' ? 'selected' : '' }}
                                                    value="passenger on board">Passenger On Board
                                                </option>
                                                <option {{ $statusQuery == 'completed' ? 'selected' : '' }}
                                                    value="completed">
                                                    Completed</option>
                                                <option {{ $statusQuery == 'arrived at the pickup location' ? 'selected' : '' }}
                                                    value="arrived at the pickup location">arrived at the pickup location</option>
                                                <option {{ $statusQuery == 'canceled' ? 'selected' : '' }}
                                                    value="canceled">Canceled
                                                </option>
                                                <option {{ $statusQuery == 'late canceled' ? 'selected' : '' }}
                                                    value="late canceled">Late Canceled
                                                </option>
                                                <option {{ $statusQuery == 'no show' ? 'selected' : '' }}
                                                    value="no show">No Show
                                                    
                                                </option>
                                                <option {{ $statusQuery == 'failed' ? 'selected' : '' }}
                                                    value="failed">Failed
                                                    
                                                </option>
                                            </select>
                                        </div>
                                        </div>
                                        <div class="col-2">
                                            <h6>Start Date</h6>
                                            <input onchange="setDateFilter()" value="{{ $startDateQuery }}" id="startDate"
                                                name="startDate" type="date" class="form-control">
                                        </div>
                                        <div class="col-2">
                                            <h6>End Date</h6>
                                            <input disabled value="{{ $endDateQuery }}" id="endDate" name="endDate"
                                                type="date" class="form-control">
                                        </div>
                                        <div class="col-8 justify-content-end d-flex">
                                            <div class="text-right">
                                                @if ($statusQuery || $query || $endDateQuery || $startDateQuery || $first_name || $last_name || $driversQuery)
                                                        <a href="{{ route($route) }}" class="btn btn-danger mt-4">Clear</a>
                                                @endif
                                                @if ($pageType === 'index')
                                                    <button type="button" id="excel"
                                                        onclick="filter('{{ route($excel_url) }}')"
                                                        class="btn btn-success remove-border mt-4"><i class="fa fa-file"
                                                            aria-hidden="true"></i></button>
                                                @endif
                                                @if ($hideFailedQuery)
                                                    <button class="btn btn-secondary mt-4" onclick="filter('{{ route($route) }}')">Show Failed</button>
                                                @else
                                                <button class="btn btn-secondary mt-4" onclick="filter('{{ route($route) }}',true)">Hide Failed</button>
                                                @endif
                                                <button class="btn btn-primary mt-4" onclick="filter('{{ route($route) }}')">Filter</button>
                                            </div>
                                        </div>
                                        </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table tablex table-hover">
                                <thead>

                                    <tr>
                                        <th class="text-center">Booking #</th>
                                        {{-- <th class="text-center">Pick up location</th>
                                        <th class="text-center ">Drop off location</th> --}}
                                        <th class="text-center ">Service Type</th>
                                        <th class="text-center w-25">Date & Time</th>
                                        <th class="text-center ">Fleets Category </th>
                                        <th class="text-center ">Customer</th>
                                        <th class="text-center ">Total price </th>
                                        <th class="text-center ">Created At </th>



                                        <th class="text-center ">Status</th>
                                        @role('Super-admin')
                                            <th class="text-center">Action</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                    @foreach ($reservations as $index => $item)
                                        <tr>

                                            <td class="text-bold-500 text-center">{{ $item->id }}</td>
                                            {{-- <td class="text-bold-500 text-center">{{ $item->pick_up_location }}</td>
                                        <td class="text-bold-500 text-center">{{ $item->drop_off_location }}</td> --}}



                                            <td class="text-bold-500 text-center">

                                                <span
                                                    style="background-color: {{ $service_type[$item->service_type]['color'] }}; !important"
                                                    class="badge badge-success {{ $item->service_type == 'one way' ? 'success-badge' : 'danger-badge' }} text-uppercase">
                                                    {{ $item->service_type == 'point to point' && $item->date && $item->date->gt($nowDate) ? $service_type['one way']['name'] : $service_type[$item->service_type]['name'] }}</span>
                                            </td>
                                            <td class="text-bold-500 text-center">
                                                {{ $item->pick_up_date->format('d-m-Y') . ' ' . $item->pick_up_time }}</td>
                                            <td class="text-bold-500 text-center">
                                                {{ $item->fleets ? $item->fleets->title : null }}</td>

                                            <td class="text-bold-500 text-center">
                                                {{ isset($item->users) ? $item->users->first_name : '' }}</td>

                                            <td class="text-bold-500 text-center">{{ $item->price_with_tip }}</td>
                                            <td class="text-bold-500 text-center">{{ $item->created_at }}</td>


                                            <td class="text-bold-500 text-center">
                                                <span
                                                    style="background-color: {{ $status[$item->status]['color'] }} !important;"
                                                    class="badge badge-success text-uppercase"
                                                    >
                                                    {{ $item->status == 'accepted' && $item->date && $item->date->gt($nowDate) ? $status['accepted']['name'] : $status[$item->status]['name']  }}
                                                </span>
                                            </td>
                                            <td class="text-bold-500">
                                                @role('Super-admin')
                                                    @if ($pageType === 'index')
                                                        {{-- <a data-id={{ $item->id }}
                                                    class="btn btn-danger btn-sm custom-button soft-delete-input delete-red mb-2 mt-2"
                                                    href="{{ route('dashboard.reservations.softDelete', $item->id) }}">
                                                    <i data-feather="trash" width="20"></i> </a> --}}

                                                        <a href="{{ route('dashboard.reservations.edit', $item->id) }}"
                                                            class="btn btn-dark text-white btn-sm custom-button "><i
                                                                data-feather="edit" width="20"></i></a>
                                                    @endif
                                                    @if ($pageType === 'trashed')
                                                        <a data-id={{ $item->id }}
                                                            class="btn btn-success btn-sm custom-button soft-delete-input mb-2 mt-2"
                                                            href="{{ route('dashboard.reservations.softDeleteRestore', $item->id) }}">
                                                            <i data-feather="refresh-ccw" width="20"></i> </a>
                                                    @endif
                                                @endrole
                                                {{-- <a href="{{ route('dashboard.reservations.show', $item->id) }}"
                                                    class="btn btn-warning text-white btn-sm custom-button "><i
                                                        data-feather="eye" width="20"></i></a> --}}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>
                        <div class="float-right">
                            {{ $reservations->links() }}
                        </div>
                    </div>

                </div>


                <div id="deleteModal" class="modal fade" id="deleteModal" tabindex="-1"
                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this blog?</p>
                            </div>
                            <div class="modal-footer">
                                <button onclick="closeButton()" type="button" class="btn btn-secondary"
                                    data-mdb-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection

            @section('scripts')
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.all.min.js"></script>



                <script>
                    let current_item_id = 0;
                    let currentRoute = '{{ route($route) }}';

                    function filter(url , hideFailInputs = false) {

                        const filter_input = document.getElementById('resID').value;
                        const company_input = document.getElementById('companyID').value;

                        const first_name = document.getElementById('firstName').value;
                        const last_name = document.getElementById('lastName').value;
                        const driver_id = document.getElementById('driverID').value;
                        const status_input = null;

                        let currentURL = new URL(url);

                        let filter_inputs = [{
                                'query': 'status',
                                'value': document.getElementById('status').value
                            },
                            {
                                'query': 'resID',
                                'value': document.getElementById('resID').value
                            },
                            {
                                'query': 'companyID',
                                'value': document.getElementById('companyID').value
                            },

                            {
                                'query': 'firstName',
                                'value': document.getElementById('firstName').value
                            },
                            {
                                'query': 'lastName',
                                'value': document.getElementById('lastName').value
                            },
                            {
                                'query': 'endDate',
                                'value': document.getElementById('endDate').value
                            },
                            {
                                'query': 'startDate',
                                'value': document.getElementById('startDate').value
                            }, 
                            {
                                'query': 'driverID',
                                'value': driver_id
                            },
                            {
                                'query': 'hideFailed',
                                'value': hideFailInputs ?? ''
                            }

                        ];

                        for (let i = 0; i < filter_inputs.length; i++) {
                            let item = filter_inputs[i];
                            if (item.value == '' || !item.value) {
                                currentURL.searchParams.delete(item.query)
                            } else {
                                currentURL.searchParams.set(item.query, item.value);
                            }
                        };

                        window.location.href = currentURL;

                    }

                    function deleteItem(id) {
                        current_item_id = id;
                        $('#deleteModal').modal('show');
                    }

                    function closeButton() {
                        $('#deleteModal').modal('hide');
                    }

                    function confirmDelete() {
                        let request = '{{ route('dashboard.events.delete', ':id') }}';
                        request = request.replace(':id', current_item_id);
                        $.ajax({
                            url: request,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            type: 'DELETE',
                            success: (res) => {
                                location.reload();
                            },
                            error: (err) => {
                                console.log(err)
                            }
                        })
                    }

                    function handle(e) {
                        if (e.keyCode === 13) {
                            e.preventDefault(); // Ensure it is only this code that runs
                            filter(currentRoute);
                        }
                    }

                    function setDateFilter() {

                        const endDate = document.getElementById('endDate');
                        const startDate = document.getElementById('startDate');
                        if (!startDate.value) {

                            endDate.disabled = true;
                            endDate.min = document.getElementById('startDate').value;
                            endDate.value = document.getElementById('startDate').value;
                        } else {
                            endDate.disabled = false;
                            endDate.min = document.getElementById('startDate').value;
                            endDate.value = document.getElementById('startDate').value;
                        }
                    }
                </script>
                @if ($pageType === 'index')
                    <script>
                        const reservations = [];

                        // Create an object to count the occurrences of each service type
                        const transferTypeCounts = {};
                        reservations.forEach(reservation => {
                            const transferType = reservation.transfer_type;
                            transferTypeCounts[transferType] = (transferTypeCounts[transferType] || 0) + 1;
                        });

                        const transferTypeLabels = Object.keys(transferTypeCounts);
                        const transferTypeData = Object.values(transferTypeCounts);

                        const ctx2 = document.getElementById('transferTypesChart').getContext('2d');
                        new Chart(ctx2, {
                            type: 'doughnut',
                            data: {
                                labels: transferTypeLabels,
                                datasets: [{
                                    data: transferTypeData,
                                    backgroundColor: [

                                        'rgba(139, 0, 0, 1)',
                                        'rgba(0, 77, 128, 1)',

                                    ],
                                }],
                            },
                        });
                    </script>
                @endif
            @endsection
