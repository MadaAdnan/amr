<div class="text-right">
    {{-- <a href="{{ route('dashboard.seo_countries.create') }}" class="btn btn-primary">Create</a> --}}
    <button onclick="showCreateModal()" type="button" class="btn btn-primary remove-border">Create</button>
    <button onclick="showContentModal(
        `{{ $content['content'] ??''}}`,
    `{{ $content['seoTitleValue'] ??''}}`,
     `{{ $content['seoDescriptionValue']??'' }}`,
     `{{ $content['seoKeyPhraseValue'] ??''}}`,
     `{{ $content['countrySeoHeader'] ?? ''}}`
     )" 
     type="button" class="btn btn-secondary remove-border">Add Content</button>
    <!-- Create Modal -->
    <div class="modal" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create Country</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="countryContent">Country Name</label>

                    <input type="text" id="nameInput" name="country_name" class="form-control"
                        placeholder="Country name">
                </div>
                <div class="modal-body">

                    <select id="statusFilterInput" class="form-select" name="status">
                        <option value="">Select Status</option>
                        <option {{ $statusQuery == 'Active' ? 'selected' : '' }} value="Active">Active</option>
                        <option {{ $statusQuery == 'Disabled' ? 'selected' : '' }} value="Disabled">Disabled
                        </option>
                    </select>
                </div>
                <div class="modal-body">
                    <label for="title">City Title</label>

                    <input id="title" name="title" type="text" class="form-control" placeholder="H1 title">
                </div>
                <div class="modal-body">
                    <label for="content">City Content</label>

                    <textarea id="content" name="content" class="form-control" placeholder="content"></textarea>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel"> Cities SEO</h5>

                </div>
                <div class="modal-body">
                    <label for="city_seo_title">SEO City Title</label>

                    <input id="city_seo_title" name="city_seo_title" type="text" class="form-control"
                        placeholder="Title">
                </div>
                <div class="modal-body">
                    <label for="city_seo_description">SEO City Description</label>

                    <input id="city_seo_description" name="city_seo_description" type="text" class="form-control"
                        placeholder="Description">
                </div>
                <div class="modal-body">
                    <label for="city_seo_key_phrase">SEO City Keyword</label>

                    <input id="city_seo_key_phrase" name="city_seo_key_phrase" type="text" class="form-control"
                        placeholder="Keyphrase">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="createButton()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Modal -->
    <div class="modal" id="contentModal" tabindex="-1" role="dialog" aria-labelledby="contentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contentModalLabel">Create Content for Countries page</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    <label  class="mb-2" for="country_seo_h1">H1</label>

                    <input id="country_seo_h1" name="country_seo_h1" type="text" class="form-control"
                        placeholder="H1">
                </div>
                <div class="modal-body text-left">
                    <label  class="mb-2" for="countryContent">Country Content</label>
                    <textarea id="countryContent" name="countryContent" class="form-control" placeholder="content"></textarea>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">SEO of Countries Page</h5>

                </div>
                <div class="modal-body text-left">
                    <label  class="mb-2" for="country_seo_title">Countries Title</label>

                    <input id="country_seo_title" name="country_seo_title" type="text" class="form-control"
                        placeholder="Title">
                </div>

                <div class="modal-body text-left">
                    <label  class="mb-2" for="country_seo_description">Countries
                        Description</label>

                    <input id="country_seo_description" name="country_seo_description" type="text"
                        class="form-control" placeholder="Description">
                </div>
                <div class="modal-body text-left">
                    <label  class="mb-2" for="country_seo_key_phrase">Countries Keywords</label>

                    <input id="country_seo_key_phrase" name="country_seo_key_phrase" type="text"
                        class="form-control" placeholder="Keyphrase">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="createContentButton()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Country</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="editNameInput" style="text-align:left !important;">Country Name</label>

                    <input type="text" id="editNameInput" name="name" class="form-control"
                        placeholder="Country name">
                </div>
                <div class="modal-body">

                    <select id="editStatusInput" class="form-select" name="status">
                        <option value="">Select Status</option>
                        <option {{ $statusQuery == 'Active' ? 'selected' : '' }} value="Active">Active</option>
                        <option {{ $statusQuery == 'Disabled' ? 'selected' : '' }} value="Disabled">Disabled
                        </option>
                    </select>
                </div>
                <div class="modal-body">
                    <label for="editTitle" style="text-align:left !important;">Country Title</label>

                    <input id="editTitle" name="title" type="text" class="form-control"
                        placeholder="H1 title">
                </div>
                <div class="modal-body">
                    <label for="editContent" style="text-align:left !important;">Country Content</label>
                    <textarea id="editContent" name="content" class="form-control" placeholder="content"></textarea>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">SEO Country</h5>

                </div>
                <div class="modal-body">
                    <label for="edit_city_seo_title" style="text-align:left !important;">SEO Country Title</label>
                    <input id="edit_city_seo_title" name="edit_city_seo_title" type="text" class="form-control"
                        placeholder="Title">
                </div>
                <div class="modal-body">
                    <label for="edit_city_seo_description" style="text-align:left !important;">SEO Country
                        Description</label>
                    <input id="edit_city_seo_description" name="edit_city_seo_description" type="text"
                        class="form-control" placeholder="Description">
                </div>
                <div class="modal-body">
                    <label for="edit_city_seo_key_phrase" style="text-align:left !important;">SEO Country Keyword</label>

                    <input id="edit_city_seo_key_phrase" name="edit_city_seo_key_phrase" type="text"
                        class="form-control" placeholder="Keyphrase">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateButton()">Edit</button>
                </div>
            </div>
        </div>
    </div>

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
                                <th class="text-center">Name</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($seo_countries ?? [] as $item)
                                <tr class="sortable-row" data-id="{{ $item->id }}">
                                    <td class="text-bold-500 text-center">{{ $item->name ?? 'No Data' }}</td>
                                    <td class="text-bold-500 text-center">{{ $item->status }}</td>
                                    <td class="text-bold-500 text-center">


                                        <a href="{{ route('dashboard.seo_countries.show', $item->id) }}"
                                            class="btn btn-dark text-white btn-sm custom-button">
                                            <i data-feather="eye" width="20"></i>
                                        </a>
                                        <a href="{{ route('dashboard.seo_countries.activeInactiveSingle', $item->id) }}"
                                            class="btn btn-warning text-white btn-sm custom-button mb-2 mt-2 "><i
                                                data-feather="disc" width="20"></i></a>
                                        @permission('delete-blogs')
                                            <a href="{{ route('dashboard.seo_countries.delete', $item->id) }}"
                                                class="btn btn-danger btn-sm custom-button delete-red mb-2 mt-2">
                                                <i data-feather="trash" width="20"></i>
                                            </a>
                                        @endpermission
                                        <button
                                            onclick="showEditModal(
                                            `{{ $item->id }}`,
                                            `{{ $item->name }}`,
                                            `{{ $item->status }}`,
                                            `{{ $item->title }}`,
                                            `{{ $item->content }}`,
                                            `{{ $item->seo_city_title }}`,
                                            `{{ $item->seo_city_key_phrase }}`,
                                            `{{ $item->seo_city_description }}`,
                                        )"
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
                    {{$seo_countries->links()}}
                </div>
            </div>



            <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>



            @section('scripts')
                <script>
                    let current_item_id = 0;

                    const nameInput = $('#nameInput'); // Use jQuery to select the element
                    const statusFilterInput = $('#statusFilterInput');

                    const countrySeoHeader = $('#country_seo_h1'); //countries
                    const seoTitle = $('#country_seo_title'); //countries
                    const seoKeyPhrase = $('#country_seo_key_phrase'); //countries
                    const seoDescription = $('#country_seo_description'); //countries

                    const CitySeoTitle = $('#city_seo_title');
                    const CitySeoKeyPhrase = $('#city_seo_key_phrase');
                    const CitySeoDescription = $('#city_seo_description');

                    const editCitySeoTitle = $('#edit_city_seo_title');
                    const editCitySeoKeyPhrase = $('#edit_city_seo_key_phrase');
                    const editCitySeoDescription = $('#edit_city_seo_description');

                    const country_content = $('#countryContent');
                    const searchInput = $('#searchInput');

                    const editNameInput = $('#editNameInput');
                    const editStatusInput = $('#editStatusInput');
                    const editTitle = $('#editTitle');
                    const editContent = $('#editContent');
                    const title = $('#title');
                    const content = $('#content');

                    function showCreateModal() {
                        $('#createModal').modal('show');
                    }

                    function showContentModal( countryContent, seoTitleValue, seoDescriptionValue, seoKeyPhraseValue,countrySeoHeader) {
                        $('#country_seo_h1').val(countrySeoHeader);
                        $('#countryContent').val(countryContent);
                        $('#country_seo_title').val(seoTitleValue);
                        $('#country_seo_description').val(seoDescriptionValue);
                        $('#country_seo_key_phrase').val(seoKeyPhraseValue);
                        $('#contentModal').modal('show');
                    }

                    function showEditModal(id, name, status, title, content, seoCitytitle, seoKeyphrase, seoDescription) {
                        $('#editNameInput').val(name);
                        $('#editStatusInput').val(status);
                        $('#editTitle').val(title);
                        $('#editContent').val(content);
                        $('#edit_city_seo_title').val(seoCitytitle);
                        $('#edit_city_seo_key_phrase').val(seoKeyphrase);
                        $('#edit_city_seo_description').val(seoDescription);
                        $('#editModal').attr('data-country-id', id);
                        
                        $('#editModal').modal('show');
                    }

                    function closeButton() {
                        $('#createModal').modal('hide');
                        $('#editModal').modal('hide');
                    }

                    function createButton() {
                        let nameInputValue = nameInput.val();
                        let statusInput = statusFilterInput.val();
                        let seoTitleValue = CitySeoTitle.val();
                        let seoDescriptionValue = CitySeoDescription.val();
                        let seoKeyPhraseValue = CitySeoKeyPhrase.val();
                        let request = "{{ route('dashboard.seo_countries.store') }}";
                        let pageTitle = title.val();
                        let pageContent = content.val();
                        if (!validation()) return;

                        $.ajax({
                            url: request,
                            type: 'POST',
                            data: {
                                country_name: nameInputValue,
                                status: statusInput,
                                title: pageTitle,
                                content: pageContent,
                                seoDescriptionValue: seoDescriptionValue,
                                seoKeyPhraseValue: seoKeyPhraseValue,
                                seoTitleValue: seoTitleValue,
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

                    function createContentButton() {

                        let countriesContent = country_content.val();
                        let seoTitleValue = seoTitle.val();
                        let countrySeoHead = countrySeoHeader.val();
                        let seoDescriptionValue = seoDescription.val();
                        let seoKeyPhraseValue = seoKeyPhrase.val();
                        let request = "{{ route('dashboard.pages.createCountryContent') }}";
                        $.ajax({
                            url: request,
                            type: 'POST',
                            data: {
                                seoTitleValue: seoTitleValue,
                                seoDescriptionValue: seoDescriptionValue,
                                seoKeyPhraseValue: seoKeyPhraseValue,
                                content: countriesContent,
                                countrySeoHeader: countrySeoHead,

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

                    function updateButton(id) {
                        let editName = editNameInput.val();
                        let editStatus = editStatusInput.val();
                        let countryId = $('#editModal').attr('data-country-id');
                        let request = "{{ route('dashboard.seo_countries.update', ':id') }}";
                        let editPageTitle = editTitle.val();
                        let editPageContent = editContent.val();

                        let editCitySeoTitleValue = editCitySeoTitle.val();
                        let editCitySeoKeyPhraseValue = editCitySeoKeyPhrase.val();
                        let editCitySeoDescriptionValue = editCitySeoDescription.val();

                        request = request.replace(':id', countryId);


                        $.ajax({
                            url: request,
                            type: 'POST',
                            data: {
                                country_id: countryId,
                                country_name: editName,
                                status: editStatus,
                                title: editPageTitle,
                                content: editPageContent,
                                seoDescriptionValue: editCitySeoDescriptionValue,
                                seoKeyPhraseValue: editCitySeoKeyPhraseValue,
                                seoTitleValue: editCitySeoTitleValue,
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

                        return true;
                    }
                </script>
            @endsection
