@extends('dashboard.layouts.index')

@section('content')
    @php
        $statusQuery = request()->get('status');
        $query = request()->query('query');
        $route = Route::currentRouteName();
        $nowDate = \Carbon\Carbon::now();

    @endphp
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>

        </div>
        <!--Create Modal -->
        <div class="modal" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Create City</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="nameInput" name="city_name" class="form-control"
                            placeholder="City name" required>
                    </div>
                    <div class="input-group ">
                        <div class="input-group-prepend" style="margin-left:2.25em">
                            <div class="input-group-text">https://lavishride.com/city/xxx</div>
                        </div>
                        <input id="slug" name="slug" type="text" class="form-control" id="inlineFormInputGroup"
                            placeholder="slug" required>
                    </div>
                    {{-- <div class="modal-body">
                        <input type="text" id="slug" name="slug" class="form-control"
                            placeholder="Slug">
                    </div> --}}
                    <div class="modal-body">

                        <select id="statusFilterInput" class="form-select" name="status" required>
                            <option value="">Select Status</option>
                            <option {{ $statusQuery == 'Active' ? 'selected' : '' }} value="Active">Active</option>
                            <option {{ $statusQuery == 'Disabled' ? 'selected' : '' }} value="Disabled">Disabled
                            </option>
                        </select>
                    </div>

                    <div class="modal-body">

                        <label for="title" style="text-align:left !important;">City Services Header</label>
    
                        <input id="header" name="header" type="text" class="form-control" placeholder="H1 title">
                    </div>
                    <div class="modal-body">
                        <label for="content" style="text-align:left !important;">City Services Content</label>
    
                        <textarea id="content" name="content" class="form-control" placeholder="content"></textarea>
                    </div>
                    

                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">SEO</h5>
                      
                    </div>
                    <div class="modal-body">
                        <input id="seo_title" name="seo_title" type="text" class="form-control" placeholder="Title">
                    </div>
                    <div class="modal-body">
                        <input id="seo_description" name="seo_description" type="text" class="form-control"
                            placeholder="Description">
                    </div>
                    <div class="modal-body">
                        <input id="seo_key_phrase" name="seo_key_phrase" type="text" class="form-control"
                            placeholder="Keyphrase">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="createButton()">Create</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Create Modal -->
        <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Create City</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="editNameInput" style="text-align:left !important;">City Name</label>

                        <input type="text" id="editNameInput" name="city_name" class="form-control"
                            placeholder="City name" required>
                    </div>
                    <div class="input-group ">

                        <div class="input-group-prepend" style="margin-left:2.25em">

                            <div class="input-group-text">https://lavishride.com/city/xxx</div>
                        </div>
                        <input id="editSlug" name="slug" type="text" class="form-control" id="inlineFormInputGroup"
                            placeholder="slug" required>
                    </div>
                    
                    <div class="modal-body">

                        <select id="editStatusInput" class="form-select" name="status" required>
                            <option value="">Select Status</option>
                            <option {{ $statusQuery == 'Active' ? 'selected' : '' }} value="Active">Active</option>
                            <option {{ $statusQuery == 'Disabled' ? 'selected' : '' }} value="Disabled">Disabled
                            </option>
                        </select>
                    </div>

                    <div class="modal-body">

                        <label for="title" style="text-align:left !important;">City Services Header</label>
    
                        <input id="editHeader" name="header" type="text" class="form-control" placeholder="H1 title">
                    </div>
                    <div class="modal-body">
                        <label for="editContent" style="text-align:left !important;">City Services Content</label>
    
                        <textarea id="editContent" name="content" class="form-control" placeholder="content"></textarea>
                    </div>
                    

                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">SEO</h5>
                      
                    </div>
                    <div class="modal-body">
                        <input id="edit_seo_title" name="seo_title" type="text" class="form-control" placeholder="Title">
                    </div>
                    <div class="modal-body">
                        <input id="edit_seo_description" name="seo_description" type="text" class="form-control"
                            placeholder="Description">
                    </div>
                    <div class="modal-body">
                        <input id="edit_seo_key_phrase" name="seo_key_phrase" type="text" class="form-control"
                            placeholder="Keyphrase">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="updateButton()">Edit</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card">
        <div class="row" id="basic-table">
            <div class="col-12">
                <div class="row p-4">
                    <h4 class="w-50 "><a href="{{ route('dashboard.pages.index', 'Countries') }}">{{ $country->name }}</a>
                        / Cities</h4>
                    <div class="w-50 text-right">
                        <button onclick="showCreateModal()" type="button"
                            class="btn btn-primary remove-border">Create</button>

                    </div>
                </div>
                <div class="card-content">
                    <div class="row">
                        <div class="form-group col p-4">
                            <h6>
                                Title
                            </h6>
                            <input onkeypress="handle(event)" value='{{ $query }}' id="searchInput"
                                class="form-control" type="text">
                        </div>

                        <div class="form-group col p-4">
                            @if ($statusQuery || $query)
                                <a href="{{ route($route) }}" class="btn btn-danger mt-4">Clear</a>
                            @endif
                            <button class="btn btn-primary mt-4" onclick="filter()"> <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Slug</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cities??[] as $item)
                                        <tr>

                                            <td class="text-bold-500 text-center">{{ $item->name ?? 'No Data' }}</td>
                                            <td class="text-bold-500 text-center">{{ $item->slug }}</td>
                                            <td class="text-bold-500 text-center">{{ $item->status }}</td>
                                            <td class="text-bold-500">
                                                <a href="{{ route('dashboard.services.create') }}" id='createCityButton'
                                                    class="btn btn-dark text-white btn-sm custom-button create-button"
                                                    data-city-id="{{ $item->id }}">
                                                    <i data-feather="plus" width="20"></i>
                                                </a>


                                                <a href="{{ route('dashboard.seo_cities.activeInactiveSingle', $item->id) }}"
                                                    class="btn btn-warning text-white btn-sm custom-button mb-2 mt-2 "><i
                                                        data-feather="disc" width="20"></i></a>
                                                @permission('delete-blogs')
                                                    <a href="{{ route('dashboard.seo_cities.delete', $item->id) }}"
                                                        class="btn btn-danger btn-sm custom-button delete-red mb-2 mt-2">
                                                        <i data-feather="trash" width="20"></i>
                                                    </a>
                                                @endpermission

                                                <button
                                                onclick="showEditModal(
                                                `{{ $item->id }}`,
                                                `{{ $item->country_id }}`,
                                                `{{ $item->name }}`,
                                                `{{ $item->status }}`,
                                                `{{ $item->slug }}`,
                                                `{{ $item->seo_title }}`,
                                                `{{ $item->seo_description }}`,
                                                `{{ $item->seo_key_phrase }}`,
                                                `{{ $item->services_header }}`,
                                                `{{ $item->services_content }}`)",
                                                class="btn btn-success text-white btn-sm custom-button">
                                                <i data-feather="edit" width="20"></i>
                                            </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No countries available.</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>

                        </div>
                        <div class="float-right">
                            {{ $cities->links() }}
                        </div>
                    </div>

                </div>


                <div id="deleteModal" class="modal fade" id="deleteModal" tabindex="-1"
                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this blog?</p>
                            </div>
                            <div class="modal-footer">
                                <button onclick="closeButton()" type="button" class="btn btn-secondary"
                                    data-mdb-dismiss="modal">Cancel</button>
                                <button onclick="confirmDelete()" type="button" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection

            @section('scripts')
                <script>
                    let current_item_id = 0;

                    function filter() {
                        const filter_input = document.getElementById('searchInput').value;
                        const status_input = null;
                        let url = '{{ route('dashboard.seo_cities.index') }}'

                        if (!filter_input && status_input) {
                            url = url + '?status=' + status_input
                        }
                        if (filter_input && !status_input) {
                            url = url + '?query=' + filter_input
                        }
                        if (filter_input && status_input) {
                            url = url + '?query=' + filter_input + '&&status=' + status_input
                        }

                        window.location.href = url;
                    }

                    const nameInput = $('#nameInput'); // Use jQuery to select the element
                    const statusFilterInput = $('#statusFilterInput');
                    const ciySlug = $('#slug');
                    const header = $('#header');
                    const content = $('#content');
                    
                    
                    const seoTitle = $('#seo_title');
                    const seoDescription = $('#seo_description');
                    const seoKeyPhrase = $('#seo_key_phrase');
                    const searchInput = $('#searchInput');
                    
                    
                    const editHeader = $('#editHeader');
                    const editContent = $('#editContent');
                    const editSlug = $('#editSlug');
                    const editNameInput = $('#editNameInput');
                    const editStatusInput = $('#editStatusInput');
                    const editSeoTitle = $('#edit_seo_title');
                    const editSeoDescription = $('#edit_seo_description');
                    const editSeoKeyPhrase = $('#edit_seo_key_phrase');
                    function showCreateModal() {
                        $('#createModal').modal('show');
                    }

                    function showEditModal(id, country_id, name, status,editSlug, seoTitle,SeoDescription,SeoKeyPhrase,servicesHeader,servicesContent) {
                        $('#editNameInput').val(name);
                        $('#editStatusInput').val(status);
                        $('#edit_seo_title').val(seoTitle);
                        $('#editSlug').val(editSlug);
                        $('#edit_seo_description').val(SeoDescription);
                        $('#edit_seo_key_phrase').val(SeoKeyPhrase);
                        $('#editHeader').val(servicesHeader);
                        $('#editContent').val(servicesContent);
                        $('#editModal').attr('city-id', id);
                        $('#editModal').attr('country-id', country_id);

                        $('#editModal').modal('show');
                    }
                    function closeButton() {
                        $('#createModal').modal('hide');
                        $('#editModal').modal('hide');
                    }

                    function createButton() {
                        let nameInputValue = nameInput.val();
                        let statusInput = statusFilterInput.val();
                        let slug = ciySlug.val();
                        let seoTitleValue = seoTitle.val();
                        let seoDescriptionValue = seoDescription.val();
                        let seoKeyPhraseValue = seoKeyPhrase.val();
                        let headerValue = header.val();
                        let contentValue = content.val();
                        
                        let countryId = '{{ $country->id }}'
                        let request = "{{ route('dashboard.seo_cities.store') }}";

                        if (!validation()) return;

                        $.ajax({
                            url: request,
                            type: 'POST',
                            data: {
                                cityName: nameInputValue,
                                status: statusInput,
                                slug: slug,
                                seo_title: seoTitleValue,
                                seo_description: seoDescriptionValue,
                                seo_key_phrase: seoKeyPhraseValue,
                                header:headerValue,
                                content:contentValue,
                                id: countryId,
                                '_token': '{{ csrf_token() }}'
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

                    function updateButton(id) {
                        let nameInputValue = editNameInput.val();
                        let statusInput = editStatusInput.val();
                        let slug = editSlug.val();

                        let editHeaderValue = editHeader.val();
                        let editContentValue = editContent.val();

                        let seoTitleValue = editSeoTitle.val();
                        let seoDescriptionValue = editSeoDescription.val();
                        let seoKeyPhraseValue = editSeoKeyPhrase.val();

                        let cityId = $('#editModal').attr('city-id');
                        let countryId = $('#editModal').attr('country-id');
                        let request = "{{ route('dashboard.seo_cities.update', ':id') }}";
                        request = request.replace(':id', cityId);
                        $.ajax({
                            url: request,
                            type: 'POST',
                            data: {
                                status: statusInput, // Use jQuery val() to get the value
                                cityName: nameInputValue,
                                slug: slug,
                                seo_title: seoTitleValue,
                                seo_description: seoDescriptionValue,
                                seo_key_phrase: seoKeyPhraseValue,
                                countryId :countryId,
                                header :editHeaderValue,
                                content:editContentValue,
                                '_token': '{{ csrf_token() }}'
                            },
                            success: (res) => {
                                console.log('response: ', res)
                                location.reload();
                            },
                            error: (err) => {
                                console.log(err)

                                Swal.fire({
                                    title: 'Something went wrong',
                                    icon: 'error',
                                    confirmButtonText: 'Ok'
                                })
                            }
                        })
                    }

                    function openEditModal(item) {
                        const obj = JSON.parse(item)

                        current_item_id = obj.id;
                        editNameInput.val(obj.name); // Use jQuery val() to set the value
                        editStatusInput.val(obj.status); // Use jQuery val() to set the value
                        $('#editModal').modal('show');
                    }

                    function validation() {
                        if (nameInput.val() == '') { // Use jQuery val() to get the value
                            Swal.fire({
                                title: 'Please add the title!',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            })
                            return false;
                        }
                        if (ciySlug.val() == '') { // Use jQuery val() to get the value
                            Swal.fire({
                                title: 'Please add the slug!',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            })
                            return false;
                        }
                        if (statusFilterInput.val() == '') { // Use jQuery val() to get the value
                            Swal.fire({
                                title: 'Please add the status!',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            })
                            return false;
                        }

                        return true;
                    }


                    $(document).ready(function() {
                        $('#createCityButton').on('click', function(event) {
                            event.preventDefault(); // Prevent the default behavior (navigation)

                            // Get the city ID from the data attribute
                            const cityId = $(this).data('city-id');

                            // Perform an AJAX request to your server
                            $.ajax({
                                url: '{{ route('dashboard.services.store_city_id') }}',
                                type: 'POST',
                                data: {
                                    city_id: cityId,
                                    '_token': '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    console.log('City ID stored successfully.');
                                    console.log(cityId);
                                    // Store the city_id in a cookie
                                    document.cookie = `city_id=${cityId}; path=/`;
                                    // Redirect to the create page after the AJAX request is successful
                                    window.location.href = '{{ route('dashboard.services.create') }}';
                                },
                                error: function(error) {
                                    console.error('Error storing City ID:', error);
                                }
                            });
                        });
                    });
                </script>
            @endsection
