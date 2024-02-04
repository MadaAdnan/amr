@extends('dashboard.layouts.index')

@section('content')

@php
    $statusQuery =  request()->get('status') ;
    $query =  request()->query('query');
    $type =  request()->query('type');
    $from =  request()->query('from');
    $to =  request()->query('to');
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
                    <h4 class="w-50 font-weight-bold">Keywords Bank <br/> <p class="h6 mt-2">Download a Kewords Template Sheet <a href="{{ asset('dashboard/Keywords Template Sheet.xlsx') }}">Click Here</a></p></h4>
                    <div class="w-50 text-right">
                        @permission('keyword-actions')
                        <a href="{{ route('dashboard.keywords.export_excel', ['query' => $query, 'type' => $typeQuery, 'from' => $fromQuery, 'to' => $toQuery]) }}" type="button" class="btn btn-primary create-hover remove-border"><i class="fa fa-upload"></i> Export</a>
                            <button onclick="importExcel()" type="button" class="btn btn-primary create-hover remove-border"><i class="fa fa-download"></i> Import</button>
                            <form enctype="multipart/form-data" id="importExcelForm" style="display: none;" action="{{ route('dashboard.keywords.import_keywords') }}" method="post">
                                @csrf
                                <input id="importExcelInput" type="file" name="file" style="display: none;">
                            </form>
                        @endpermission
                    </div>
                </div>
                <div class="card-content">
                    <div class="row pl-3">
                        <div class="form-group col p-4">
                            <h6>
                                Search
                            </h6>
                            <input  value='{{ $query }}' id="titleInput" class="form-control" type="text" placeholder="Search for (keyword name ,strength, monthly volum , subject)">
                        </div>
                        <div class="form-group col p-4">
                            <h6>
                                Range
                            </h6>
                           <select class="form-control" name="filter_type" id="filter_type">
                                <option {{ $type == 'strength'?'selected':'' }} value="strength">Strength</option>
                                <option {{ $type == 'monthly_volume'?'selected':'' }} value="monthly_volume">Monthly Volume</option>
                           </select>
                        </div>
                        <div class="form-group col p-4">
                            <h6>
                                From
                            </h6>
                           <input min="0" value="{{ $from }}" type="number" name="from_filter" id="from_filter" class="form-control">
                        </div>
                        <div class="form-group col p-4">
                            <h6>
                                To
                            </h6>
                           <input min="0" value="{{ $to }}" type="number" name="to_filter" id="to_filter" class="form-control">
                        </div>
                        <div class="form-group col p-4">
                            <button onclick="filter()" class="btn btn-primary btn-sm mt-4"> <i class="fa fa-search"></i> </button>
                            <a  href="{{ route('dashboard.keywords.index') }}" class="btn btn-primary btn-sm mt-4">Clear</a>
                        </div>
                       
                        <div class="form-group col p-4 text-right">
                            {{-- <button class="btn btn-primary mt-4" onclick="filter()">Filter</button> --}}
                            @permission('keyword-actions')
                                <button onclick="openModal('create')" type="button" class="btn btn-primary create-hover remove-border mt-4">Create</button>
                            @endpermission
                        </div>
                    </div>
                    <div class="card-body">
                       
                        <!-- Table with outer spacing -->
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                <th class="text-center">Keyword Name</th>
                                <th class="text-center">Subject</th>
                                <th class="text-center">Strength</th>
                                <th class="text-center">Monthly Volume</th>
                                <th class="text-center">Usage Number For Blogs</th>
                                <th class="text-center">Usage Number For Pages</th>
                                <th class="text-center">Total</th>
                                @canany('delete-keywords|edit-keywords')
                                    <th>Action</th>
                                @endcanany

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td class="text-bold-500 text-center">{{ $item->title }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->subject }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->strength }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->monthly_volume }}</td>
                                    <td class="text-bold-500 text-center"><a target="_blank" href="">{{ $item->post_keywords()->count() }}</a> </td>
                                    <td class="text-bold-500 text-center"><a target="_blank" href="">{{ $item->pages_keywords()->count() }}</a></td>
                                    <td class="text-bold-500 text-center">{{ $item->post_keywords()->count()+ $item->pages_keywords()->count() }}</td>
                                    @canany('delete-keywords|edit-keywords')
                                        <td class="text-bold-500 text-center">
                                            @permission('delete-keywords')
                                                <button onclick="deleteKeyword({{ $item->id }})" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endpermission
                                            @permission('edit-keywords')
                                                <button onclick="editKeyword({{ json_encode($item)}})" class="btn btn-primary mt-2 btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endpermission

                                        </td>
                                    @endcanany
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

