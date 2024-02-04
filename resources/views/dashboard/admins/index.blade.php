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
                    <h4 class="w-50 font-weight-bold">Users</h4>
                    <div class="w-50 text-right">
                        <a href="{{ route('dashboard.admins.create') }}" type="button" class="btn btn-primary remove-border w-50">Create</a>
                    </div>
                </div>
                <div class="card-content">
                    {{-- <div class="row">
                        <div class="form-group col p-4">
                            <h6>
                                Title
                            </h6>
                            <input value='{{ $query }}' id="searchInput" class="form-control" type="text">
                        </div>

                        <div class="form-group col-2 p-4">
                            <h6>
                                Roles
                            </h6>
                           <select id="rolesInput" class="form-control">
                            <option  {{ $statusQuery == ''?'selected':'' }} value="">All</option>
                            <option  {{ $statusQuery == 'Blogger'?'selected':'' }} value="Blogger">Blogger</option>
                            <option  {{ $statusQuery == 'Seo-specialist'?'selected':'' }} value="Seo-specialist">Seo-specialist</option>
                            <option  {{ $statusQuery == 'Seo-admin'?'selected':'' }} value="Seo-admin">Seo-admin</option>
                           </select>
                        </div>
                        
                        <div class="form-group col p-4">
                            @if($statusQuery||$query)
                            <a href="{{ route($route) }}" class="btn btn-danger mt-4">Clear</a>
                            @endif
                            <button class="btn btn-primary mt-4" onclick="filter()"> <i class="fa fa-search"></i> </button>
                        </div>
                    </div> --}}
                    <div class="card-body">
                        @if (\Session::has('password'))
                              <div class="alert alert-success" role="alert">
                                <h4 class="alert-heading">New Password Generated!</h4>
                                <p>Password was generated for account.  {!! \Session::get('email') !!} </p>
                                <hr>
                                <p class="mb-0">New Password:
                                    <div class="d-flex justify-content-center">
                                        <div class="input-group mb-3 w-50">
                                            <div class="input-group-prepend">
                                              <span onclick="copyText()" class="input-group-text" id="basic-addon1"> <i class="fa fa-copy h3"></i> </span>
                                            </div>
                                            <input  value=" {!! \Session::get('password') !!}" id="new_password_input" onclick="copyText()" readonly type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </p>
                              </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th class="text-center" >Name</th>
                                <th class="text-center" >Role</th>
                                <th class="text-center" >Active</th>
                                <th class="text-center" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td class="text-bold-500 text-center">{{ $item->full_name??'No Data' }} {{ $item->id == Auth::user()->id ? '(You)':'' }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->roles[0]->name??'No Data' }}</td>
                                    <td class="text-bold-500 text-center">
                                        <span class="badge {{ !$item->is_deactivated?'success':'danger' }}-badge m-2">{{ !$item->is_deactivated ? 'Active' : 'Deactivated' }}</span>
                                    </td>
                                    <td class="text-bold-500 text-center">
                                        @if ($item->roles[0]->name != 'Seo-admin'||$myRole == 'Super-admin')
                                            @if ($item->roles[0]->name != 'Blogger'&&$item->roles[0]->name != 'super_admin'&&Auth::user()->id != $item->id)
                                                <button onclick="delete_user_modal('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red mb-2 mt-2"> <i data-feather="trash" width="20"></i> </button>                                                                                           
                                            @endif
                                            @if ($item->roles[0]->name != 'super_admin'&&Auth::user()->id != $item->id)
                                                <button onclick="changeStatus('{{ $item->id }}','{{ $item->is_deactivated }}')" class="btn btn-warning btn-sm custom-button mb-2 mt-2"> <i data-feather="{{ $item->is_deactivated == 0 ? 'user-x':'user-check' }}" width="20"></i> </button>                                                  
                                            @endif                                     
                                        @endif
                                        <a href="{{ route('dashboard.admins.edit',$item->id) }}" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></a>
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


<div id="ChangeStatus" class="modal fade" tabindex="-1" aria-labelledby="ChangeStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ChangeStatusLabel">User Activation Status</h5>
        </div>
        <div class="modal-body">
          <p id="changeStatusMsg">Are you sure you want to change this account status?</p>
        </div>
        <div class="modal-footer">
          <button onclick="closeButton()" type="button" class="btn btn-secondary" data-mdb-dismiss="modal">No</button>
          <button onclick="confirmRequest()" type="button" class="btn btn-danger">Yes</button>
        </div>
      </div>
    </div>
  </div>
  
  <div id="deleteModal" class="modal fade"  tabindex="-1" aria-labelledby="ChangeStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ChangeStatusLabel">Delete User</h5>
        </div>
        <div class="modal-body">
          <p id="changeStatusMsg">Are you sure you want to delete this user?</p>
        </div>
        <div class="modal-footer">
          <button onclick="closeButton()" type="button" class="btn btn-secondary" data-mdb-dismiss="modal">No</button>
          <button onclick="delete_user()" type="button" class="btn btn-danger">Yes</button>
        </div>
      </div>
    </div>
  </div>  



@endsection

@section('scripts')
<script>
    let current_item_id = 0;
    let deleted_item = 0;

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
    let msg = "Are you sure you want to reactivate this user's account?"
    if(is_deactivated == 0)
    {
        msg = "Are you sure you want to deactivate this user's account?"
    }
    $('#changeStatusMsg').empty();
    $('#changeStatusMsg').append(msg);
    
    $('#ChangeStatus').modal('show');
   }

   function closeButton()
    {
        $('#ChangeStatus').modal('hide');
        $('#deleteModal').modal('hide');
    }

    function confirmRequest()
    {
        let request = '{{ route("dashboard.admins.change_status_admin",":id") }}';
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
                console.log('err: ',res)
            }
        })
    }

    function copyText() 
    {
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

    function delete_user_modal(id)
    {
        deleted_item = id;
        $('#deleteModal').modal('show');
    }

    function delete_user()
    {
        let request = '{{ route("dashboard.admins.delete",":id") }}';
        request = request.replace(':id',deleted_item);
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
                console.log('err: ',res)
            }
        })
    }




</script>
@endsection

