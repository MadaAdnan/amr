@extends('dashboard.layouts.index')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
      
    </div>
    <div class="card">
        <div class="row" id="basic-table">
            <div class="col-12">
                <div class="row p-4">
                    <h4 class="w-50 font-weight-bold">Pending Categories</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                       
                        <!-- Table with outer spacing -->
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th>Name</th>
                                <th>History</th>
                                <th>Action Type</th>
                                <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                              
                                <tr>
                                    <td class="text-bold-500">{{ $item->title }}</td>
                                    <td class="text-bold-500">example.....</td>
                                    <td class="text-bold-500">Delete/Edit</td>
                                    <td class="text-bold-500 text-center">
                                        <button class="btn btn-danger btn-sm  delete-red"> Reject </button>
                                        <button onclick="openModal('edit','{{ $item->title }}','{{ $item->slug }}','{{ $item->id }}')" class="btn btn-success text-white btn-sm">Accept</button>
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
@endsection

@section('scripts')
<script>
    const title_input = document.getElementById('titleInput');
    const slug_input = document.getElementById('slugInput');
    let requestType = 'create';
    let actionButtonText = 'Create';
    let category_id;

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
                Toast.fire({
                    icon: 'error',
                    title: err_res.responseJSON.err
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
</script>
@endsection