<div id="editModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="titleInput">Keyword Name</label>
                <input disabled type="text" class="form-control" id="titleEditInputModal" placeholder="Keyword Name">
            </div>
            <div class="form-group">
                <label for="titleInput">Subject</label>
                <input type="text" class="form-control" id="subjectEditInputModal" placeholder="Enter subject">
            </div>
            <div class="form-group">
                <label for="titleInput">Strength</label>
                <input type="number" min="0" max="10" class="form-control" id="strengthEditInputModal" placeholder="Enter strength">
            </div>
            <div class="form-group">
                <label for="titleInput">Monthly Volume</label>
                <input type="number" min="0" max="10" class="form-control" id="monthlyVolumeEditInputModal" placeholder="Enter Strenth">
            </div>
        </div>
        <div class="modal-footer">
          <button id="actionButton" onclick="sendEditRequest()" type="button" class="btn btn-primary create-hover">Update</button>
          {{-- <a style="display:none;" id="reloadButton" href="{{ route($route) }}" type="button" class="btn btn-secondary">Reload</a> --}}
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
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
                <label for="titleInput">Keyword Name</label>
                <input type="text" class="form-control" id="titleInputModal" placeholder="Enter Keyword Name">
            </div>
            <div class="form-group">
                <label for="titleInput">Subject</label>
                <input type="text" class="form-control" id="subjectInputModal" placeholder="Enter subject">
            </div>
            <div class="form-group">
                <label for="titleInput">Strength</label>
                <input type="number" min="0" max="10" class="form-control" id="strengthInputModal" placeholder="Enter strength">
            </div>
            <div class="form-group">
                <label for="titleInput">Monthly Volume</label>
                <input type="text" pattern="[0-9]" min="0" class="form-control" id="monthlyVolumeInputModal" placeholder="Enter monthly volume">
            </div>
        </div>
        <div class="modal-footer">
          <button id="actionButton" onclick="sendRequest()" type="button" class="btn btn-primary create-hover">Create</button>
          <a style="display:none;" id="reloadButton" href="{{ route($route) }}" type="button" class="btn btn-secondary">Reload</a>
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
          <p>Are you sure you want to delete this keyword?</p>
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
    const title_input = document.getElementById('titleInputModal');
    let requestType = 'create';
    let actionButtonText = 'Create';
    let category_id;

    function openModal(type,title = null , slug = null,id = null)
    {
        requestType = type;
        if(title&&slug)
        {
            title_input.value = title;
            category_id = id;
            $('#actionButton').empty();
            $('#actionButton').append('Update');
        }
        else
        {
            title_input.value = null;
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
        const url = '{{ route("dashboard.keywords.store") }}';
        const subjectInput = document.getElementById('subjectInputModal');
        const strengthInput = document.getElementById('strengthInputModal');
        const monthlyVolumeInput = document.getElementById('monthlyVolumeInputModal');
        console.log('value: ',strengthInput.value)
        if(strengthInput.value > 11)
        {
            Toast.fire({
                icon: 'error',
                title: "Please add a value between 1 to 10 to strength"
            })
            return;
        }

        let data = {
            'title':title_input.value,
            'subject':subjectInput.value,
            'strength':strengthInput.value,
            'monthly_volume':monthlyVolumeInput.value,
            '_token':'{{ csrf_token() }}'
        }
        $.ajax({
            url,
            data,
            type:"POST",
            success:(res)=>{
                Toast.fire({
                    icon: 'success',
                    title: "Keyword was added successfully"
                })
                title_input.value = '';
                subjectInput.value = '';
                strengthInput.value = '';
                monthlyVolumeInput.value = '';
                $('#reloadButton').css('display','block');


            },
            error:(err_res)=>{
                Toast.fire({
                    icon: 'error',
                    title: 'Please add the title and subject to submit and make sure it is unique'
                })
            }
        })
    }

    function updateRequest()
    {
        let url = '{{ route("dashboard.keywords.update",":id") }}';
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

    function addQueryParameter(key, value) {
        params.append(key, value);
    }

    var params = new URLSearchParams();

    function filter()
   {
    
    const input = document.getElementById('titleInput').value;
    const type = document.getElementById('filter_type').value;
    const from = document.getElementById('from_filter').value;
    const to = document.getElementById('to_filter').value;

    if(input)
    {
        addQueryParameter("query", input);
    }
    if(type)
    {
        addQueryParameter("type", type);
    }
    if(from)
    {
        addQueryParameter("from", from);
    }
    if(to)
    {
        addQueryParameter("to", to);
    }

    let url = '{{ route("dashboard.keywords.index") }}'
    var urlWithQueries = url + "?" + params.toString();
    window.location.href = urlWithQueries;

   }

   let input = document.getElementById('titleInput');
   input.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            filter();
        }
    });

    let current_item
    let current_edit_object_id

    function deleteKeyword(id)
    {
        current_item = id;
        $('#deleteModal').modal('show');
    }

    function confirmDelete()
    {
        let url = '{{ route("dashboard.keywords.delete",":id") }}';
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

    function closeButton()
    {
        $('#deleteModal').modal('hide');
    }

    function editKeyword(data){
       document.getElementById('titleEditInputModal').value = data.title
       document.getElementById('subjectEditInputModal').value = data.subject
       document.getElementById('strengthEditInputModal').value = data.strength
       document.getElementById('monthlyVolumeEditInputModal').value = data.monthly_volume
       current_edit_object_id = data.id;
       $('#editModal').modal('show');
    }

    function sendEditRequest()
    {
        let url = '{{ route("dashboard.keywords.update",":id") }}';
        url = url.replace(':id',current_edit_object_id)
        $.ajax({
            url:url,
            type:'POST',
            data:{
                '_token':'{{ csrf_token() }}',
                'title':document.getElementById('titleEditInputModal').value,
                'subject':document.getElementById('subjectEditInputModal').value,
                'strength':document.getElementById('strengthEditInputModal').value,
                'monthly_volume':document.getElementById('monthlyVolumeEditInputModal').value,
            },
            success:(res)=>{
                location.reload();
            },
            error:(err)=>{
                console.log('error: ',err)
            }
        })
    }

    function importExcel()
    {
        $('#importExcelInput').click();
    }

    $('#importExcelInput').change(function(){
        $('#importExcelForm').submit();
    })








</script>
@endsection

