@extends('dashboard.layouts.index')

@section('content')
@php
    $statusQuery =  request()->get('status') ;
    $query =  request()->query('query') ;
    $route = Route::currentRouteName();
    $nowDate = \Carbon\Carbon::now();
    $status = [
        'Active'=>[
            'name'=>'Active',
            'color'=>'#58f000'
        ],
        'Disabled'=>[
            'name'=>'Disabled',
            'color'=>'#FF0000'
        ]
    ];
   

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
                    <h4 class="w-50 font-weight-bold">States</h4>
                    <div class="w-50 text-right">
                        <button onclick="showCreateModal()" type="button" class="btn btn-primary remove-border w-50">Add</button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="row">
                        <div class="form-group col p-4">
                            <h6>
                                Name
                            </h6>
                            <input onkeypress="handle(event)"  value='{{ $query }}' id="searchInput" class="form-control" type="text">
                        </div>
                        <div class="form-group col p-4">
                            <h6>
                                Status
                            </h6>
                            <select id="statusFilterInput" class="form-select">
                                <option value="">Select</option>
                                <option {{ $statusQuery == 'Active' ? 'selected':'' }} value="Active">Active</option>
                                <option {{ $statusQuery == 'Disabled' ? 'selected':'' }} value="Disabled">Disabled</option>
                              </select>
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
                                <th class="text-center" >Name</th>
                                <th class="text-center">Country</th>
                                <th class="text-center" >Status</th>
                                <th class="text-center" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                   
                                    <td class="text-bold-500 text-center">{{ $item->name??'No Data' }}</td>
                                    <td class="text-bold-500 text-center"> {{ $item->countries->name }} </td>
                                    <td class="text-bold-500 text-center"><span style="background-color: {{ $status[$item->status]['color'] }}; !important" class="badge badge-success {{ $item->status == 'publish'?'success-badge':'danger-badge' }} text-uppercase">{{$item->status == 'publish'&& $item->date&&$item->date->gt($nowDate)?$status['Scheduled']['name']:$status[$item->status]['name'] }}</span></td>
                                    <td class="text-bold-500 text-center">
                                        <button onclick="openEditModal('{{ $item }}','{{ $item->countries->name }}')" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></button>
                                        <button disabled class="btn btn-primary btn-sm custom-button"><i data-feather="eye" width="20"></i></button>
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


<div id="createModal" class="modal fade" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createModalLabel">Add a State</h5>
        </div>
        <div class="modal-body">
            <div class="form-group mb-3">
                <label for="">Country</label>
                <select onchange="getStateAccordingToState(event)" id="selectCountry" class="form-control">
                    <option value="" selected disabled>Select</option>
                    @foreach ($countries as $item)
                        <option data-country-name="{{ $item->name }}" value="{{ $item->id}}">{{ $item->name }}</option>
                    @endforeach
                </select>
              </div>
              <div class="form-group mb-3">
                <label for="">State</label>
                <select disabled id="selectState" class="form-control">
                    
                </select>
              </div>
        </div>
        <div class="modal-footer">
          <button onclick="closeButton()" type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
          <button id="addButton" onclick="createButton()" type="button" class="btn btn-success">Add</button>
        </div>
      </div>
    </div>
  </div>  

  <div id="editModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit State</h5>
        </div>
        <div class="modal-body">
            <div class="form-group mb-3">
                <label for="" class="mb-2">Name</label>
                <input disabled id="editNameInput" type="text" class="form-control" placeholder="please add state name" aria-label="please add state name" aria-describedby="basic-addon2">
            </div>
            <div class="form-group mb-3">
                <label for="" class="mb-2">Country</label>
                <input disabled id="editCountryInput" type="text" class="form-control" placeholder="please add country name" aria-label="please add country name" aria-describedby="basic-addon2">
            </div>
            <div class="form-group">
                <label for="" class="mb-2">Status</label>
                <select id="editStatusInput" class="form-select" aria-label="Default select example">
                    <option value="Active"> Active </option>
                    <option value="Disabled">Disabled</option>
                  </select>
            </div>
        </div>
        <div class="modal-footer">
          <button onclick="closeButton()" type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
          <button onclick="updateButton()" type="button" class="btn btn-success">Update</button>
        </div>
      </div>
    </div>
  </div>  



