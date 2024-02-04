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
                    <h4 class="w-50 font-weight-bold">Comments</h4>
                    <div class="w-50 text-right">
                    </div>
                </div>
                <div class="card-content">
                    <div class="row">
                        <div class="form-group col p-4">
                            <h6>
                                Title
                            </h6>
                            <input value='{{ $query }}' id="searchInput" class="form-control" type="text">
                        </div>
                        <div class="form-group col p-4">
                            <h6>
                                Status
                            </h6>
                           <select id="statusInput" class="form-control">
                            <option  {{ $statusQuery == ''?'selected':'' }} value="">All</option>
                            <option  {{ $statusQuery == 'published'?'selected':'' }} value="published">Published</option>
                            <option  {{ $statusQuery == 'Pending'?'selected':'' }} value="Pending">Pending</option>
                            <option  {{ $statusQuery == 'deleted'?'selected':'' }} value="deleted">Deleted</option>
                           </select>
                        </div>
                        <div class="form-group col p-4">
                            <button class="btn btn-primary mt-4" onclick="filter()">Filter</button>
                            @if($statusQuery||$query)
                            <a href="{{ route($route) }}" class="btn btn-danger mt-4">Clear</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                       
                        <!-- Table with outer spacing -->
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th>Text</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Blog</th>
                                <th>Reply</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                              
                                <tr>
                                    <td class="text-bold-500 max-char">{{ $item->text }}</td>
                                    <td class="text-bold-500">{{ $item->users ? $item->users->first_name .' '.$item->users->last_name : $item->name }}</td>
                                    <td class="text-bold-500">{{ $item->users ? $item->users->email : $item->email }}</td>
                                    <td class="text-bold-500 max-char">{{ $item->posts->title }}</td>
                                    <td class="text-bold-500">
                                        {{ $item->comment_id ? 'Yes' : 'No' }}
                                    </td>
                                    <td class="text-bold-500">  <span class="badge badge-{{ $item->status == 'Pending'?'info':'primary' }}">{{ $item->status }}</span></td>
                                    <td class="text-bold-500 text-center">
                                        <button onclick="deleteCategory('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red mt-2"> <i data-feather="trash" width="20"></i> </button>
                                        <a href="{{ route('dashboard.comments.show',$item->id) }}" class="btn btn-dark text-white btn-sm custom-button mt-2"><i data-feather="eye" width="20"></i></a>
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
                <label for="titleInput">Seo Description</label>
                <input type="text" class="form-control" id="seoDescriptionInput" placeholder="Enter Description">
            </div>
            <div class="form-group">
                <label for="titleInput">Seo Keywords</label>
                <input id="seo_keywords" type="text" class="form-control" id="seoKeywordInput" placeholder="Enter keywords">
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
            category_id = id;
            $('#actionButton').empty();
            $('#actionButton').append('Update');
        }
        else
        {
            title_input.value = null;
            slug_input.value = null;
            category_id = null;

            $('#actionButton').empty();
            $('#actionButton').append('Create');
        }
       $('#createModal').modal('toggle');
    }

    function sendRequest()
    {
        if(requestType == 'create') createRequest()
        else updateRequest();
    }

    function createRequest()
    {
        const url = '{{ route("dashboard.categories.store") }}';
        let data = {
            'title':document.getElementById('titleInput').value,
            'seo_description':document.getElementById('seoDescriptionInput').value,
            'seo_keywords':document.getElementById('seo_keywords').value,
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
        let url = '{{ route("dashboard.categories.update",":id") }}';
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

    function deleteCategory(id)
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
        let url = '{{ route("dashboard.categories.delete",":id") }}';
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

    function filter()
   {
        const filter_input = document.getElementById('searchInput').value;
        const status_input = document.getElementById('statusInput').value;
        
        let url = '{{ route("dashboard.comments.index") }}'

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


    
</script>
@endsection

