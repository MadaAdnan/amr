<div class="text-right">
    <a href="{{ route('dashboard.services.create') }}" class="btn btn-primary">Create</a>
</div>
<hr />
<div class="row" id="basic-table">
    <div class="col-12">

        <div class="card-content">
            <div class="row">

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">Title</th>
                                <th class="text-center">Orphan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="sortable-table" data-url="{{ route('dashboard.services.sort') }}">
                            @foreach ($services as $item)
                                <tr class="sortable-row"  data-id="{{ $item->id }}">

                                    <td class="text-bold-500 text-center">{{ $item->title ?? 'No Data' }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->is_orphan ? 'Yes' : 'No' }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->status }}</td>
                                    <td class="text-bold-500 text-center">
                                        @permission('delete-blogs')
                                            <button onclick="deleteServices('{{ $item->id }}')"
                                                class="btn btn-danger btn-sm custom-button delete-red mb-2 mt-2"> <i
                                                    data-feather="trash" width="20"></i> </button>
                                        @endpermission
                                        <a href="{{ route('dashboard.services.edit', $item->id) }}"
                                            class="btn btn-dark text-white btn-sm custom-button"><i data-feather="edit"
                                                width="20"></i></a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
                <div class="float-right">
                    {{-- {{ $services->links() }} --}}
                </div>
            </div>
            <div id="deleteServices" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this services?</p>
                        </div>
                        <div class="modal-footer">
                            <button onclick="deleteRequest()" type="button" class="btn btn-danger">Delete</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

            <script>
                let current_id = null;

                function deleteServices(id) {
                    current_id = id;
                    $('#deleteServices').modal('show')
                }

                function deleteRequest() {
                    let request = '{{ route('dashboard.services.delete', ':id') }}';
                    request = request.replace(':id', current_id);
                    $.ajax({
                        url: request,
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        success: (res) => {
                            location.reload();
                        },
                        error: (err) => {

                        }
                    });
                }
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let sortableTable = document.querySelector('.sortable-table');

                    new Sortable(sortableTable, {
                        handle: '.sortable-row',
                        animation: 150,
                        onEnd: function(evt) {
                            let rowIds = Array.from(sortableTable.children).map(row => row.dataset.id);
                            console.log(rowIds);
                            console.log('Number of rows:', sortableTable.children.length);

                            updateSortOrder(rowIds);
                        }
                    });

                    function updateSortOrder(order) {
                        let url = sortableTable.dataset.url;

                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify({
                                    order: order
                                }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Handle success, if needed
                                console.log('Sort order updated successfully');
                            })
                            .catch(error => {
                                console.error('Error updating sort order:', error);
                            });
                    }
                });
            </script>
