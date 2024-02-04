@extends('dashboard.layouts.index')

@section('content')
@php
    $statusQuery =  request()->get('status') ;
    $query =  request()->query('query') ;
    $route = Route::currentRouteName();
    $id;
    $myRole = Auth::user()->roles[0]->name;

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
                    <h4 class="w-50 font-weight-bold">Trash Users</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                       

                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th class="text-center" >Name</th>
                                <th class="text-center" >Role</th>
                                <th class="text-center" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td class="text-bold-500 text-center">{{ $item->full_name??'No Data' }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->roles[0]->name??'No Data' }}</td>
                                    <td class="text-bold-500 text-center">
                                        <button onclick="changeStatus('{{ $item->id }}','{{ $item->is_deactivated }}')" class="btn btn-warning btn-sm custom-button  mb-2 mt-2"> <i class="fas fa-share"></i> </button>                                           
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


<div id="ChangeStatus" class="modal fade" id="ChangeStatus" tabindex="-1" aria-labelledby="ChangeStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ChangeStatusLabel">Return User</h5>
        </div>
        <div class="modal-body">
          <p id="changeStatusMsg">Are you sure you want to return ?</p>
        </div>
        <div class="modal-footer">
          <button onclick="closeButton()" type="button" class="btn btn-secondary" data-mdb-dismiss="modal">No</button>
          <button onclick="confirmRequest()" type="button" class="btn btn-danger">Yes</button>
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
    const status_input = document.getElementById('rolesInput').value;
    let url = '{{ route("dashboard.admins.index") }}'

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

   function changeStatus(id,is_deactivated)
   {
    current_item_id = id;
    let msg = "Are you sure you want to return the user info?"
    $('#changeStatusMsg').empty();

    $('#changeStatusMsg').append(msg);
    
    $('#ChangeStatus').modal('show');
   }

   function closeButton()
    {
        $('#ChangeStatus').modal('hide');
    }

    function confirmRequest()
    {
        let request = '{{ route("dashboard.trashes.return_user",":id") }}';
        request = request.replace(':id',current_item_id);
        $.ajax({
            url:request,
            type:'POST',
            data:{
                '_token':'{{ csrf_token() }}'
            },
            success:(res)=>{
               location.reload();
            },
            error:(err)=>{
                console.log('err: ',err)
            }
        })
    }

    function copyText() {
        // Get the text field
        var copyText = document.getElementById("new_password_input");

        // Select the text field
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        // Copy the text inside the text field
        navigator.clipboard.writeText(copyText.value);

        // Alert the copied text
        Toast.fire({
            icon: 'success',
            title: 'Password was copied'
        })
    }



</script>
@endsection