@endsection

@section('scripts')
<script>
    let current_item_id = 0;

    const nameInput = document.getElementById('titleInput');
    const statusFilterInput = document.getElementById('statusFilterInput');
    const searchInput = document.getElementById('searchInput');
    const editNameInput = document.getElementById('editNameInput');
    const editCountryInput = document.getElementById('editCountryInput');
    const editStatusInput = document.getElementById('editStatusInput');
    const countrySelect = document.getElementById('selectCountry');
    const addButton = document.getElementById('addButton');
    const selectState = document.getElementById('selectState')
    const selectStateSelect2 = $('#selectState').select2();
    const createdStates = @json($getAllStates);
    addButton.disabled = true;

    
   function filter()
   {
    const filter_input = searchInput.value;
    const status_input = statusFilterInput.value;
    let url = '{{ route("dashboard.states.index") }}'

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

   function showCreateModal()
   {
    $('#createModal').modal('show');
   }

   function closeButton()
    {
        $('#createModal').modal('hide');
        $('#editModal').modal('hide');
    }

    function createButton()
    {
        let request = '{{ route("dashboard.states.store") }}';
       if(!valdation())return;
        $.ajax({
            url:request,
            type:'POST',
            data:{
                country_id:countrySelect.value,
                name:selectState.value,
                '_token':'{{ csrf_token() }}'
            },
            success:(res)=>{
                console.log('response: ',res)
                location.reload();
            },
            error:(err)=>{
                console.log(err)

                Swal.fire({
                title: 'Some thing went wrong',
                icon: 'error',
                confirmButtonText: 'Ok'
            })
            }
        })
    }

    function updateButton()
    {
        let request = '{{ route("dashboard.states.update",":id") }}';
        request = request.replace(':id',current_item_id);

        $.ajax({
            url:request,
            type:'POST',
            data:{
                status:editStatusInput.value,
                '_token':'{{ csrf_token() }}'
            },
            success:(res)=>{
                console.log('response: ',res)
                location.reload();
            },
            error:(err)=>{
                console.log(err)

                Swal.fire({
                title: 'Some thing went wrong',
                icon: 'error',
                confirmButtonText: 'Ok'
            })
            }
        })
    }

    function openEditModal(item , countryName)
    {
        const obj = JSON.parse(item)
        current_item_id = obj.id;
        editNameInput.value = obj.name;
       editCountryInput.value = countryName;
        editStatusInput.value = obj.status;
        $('#editModal').modal('show');
    }

    function valdation()
    {
        if(selectState.value == '')
        {
            Swal.fire({
                title: 'Please select state',
                icon: 'error',
                confirmButtonText: 'Ok'
            })
            return false;
        }

        return true;
    }

     function handle(e){
        if(e.keyCode === 13){
            e.preventDefault(); // Ensure it is only this code that runs
            filter();
        }
    }

    $('#selectCountry').select2();
    $('#selectState').select2();


    function getStateAccordingToState(event){
        const selectedOption = countrySelect.options[countrySelect.selectedIndex];
        const countrName = selectedOption.getAttribute('data-country-name');
        addButton.disabled = true;
        let request = '{{ route("dashboard.states.getStateAccordingToCountry",":name") }}';
        request = request.replace(":name",countrName);

        $.ajax({
            url:request,
            type:'GET',
            success:(res)=>{
                selectStateSelect2.empty();
                selectState.disabled = true;
                addButton.disabled = false;

                if(res.data.length == 0)
                {
                    Swal.fire({
                        title: 'No data found',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    addButton.disabled = true;
                    return;
                }
                selectStateSelect2.empty();
                selectState.disabled = false;
                const option = new Option('Choose', '');
                selectStateSelect2.append(option);
               res.data.forEach((value,index) =>{
                    const option = new Option(value, value);
                    selectStateSelect2.append(option);
               });
               selectStateSelect2.trigger('change');
               disableOption()

            },
            error:(err)=>{
                Swal.fire({
                        title: 'Something went wrong',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                })
            }
        })
    }

    function disableOption() {
        createdStates.forEach(element => {
            selectStateSelect2.find(`option[value="${element}"]`).prop('disabled', true);
        });
        selectStateSelect2.trigger('change');
    }



</script>
@endsection

