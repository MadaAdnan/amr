<div class="modal fade" id="{{ $id }}" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                {{-- Create Section  --}}
                <h5 class="modal-title">Paragraph Modal</h5>
            </div>
            <div class="modal-body">
                <!-- Form -->
                <form id="edit-form">
                    <h4 class="mt-2 p-3">
                        Image
                    </h4>

                    <div class="form-check">
                        <label for="imageTypeServiceSection" class="form-label">Choose Input Method:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="{{ isset($isEdit) ? 'edit-':'' }}imageTypeServiceSection" id="{{ isset($isEdit) ? 'edit-':'' }}fileInput2"
                                value="file" checked>
                            <label class="form-check-label" for="fileInput2">Upload Image</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="{{ isset($isEdit) ? 'edit-':'' }}imageTypeServiceSection" id="{{ isset($isEdit) ? 'edit-':'' }}urlInput2"
                                value="url">
                            <label class="form-check-label" for="urlInput2">Use Image URL</label>
                        </div>
                    </div>

                    <!-- Input field for uploading a file -->
                    <div class="form-check text-center" id="{{ isset($isEdit) ? 'edit-':'' }}fileInputGroup">
                        <img id="{{ isset($isEdit) ? 'edit-':'' }}paragraphImage" width="300px"
                            src="{{ asset('assets_new/img/upload_image_placeholder.jpg') }}" alt="">
                            {{-- dffirent is to send the edit boolean to the user --}}
                            @if (isset($isEdit))
                                <input onchange="readURL(this,true);" type="file" style="display:none;"
                                id="edit-paragraphFileInput">      
                                @else
                                <input onchange="readURL(this);" type="file" style="display:none;"
                                id="paragraphFileInput">               
                            @endif

                    </div>

                    <!-- Input field for entering a URL -->
                    <div class="form-check text-center" id="{{ isset($isEdit) ? 'edit-':'' }}urlInputGroup" style="display: none;">
                        <input type="url" name="urlParagraphImage" id="{{ isset($isEdit) ? 'edit-':'' }}urlParagraphImage" class="form-control"
                            placeholder="Image URL: lavishride.com/xxxxx" value="{{ old('urlParagraphImage') }}">

                        {{-- <button onclick="loadImageFromURL()">Load Image</button> --}}
                    </div>
                    <hr />

                    <div class="mb-3">
                        <label class="form-label">Image Alignment</label>
                        <div class="row justify-content-center pl-3 pr-3">
                            <div class="col justify-content-center d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="alignment" id="{{ isset($isEdit) ? 'edit-':'' }}left"
                                        value="left">
                                    <label class="form-check-label" for="left">Left</label>
                                </div>
                            </div>
                            <div class="col justify-content-center d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="alignment" id="{{ isset($isEdit) ? 'edit-':'' }}right"
                                        value="right">
                                    <label class="form-check-label" for="right">Right</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="mb-3">
                        <label for="title" class="form-label">Alt</label>
                        <input type="text" class="form-control" id="{{ isset($isEdit) ? 'edit-':'' }}imageAlt" placeholder="Enter alt">
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Caption</label>
                        <input type="text" class="form-control" id="{{ isset($isEdit) ? 'edit-':'' }}imageCaption" placeholder="Enter caption">
                    </div>
                    <h4 class="mt-2 p-3">
                        Paragraph
                    </h4>
                    <hr />
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="{{ isset($isEdit) ? 'edit-':'' }}paragraphTitle" placeholder="Enter title">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="{{ isset($isEdit) ? 'edit':'' }}paragraphDescription" rows="3" placeholder="Enter description"></textarea>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button onclick="closeModal('{{$id}}')" type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>
                <button onclick="{{ isset($isEdit) ? "updateParagraph()" : "addParagragh()" }}" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>