@extends('dashboard.layouts.index')

@section('content')
    @php
        $statusQuery = request()->get('status');
        $query = request()->query('query');
        $return_date = request()->query('return_date');
        $pick_up_date = request()->query('pick_up_date');
        $route = Route::currentRouteName();
        $nowDate = \Carbon\Carbon::now();
        $id;
       
        
    @endphp
    <style>
        .table > :not(caption) > * > *{
            padding:1.5rem 0.2rem;
        }
        </style>

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
                        <div class="row p-4">
                            <div class="form-group col-6 p-4">

                                <h4 class=" font-weight-bold">Coupons </h4>
                            </div>

                            <div class=" form-group col-6 ">
                                <div class="  float-right mt-3 ml-2">
                                    <a href="{{ route('dashboard.coupons.create') }}" type="button"
                                        class="btn btn-secondary remove-border " >Create</a>
                                </div>
                            </div>
                        </div>



                    </div>
                @else
                    <div class="row p-4">
                        <h4 class="w-50 font-weight-bold"> Archive</h4>

                    </div>
                @endif
                <div class="card-content">

                    <div class="row">
                        <div class="form-group col p-4">
                            <h6>
                                Title
                            </h6>
                            <input onkeypress="handle(event)" value='{{ $query }}' id="searchInput"
                                class="form-control" type="text">



                        </div>
                       
                        <div class="form-group col p-4">
                            @if ($statusQuery || $query)
                                <a href="{{ route($route) }}" class="btn btn-danger mt-4">Clear</a>
                            @endif
                            <button class="btn btn-primary mt-4" onclick="filter(false)"> <i class="fa fa-search"></i>
                            </button>
                           
                        </div>
                       

                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead>

                                    <tr>
                                        <th class="text-center ">Coupon name</th>
                                        <th class="text-center ">Code</th>
                                        <th class="text-center ">Usage Limit</th>
                                        <th class="text-center ">Amount</th>
                                        <th class="text-center " >Start Date</th>
                                        <th class="text-center " >End Date</th>
                                        <th class="text-center ">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($coupons as $index => $item)
                                        <tr>
                                            <td class="text-bold-500 text-center" >{{ $item->coupon_name }}</td>
                                            <td class="text-bold-500 text-center" >{{ $item->coupon_code }}</td>
                                            <td class="text-bold-500 text-center" >{{ $item->usage_limit }}</td>
                                            <td class="text-bold-500 text-center" >{{ $item->discount_type == 'Percentage'?'%'.$item->percentage_discount:'$'.$item->percentage_discount }}</td>
                                            <td class="text-bold-500 text-center" >{{ $item->active_from->format('d-m-Y') }}</td>
                                            <td class="text-bold-500 text-center" >{{ $item->active_to->format('d-m-Y') }}</td>                                            <td class="text-bold-500">

                                                @if ($pageType === 'index')
                                                    {{-- <button  data-id={{$item->id}} class="btn btn-danger btn-sm custom-button soft-delete-input delete-red mb-2 mt-2" data-url="{{ route('dashboard.coupons.softDelete',  $item->id ) }}"> <i data-feather="trash" width="20"></i> </button> --}}
                                                    {{-- <a data-id={{ $item->id }}
                                                        class="btn btn-danger btn-sm custom-button soft-delete-input delete-red mb-2 mt-2"
                                                        href="{{ route('dashboard.coupons.softDelete', $item->id) }}">
                                                        <i data-feather="trash" width="20"></i> </a> --}}
                                                        <button onclick="delete_user_modal('{{ $item->id }}')"
                                                            class="btn btn-danger btn-sm custom-button delete-red mb-2 mt-2"> <i
                                                                data-feather="trash" width="20"></i> </button>

                                                    {{-- <button onclick="deleteItem('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red mb-2 mt-2"> <i data-feather="trash" width="20"></i> </button> --}}
                                                    <a href="{{ route('dashboard.coupons.edit', $item->id) }}"
                                                        class="btn btn-dark text-white btn-sm custom-button "><i
                                                            data-feather="edit" width="20"></i></a>


                                                    
                                                @endif
                                                @if ($pageType === 'trashed')
                                                    {{-- <button  data-id={{$item->id}} class="btn btn-danger btn-sm custom-button soft-delete-input delete-red mb-2 mt-2" data-url="{{ route('dashboard.coupons.softDelete',  $item->id ) }}"> <i data-feather="trash" width="20"></i> </button> --}}
                                                    <a data-id={{ $item->id }}
                                                        class="btn btn-success btn-sm custom-button soft-delete-input mb-2 mt-2"
                                                        href="{{ route('dashboard.coupons.softDeleteRestore', $item->id) }}">
                                                        <i data-feather="refresh-ccw" width="20"></i> </a>
                                                @endif
                                                {{-- <button onclick="deleteItem('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red mb-2 mt-2"> <i data-feather="trash" width="20"></i> </button> --}}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>


                        </div>
                        <div class="float-right">
                            {{ $coupons->links() }}
                        </div>
                    </div>

                </div>


                <div id="deleteModal" class="modal fade" tabindex="-1" aria-labelledby="ChangeStatusLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ChangeStatusLabel">Delete Coupon</h5>
                            </div>
                            <div class="modal-body">
                                <p id="changeStatusMsg">Are you sure you want to delete this coupon?</p>
                            </div>
                            <div class="modal-footer">
                                <button onclick="closeButton()" type="button" class="btn btn-secondary"
                                    data-mdb-dismiss="modal">No</button>
                                <button onclick="delete_user()" type="button" class="btn btn-danger">Yes</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection

            @section('scripts')
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.all.min.js"></script>

                <script>
                    let current_item_id = 0;

                    function filter(is_export = false) {
                        const filter_input = document.getElementById('searchInput').value;
                      
                        let url = '{{ route('dashboard.coupons.index') }}'

                        


                        if (filter_input ) {
                            url = url + '?query=' + filter_input
                        }
                       

                        window.location.href = url;
                    }

                    function deleteItem(id) {
                        current_item_id = id;
                        $('#deleteModal').modal('show');
                    }

                    function delete_user_modal(id) {
                        deleted_item = id;
                        $('#deleteModal').modal('show');
                    }

                    function delete_user() {
                        let request = '{{ route('dashboard.coupons.softDelete', ':id') }}';
                        request = request.replace(':id', deleted_item);
                        $.ajax({
                            url: request,
                            type: 'POST',
                            data: {
                                '_token': '{{ csrf_token() }}'
                            },
                            success: (res) => {
                                location.reload();
                            },
                            error: (err) => {
                                console.log('err: ', err)
                            }
                        })
                    }


                    function closeButton() {
                        $('#ChangeStatus').modal('hide');
                        $('#deleteModal').modal('hide');
                    }



                    function handle(e) {
                        if (e.keyCode === 13) {
                            e.preventDefault(); // Ensure it is only this code that runs
                            filter();
                        }
                    }
                </script>

                <script></script>
            @endsection
