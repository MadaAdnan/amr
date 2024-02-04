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
                    <h4 class="w-50 font-weight-bold">URL Redirection</h4>
                </div>
                <div class="card-content">
                    
                    <div class="row d-flex justify-content-center align-items-center">
                        
                        <div class="form-group col-5">
                          <label for="old_url">Url</label>
                          <input type="text" id="old_url" class="form-control" placeholder="Enter a URL to redirect from">
                        </div>

                        <div class="form-group col-1 text-center">
                            <button class="btn btn-primary mt-3"><i data-feather="repeat" width="20"></i></button>
                        </div>

                        <div class="form-group col-5">
                          <label for="new_url">Redirected URL</label>
                          <input type="text" id="new_url" class="form-control" placeholder="Enter a URL to redirect to">
                        </div>
                    </div>
                    <div class="form-group col text-right pl-2 pr-2 mr-2">
                      <button onclick="checkSlug()" class="btn btn-primary mt-1">Add</button>
                    </div>
                      <hr/>

                      <div class="p-3">
                        <div class="form-group">
                          <label for="searchInput">Search</label>
                          <input type="text" value="{{ $query }}" class="form-control" id="searchInput" aria-describedby="searchForData" placeholder="Enter the url">
                          <small id="emailHelp" class="form-text text-muted">Add the full url to get proper resualt ex:https://lavishride.com/xxxx.</small>
                        </div>
                        <div class="text-right">
                            <button onclick="search()" type="button" class="btn btn-primary">Search</button>
                            @if ($query)
                                <a href="{{ route("dashboard.mapLinking.index") }}" class="btn btn-danger">Clear</a>
                            @endif
                        </div>
                      </div>
                      

                    <div class="card-body">
                       
                        <!-- Table with outer spacing -->
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th>Url</th>
                                <th>Redirected URL</th>
                                <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                              
                                <tr>
                                    <td class="text-bold-500"><a href="{{ $item->old_url }}" target="_blank">{{ $item->old_url }}</a></td>
                                    <td class="text-bold-500"><a href="{{ $item->new_url }}" target="_blank">{{ $item->new_url }}</a></td>
                                    <td class="text-bold-500 text-center">
                                        <button onclick="deleteItem('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red"> <i data-feather="trash" width="20"></i> </button>
                                        {{-- <button onclick="openModal('edit','{{ $item->old_url }}','{{ $item->new_url }}','{{ $item->id }}','{{ $item->is_active }}')" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></button> --}}
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
                <label for="titleInput">Url</label>
                <input type="text" class="form-control" id="broken_url" placeholder="Enter a URL to redirect from">
            </div>

            <div class="form-group">
                <label for="titleInput">Redirected URL</label>
                <input type="text" class="form-control" id="new_destination" placeholder="Enter a URL to redirect to">
            </div>
        </div>
        <div class="modal-footer">
          <button id="actionButton" onclick="checkSlug()" type="button" class="btn btn-primary create-hover">Create</button>
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
          <p>Are you sure you want to delete this url?</p>
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
    const newUrlInput = document.getElementById('new_url');
    const oldUrlInput = document.getElementById('old_url');
    const newUrlInputModal = document.getElementById('broken_url');
    const oldUrlInputModal = document.getElementById('new_destination');

    let requestType = 'create';
    let actionButtonText = 'Create';
    let current_item
    let current_id = 0;


    function openModal(type,old_url = null , new_url = null,id = null,is_active = null)
    {
        requestType = type;
        if(old_url&&new_url)
        {
            newUrlInputModal.value = old_url;
            oldUrlInputModal.value = new_url;
            current_id = id


            $('#actionButton').empty();
            $('#actionButton').append('Update');
            $('.modal-title').empty();
            $('.modal-title').append('Edit');

        }
        else
        {
            newUrlInputModal.value = null;
            oldUrlInputModal.value = null;


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
            checkSlug(current_id);
        }
    }

    function createRequest()
    {
        const url = '{{ route("dashboard.mapLinking.store") }}';
        let data = {
            'old_url':document.getElementById('old_url').value,
            'new_url':document.getElementById('new_url').value,
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
                    title: err_res.responseJSON.message
                })
            }
        })
    }

    function updateRequest()
    {
        let url = '{{ route("dashboard.mapLinking.update",":id") }}';
        url = url.replace(":id",current_id);
        let data = {
            'old_url':newUrlInputModal.value,
            'new_url':oldUrlInputModal.value,
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
                    title: err_res.responseJSON.message
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
        let url = '{{ route("dashboard.mapLinking.delete",":id") }}';
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

    function checkSlug()
            {
                console.log(current_id)
                let request = '{{ route("dashboard.mapLinking.checkUrl") }}';
                if(current_id) request = request+'?id='+current_id
                $.ajax({
                    url:request,
                    type:'POST',
                    data:{
                        url:document.getElementById('old_url').value,
                        '_token':'{{csrf_token()}}'
                    },
                    success:(res)=>{
                        let check = res.data.is_available?true:false;

                        if(check)
                        {
                            if(current_id)
                            {
                                return updateRequest()
                            }
                            else
                            {
                                return createRequest()
                            }
                            
                        }
                        else
                        {
                            Toast.fire({
                                icon: 'error',
                                title: 'Before proceeding, kindly verify that the URL does not already exist in our system.'
                            })
                        }
                       return;
                    },
                    error:(err)=>{
                       return false
                    }
                });
            }
    const isValidUrl = (url) => {
        const urlPattern = /^(?:https?:\/\/)?(?:www\.)?([a-zA-Z0-9-]+\.[a-zA-Z]{2,})(?:\/[^\s]*)?$/;
        return urlPattern.test(url);
    };

    function search()
    {
       const search = document.getElementById('searchInput').value;
       const request = '{{ route("dashboard.mapLinking.index") }}'+'?query='+search;
       window.location.href = request;
    }








    
</script>
@endsection

