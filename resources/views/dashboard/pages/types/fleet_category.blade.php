<div class="text-right">
    <a href="{{ route('dashboard.fleetCategories.create') }}" class="btn btn-primary">Create</a>
</div>
<div>
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>
          
        </div>
            <div class="row" id="basic-table">
                <div class="col-12">
                    
                    <div class="card-content">
                        
                        <div class="card-body">
                           
                            <!-- Table with outer spacing -->
                            <div class="table-responsive">
                                <table class="table">
                                <thead>
                                    <tr>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Fleets</th>
                                    <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selectFleetCategory as $item)
                                  
                                    <tr>
                                        <td class="text-bold-500">{{ $item->title }}</td>
                                        <td class="text-bold-500">{{ $item->slug }}</td>
                                        <td class="text-bold-500">{{ $item->fleets()->count() }}</td>
                                        <td class="text-bold-500 text-center">
                                            <button onclick="deleteItemModal('{{ $item->id }}')" class="btn btn-danger btn-sm custom-button delete-red"> <i data-feather="trash" width="20"></i> </button>
                                            <a href="{{ route('dashboard.fleetCategories.edit',$item->id) }}" class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit" width="20"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                
                                </tbody>
                                </table>
                        </div>


                        <div id="deleteModal" class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                                </div>
                                <div class="modal-body">
                                  <p>Are you sure you want to delete this fleet category?</p>
                                  <h6>
                                    Choose the category that fleet will assign to ?   
                                  </h6>
                                  <select id="selectFleet" class="form-select" aria-label="Default select example">

                                  </select>                                  
                                </div>
                                <div class="modal-footer">
                                  <button onclick="closeButton()" type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
                                  <button onclick="confirmDelete()" type="button" class="btn btn-danger">Delete</button>
                                </div>
                              </div>
                            </div>
                          </div>  
                        
                        



                        @section('scripts')
                            <script>
                                let current_item = 0;
                                var allFleetsCategory = {!! json_encode($selectFleetCategory) !!};
                                console.log(allFleetsCategory)
                                function deleteItemModal(id)
                                {
                                    current_item = id;
                                    getTheUnselectedFleetsCategory(id);

                                    $('#deleteModal').modal('show');
                                }

                                function confirmDelete()
                                {
                                    let request = '{{ route("dashboard.fleetCategories.delete",":id") }}';
                                    request = request.replace(":id",current_item);
                                    const fleet_category = document.getElementById('selectFleet').value;
                                    if(fleet_category == '')
                                    {
                                        Toast.fire({
                                            icon: 'error',
                                            title: 'Please choose category'
                                        })
                                        return;
                                    }

                                    $.ajax({
                                        url:request,
                                        type:'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        data:{
                                            'fleet_category':document.getElementById('selectFleet').value
                                        },
                                        success:(res)=>{
                                            window.location.reload();
                                        },
                                        error:(err)=>{}
                                    })
                                }

                                function getTheUnselectedFleetsCategory(id)
                                {
                                    $('#selectFleet').empty();
                                    allFleetsCategory.forEach(element => {
                                        let option = `<option value="${element.id}" >${element.title}</option>`;
                                        if(id != element.id)
                                        {
                                            $('#selectFleet').append(option);
                                        }
                                    });
                                }

                                function closeButton()
                                {
                                    $('#deleteModal').modal('hide');
                                }

                            </script>
                        @endsection
</div>