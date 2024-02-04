@extends('dashboard.layouts.index')

@section('content')
@php
    $statusQuery =  request()->get('status') ;
    $query =  request()->query('query') ;
    $route = Route::currentRouteName();
    $nowDate = \Carbon\Carbon::now();
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
                    <h4 class="w-50 font-weight-bold">Cities for state {{ $state->name }}</h4>
                    <div class="w-50 text-right">
                        <a href="{{ route('dashboard.cities.create') }}" type="button" class="btn btn-primary remove-border w-50">Create</a>
                    </div>
                </div>
                <div class="card-content">
                    <div class="row">
                        <div class="form-group col p-4">
                            <h6>
                                Title
                            </h6>
                            <input onkeypress="handle(event)"  value='{{ $query }}' id="searchInput" class="form-control" type="text">
                        </div>
                       
                        <div class="form-group col p-4">
                            @if($statusQuery||$query)
                            <a href="{{ route($route) }}" class="btn btn-danger mt-4">Clear</a>
                            @endif
                            <button class="btn btn-primary mt-4" onclick="filter()"> <i class="fa fa-search"></i> </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th class="text-center" >Title</th>
                                <th class="text-center" >Updated at</th>
                                <th class="text-center" >Status</th>
                                <th class="text-center" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                   
                                    <td class="text-bold-500 text-center">{{ $item->title??'No Data' }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->updated_at->format('Y-m-d h:i:s A')??'No Data' }}</td>
                                   
                                    <td class="text-bold-500 text-center">{{ $item->status }}</td>
                                    <td class="text-bold-500 text-center">
                                        <a href="{{ route('dashboard.cities.edit',$item->id) }}" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></a>
                                        <a href="{{ route('dashboard.countries.companies_index',$item->id) }}" class="btn btn-primary btn-sm custom-button">
                                            <i class="fa fa-eye"></i>
                                        </a>
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
          <p>Are you sure you want to delete this blog?</p>
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
    let url = '{{ route("dashboard.cities.index") }}'

    if(!filter_input&&status_input)
    {
        url = url+'?status='+status_input
    }
    if(filter_input&&!status_input)
    {
        url = url+'?query='+filter_input
    }
    if(filter_input&&status_input)
    {
        url = url+'?query='+filter_input+'&&status='+status_input
    }

    window.location.href = url;
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
        let request = '{{ route("dashboard.blogs.delete",":id") }}';
        request = request.replace(':id',current_item_id);
        $.ajax({
            url:request,
            type:'GET',
            success:(res)=>{
                console.log('response: ',res)
                location.reload();
            },
            error:(err)=>{
                console.log('err: ',res)
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


</script>
@endsection

