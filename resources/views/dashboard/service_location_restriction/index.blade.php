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
                    <h4 class="w-50 font-weight-bold">Location rules</h4>
                    <div class="w-50 text-right">
                        <a href="{{ route('dashboard.serviceLocationRestrictions.create') }}" type="button" class="btn btn-primary create-hover remove-border">Create</a>
                    </div>
                </div>
                <div class="card-content">
                    
                    <div class="card-body">
                       
                        <!-- Table with outer spacing -->
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th>id</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                              
                                <tr>
                                    <td class="text-bold-500">{{ $item->id }}</td>
                                    <td class="text-bold-500">{{ $item->address }}</td>
                                    <td class="text-bold-500">{{ $item->status }}</td>
                                    <td class="text-bold-500 text-center">
                                        <button onclick="deleteItem('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red"> <i data-feather="trash" width="20"></i> </button>
                                        <a href="{{ route('dashboard.serviceLocationRestrictions.edit',$item->id) }}" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            
                            </tbody>
                            </table>
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
                <input type="text" class="form-control" id="titleInput" placeholder="Enter keyword title">
            </div>
            <div class="form-group">
                <label for="titleInput">Seo Title</label>
                <input type="text" class="form-control" id="seoTitleInput" placeholder="Enter keyword title">
            </div>
            <div class="form-group">
                <label for="titleInput">Seo Description</label>
                <input type="text" class="form-control" id="seoDescriptionInput" placeholder="Enter keyword description">
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
          <p>Are you sure you want to delete this item?</p>
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
    const seo_keywords_input = document.getElementById('seo_keywords');
    const seoDescriptionInput = document.getElementById('seoDescriptionInput');
    const seoTitleInput = document.getElementById('seoTitleInput');

    let requestType = 'create';
    let actionButtonText = 'Create';
    let category_id;
    let current_item


    function openModal(type,item = null)
    {
        const obj = JSON.parse(item);


        requestType = type;
        if(obj)
        {
            title_input.value = obj.title;
            slug_input.value = obj.slug;
            seo_keywords_input.value = obj.seo_keyphrase;
            seoTitleInput.value = obj.seo_title;
            seoDescriptionInput.value = obj.seo_description;
            category_id = obj.id;

            $('#actionButton').empty();
            $('#actionButton').append('Update');
            $('.modal-title').empty();
            $('.modal-title').append('Edit');

        }
        else
        {
            title_input.value = null;
            slug_input.value = null;
            seo_keywords_input.value = null;
            seoTitleInput.value = null;
            seoDescriptionInput.value = null;

            category_id = null;

            $('#actionButton').empty();
            $('#actionButton').append('Create');
            $('.modal-title').empty();
            $('.modal-title').append('Create');

        }
       $('#createModal').modal('toggle');
    }

    function sendRequest()
    {
        
        if(requestType == 'create')
        {
            createRequest();
        }
        else
        {
            updateRequest();
        }
    }

    function createRequest()
    {
        const url = '{{ route("dashboard.categories.store") }}';
        let data = {
            'title':document.getElementById('titleInput').value,
            'seo_title':document.getElementById('seoTitleInput').value,
            'seo_description':document.getElementById('seoDescriptionInput').value,
            'seo_keyphrase':document.getElementById('seo_keywords').value,
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
            'seo_keyphrase':seo_keywords_input.value,
            'seo_title':seoTitleInput.value,
            'seo_description':seoDescriptionInput.value,
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
        let url = '{{ route("dashboard.serviceLocationRestrictions.delete",":id") }}';
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

