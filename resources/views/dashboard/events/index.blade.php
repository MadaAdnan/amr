@extends('dashboard.layouts.index')

@section('content')
@php
    $statusQuery =  request()->get('status') ;
    $query =  request()->query('query') ;
    $startDateQuery =  request()->query('startDate') ;
    $endDateQuery =  request()->query('endDate') ;
    $route = Route::currentRouteName();
    $nowDate = \Carbon\Carbon::now();
    $status = [
        'Active'=>[
            'name'=>'Active',
            'color'=>'#58f000'
        ],
        'Disabled'=>[
            'name'=>'Unactive',
            'color'=>"#fe3738"
        ]
    ]
   

@endphp
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
      
    </div>
    <div class="card">
        <div class="row" id="basic-table">
            <div class="col-12">
                <div class="row p-4">
                    <h4 class="w-50 font-weight-bold">Events</h4>
                    <div class="w-50 text-right">
                        <a href="{{ route('dashboard.events.create') }}" type="button" class="btn btn-primary remove-border w-50">Create</a>
                    </div>
                </div>
                <div class="card-content">
                    {{-- <div class="row">
                        <div class="form-group col-4 p-4">
                            <h6>
                                Search
                            </h6>
                            <input onkeypress="handle(event)"  value='{{ $query }}' id="searchInput" class="form-control" type="text" placeholder="Search by address , name , city name">
                        </div>
                        <div class="form-group col-4 p-4">
                            <h6>
                                Status
                            </h6>
                            <select name="status" id="status" class="form-control">
                                <option {{ $statusQuery == 'Active'?'selected':'' }} value="Active">Active</option>
                                <option {{ $statusQuery == 'Disabled'?'selected':'' }} value="Disabled">Disabled</option>
                            </select>
                        </div>
                        <div class="form-group col p-4">
                            @if($statusQuery||$query||$startDateQuery||$endDateQuery)
                            <a href="{{ route($route) }}" class="btn btn-danger mt-4">Clear</a>
                            @endif
                            <button class="btn btn-primary mt-4" onclick="filter()"> <i class="fa fa-search"></i> </button>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="form-group col-6">
                            <label for="">Start Date</label>
                            <input onchange="setDateFilter()" value="{{ $startDateQuery }}" id="startDate" type="date" class="form-control">
                        </div>
                       <div class="form-group col-6">
                            <label for="">End Date</label>
                            <input disabled value="{{ $endDateQuery }}" id="endDate" type="date" class="form-control">
                       </div>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th class="text-center" >Name</th>
                                <th class="text-center col-3" >Date</th>
                                <th class="text-center" >City</th>
                                <th class="text-center" >Value</th>
                                <th class="text-center" >Address</th>
                                <th class="text-center" >Status</th>
                                <th class="text-center" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                   
                                    <td class="text-bold-500 text-center">{{ $item->name??'No Data' }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->start_date->format('d-m-Y')}} / {{ $item->endless == 1 ? '~' : $item->end_date->format('d-m-Y') }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->cities->title  }}</td>
                                    <td class="text-bold-500 text-center">
                                        @if ($item->discount_type == 'Price')
                                            ${{ number_format($item->price, 2, '.', ',')  }}
                                            @else
                                            %{{$item->price}}
                                        @endif
                                    </td>
                                    <td class="text-bold-500 text-center">{{  $item->address  }}</td>
                                   
                                    <td class="text-bold-500 text-center"><span style="background-color: {{ $status[$item->status]['color'] }}; !important" class="badge badge-success {{ $item->status == 'Active'?'success-badge':'danger-badge' }} text-uppercase">{{$status[$item->status]['name'] }}</span></td>
                                    <td class="text-bold-500">
                                        <button onclick="deleteItem('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red mb-2 mt-2"> <i data-feather="trash" width="20"></i> </button>
                                        <a href="{{ route('dashboard.events.edit',$item->id) }}" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            
                            </tbody>
                            </table>
                           
                    </div>
                    <div class="float-right">
                        {{$data->links()}}
                    </div>
    </div>
    
</div>


<div id="deleteModal" class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this event?</p>
        </div>
        <div class="modal-footer">
          <button onclick="closeButton()" type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
          <button onclick="confirmDelete()" type="button" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>  


@endsection

@section('scripts')
<script>
    let current_item_id = 0;

   function filter()
   {
    const filter_input = document.getElementById('searchInput').value;
    const status_input =null;
    let url = '{{ route("dashboard.events.index") }}'

    let currentURL = new URL(url);

    let filter_inputs = [
        {
            'query':'status',
            'value':document.getElementById('status').value
        },
        {
            'query':'query',
            'value':document.getElementById('searchInput').value
        },
        {
            'query':'endDate',
            'value':document.getElementById('endDate').value
        },
        {
            'query':'startDate',
            'value':document.getElementById('startDate').value
        },
        
    ];

    for(let i = 0; i < filter_inputs.length; i++)
    {
        let item = filter_inputs[i];
        if(item.value == ''||!item.value)
        {
            currentURL.searchParams.delete(item.query)
        }
        else
        {
            currentURL.searchParams.set(item.query, item.value);
        }
    };
    window.location.href = currentURL;

   }

   function deleteItem(id)
   {
    current_item_id = id;
    $('#deleteModal').modal('show');
   }

   function closeButton()
    {
        $('#deleteModal').modal('hide');
    }

    function confirmDelete()
    {
        let request = '{{ route("dashboard.events.delete",":id") }}';
        request = request.replace(':id',current_item_id);
        $.ajax({
            url:request,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type:'DELETE',
            success:(res)=>{
                location.reload();
            },
            error:(err)=>{
                console.log(err)
            }
        })
    }

     function handle(e){
        if(e.keyCode === 13){
            e.preventDefault(); // Ensure it is only this code that runs
            filter();
        }
    }

    function setDateFilter()
    {
        const endDate = document.getElementById('endDate');
        endDate.disabled = false;
        endDate.min = document.getElementById('startDate').value;
        endDate.value = "";
    }


</script>
@endsection

