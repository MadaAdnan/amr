@extends('dashboard.layouts.index')

@section('content')
@php
    $statusQuery =  request()->get('status') ;
    $query =  request()->query('query') ;
    $route = Route::currentRouteName();
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
                    <h4 class="w-50 font-weight-bold">Tags</h4>
                    <div class="w-50 text-right">
                        <button onclick="openModal('create')" type="button" class="btn btn-primary create-hover remove-border">Create</button>
                    </div>
                </div>
                <div class="card-content">
                    
                    <div class="card-body">
                       
                        <!-- Table with outer spacing -->
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th class="text-center">Name</th>
                                <th class="text-center">Slug</th>
                                <th class="text-center">Blogs</th>
                                @canany('delete-tags|edit-tags')
                                <th class="text-center">Action</th>
                                </tr>
                                @endcanany
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                              
                                <tr>
                                    <td class="text-bold-500 text-center">{{ $item->title }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->slug }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->posts()->count() }}</td>
                                    @canany('delete-tags|edit-tags')
                                        <td class="text-bold-500 text-center">
                                            @permission('delete-tags')
                                                <button onclick="deleteItem('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red"> <i data-feather="trash" width="20"></i> </button>
                                            @endpermission
                                            @permission('edit-tags')
                                                <button onclick="openModal('edit','{{ $item->title }}','{{ $item->slug }}','{{ $item->id }}')" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></button>
                                            @endpermission
                                        </td>
                                    @endcanany
                                </tr>
                                @endforeach
                            
                            </tbody>
                        </table>
                        {{$data->links()}}
                    </div>

    </div>
    
</div>

<div id="createModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="titleInput">Title</label>
                <input type="text" class="form-control" id="titleInput" placeholder="Enter title">
            </div>
            
            <div class="form-group">
                <label for="slugInput">Slug</label>
                <input type="text" class="form-control" id="slugInput" placeholder="Enter slug">
            </div>
        </div>
        <div class="modal-footer">
          <button id="actionButton" onclick="sendRequest()" type="button" class="btn btn-primary create-hover">Create</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
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
          <p>Are you sure you want to delete this category?</p>
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
    const title_input = document.getElementById('titleInput');
    const slug_input = document.getElementById('slugInput');
    let requestType = 'create';
    let actionButtonText = 'Create';
    let category_id;
    let current_item


    function openModal(type,title = null , slug = null,id = null)
    {
        requestType = type;
        if(title&&slug)
        {
            title_input.value = title;
            slug_input.value = slug;
            // slug_input.disabled = true;
            category_id = id;
            $('#actionButton').empty();
            $('#actionButton').append('Update');
        }
        else
        {
            title_input.value = null;
            slug_input.value = null;
            slug_input.disabled = false;
            category_id = null;

            $('#actionButton').empty();
            $('#actionButton').append('Create');
        }
       $('#createModal').modal('toggle');
    }

    function sendRequest()
    {
        let slugInput = document.getElementById('slugInput');
        if(requestType == 'create') createRequest()
        else updateRequest();
    }

    function createRequest()
    {
        const url = '{{ route("dashboard.tags.store") }}';
        let data = {
            'title':document.getElementById('titleInput').value,
            'slug':slug_input.value,
            '_token':'{{ csrf_token() }}'
        }
        $.ajax({
            url,
            data,
            type:"POST",
            success:(res)=>{
                location.reload();
            },
            error:(err_res)=>{
                console.log(err_res)
                Toast.fire({
                    icon: 'error',
                    title: err_res.responseJSON.err??err_res.responseJSON.msg
                })
            }
        })
    }

    function updateRequest()
    {
        let url = '{{ route("dashboard.tags.update",":id") }}';
        url = url.replace(":id",category_id)
        let data = {
            'title':title_input.value,
            'slug':slug_input.value,
            '_token':'{{ csrf_token() }}'
        }
        $.ajax({
            url,
            data,
            type:"POST",
            success:(res)=>{
                location.reload();
            },
            error:(err_res)=>{
                console.log(err_res)
                Toast.fire({
                    icon: 'error',
                    title: err_res.responseJSON.err??err_res.responseJSON.msg
                })
            }
        })
    }

    function deleteItem(id)
    {
        current_item = id;
        $('#deleteModal').modal('show');
    }
    function closeButton()
    {
        $('#deleteModal').modal('hide');
    }
    function confirmDelete()
    {
        let url = '{{ route("dashboard.tags.delete",":id") }}';
        url = url.replace(':id',current_item)
        $.ajax({
            url:url,
            type:'DELETE',
            data:{
                '_token':'{{ csrf_token() }}'
            },
            success:(res)=>{
                location.reload();
            },
            error:(err)=>{
                console.log('error: ',err)
            }
        })
    }
    
</script>
@endsection

