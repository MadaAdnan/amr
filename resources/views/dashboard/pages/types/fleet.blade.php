<div class="text-right">
    <a href="{{ route('dashboard.fleets.create') }}" class="btn btn-primary">Create</a>
    <button onclick="showContentModal( '{{ $content['content'] ??''}}',
        '{{ $content['fleetSeoHeader']??'' }}',)" type="button" class="btn btn-secondary remove-border">Add Content</button>

</div>
<!-- Content Modal -->
<div class="modal" id="contentModal" tabindex="-1" role="dialog" aria-labelledby="contentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contentModalLabel">Create Content for Fleet page</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="fleet_h1" style="text-align:left !important;">H1</label>

                <input id="fleet_h1" name="fleet_h1" type="text" class="form-control" placeholder="H1">
            </div>
            <div class="modal-body">
                <label for="fleetContent" style="text-align:left !important;">Fleet Content</label>
                <textarea id="fleetContent" name="fleetContent" class="form-control" placeholder="content"></textarea>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="createContentButton()">Create</button>
            </div>
        </div>
    </div>
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
                                    <th>Category</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fleets as $item)
                                    <tr>
                                        <td class="text-bold-500">{{ $item->title }}</td>
                                        <td class="text-bold-500">{{ $item->slug }}</td>
                                        <td class="text-bold-500">{{ $item->category->title }}</td>
                                        <td class="text-bold-500 text-center">
                                            <button onclick="deleteItemModal('{{ $item->id }}')"
                                                class="btn btn-danger btn-sm custom-button delete-red mb-2"><i
                                                    data-feather="trash" width="20"></i></button>
                                            <a href="{{ route('dashboard.fleets.edit', $item->id) }}"
                                                class="btn btn-dark text-white btn-sm custom-button mb-2"><i
                                                    data-feather="edit" width="20"></i></a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>


                    <div id="deleteModal" class="modal fade" id="deleteModal" tabindex="-1"
                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this fleet?</p>

                                </div>
                                <div class="modal-footer">
                                    <button onclick="closeButton()" type="button" class="btn btn-secondary"
                                        data-mdb-dismiss="modal">Cancel</button>
                                    <button onclick="confirmDelete()" type="button"
                                        class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="float-right">
                        {{ $fleets->links() }}
                    </div>

                    @section('scripts')
                        <script>
                            let current_item = 0;
                            const FleetHeader = $('#fleet_h1'); //countries
                            const fleet_content = $('#fleetContent');

                            var allFleetsCategory = {!! json_encode($selectFleetCategory) !!};

                            function deleteItemModal(id) {
                                current_item = id;
                                $('#deleteModal').modal('show');
                            }

                            function confirmDelete() {
                                let request = '{{ route('dashboard.fleets.delete', ':id') }}';
                                request = request.replace(":id", current_item);

                                $.ajax({
                                    url: request,
                                    type: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    success: (res) => {
                                        window.location.reload();
                                    },
                                    error: (err) => {}
                                })
                            }

                            function closeButton() {
                                $('#deleteModal').modal('hide');
                            }


                           
                            function showContentModal(content, Seo_h1) {
                                $('#fleetContent').val(content);
                                $('#fleet_h1').val(Seo_h1);
                                $('#contentModal').modal('show');
                            }


                            function createContentButton() {

                                let fleetContent = fleet_content.val();
                                let header = FleetHeader.val();
                               
                                let request = "{{ route('dashboard.pages.createFleetContent') }}";
                                $.ajax({
                                    url: request,
                                    type: 'POST',
                                    data: {
                                      
                                        content: fleetContent,
                                        fleetSeoHeader: header,

                                        '_token': '{{ csrf_token() }}',
                                    },
                                    success: (res) => {
                                        console.log('response: ', res);
                                        location.reload();
                                    },
                                    error: (err) => {
                                        console.log(err);

                                        Swal.fire({
                                            title: 'Something went wrong',
                                            icon: 'error',
                                            confirmButtonText: 'Ok'
                                        });
                                    }
                                });
                            }
                        </script>
                    @endsection
                </div>
