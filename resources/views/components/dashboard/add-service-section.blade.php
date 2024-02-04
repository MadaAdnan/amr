<div class="modal fade" id="{{ $modalId }}" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                {{-- Create Section  --}}
                <h5 class="modal-title" id="exampleModalLabel">Add Paragraph</h5>
            </div>
            <div class="modal-body">
                <!-- Form -->
                <form>
                    <h4 class="mt-2 p-3">
                        Image
                    </h4>

                    <div class="form-check">
                        <label for="inputMethod2" class="form-label">Choose Input Method:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="inputMethod2" id="fileInput2"
                                value="file" checked>
                            <label class="form-check-label" for="fileInput2">Upload Image</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="inputMethod2" id="urlInput2"
                                value="url">
                            <label class="form-check-label" for="urlInput2">Use Image URL</label>
                        </div>
                    </div>

                    <!-- Input field for uploading a file -->
                    <div class="form-check text-center" id="fileInputGroup">
                        <img id="paragraphImage" width="300px"
                            src="{{ asset('assets_new/img/upload_image_placeholder.jpg') }}" alt="">
                        <input onchange="readURL(this);" type="file" style="display:none;"
                            id="paragraphFileInput">
                    </div>

                    <!-- Input field for entering a URL -->
                    <div class="form-check text-center" id="urlInputGroup" style="display: none;">
                        <input type="url" name="urlParagraphImage" id="urlParagraphImage" class="form-control"
                            placeholder="Image URL: lavishride.com/xxxxx" value="{{ old('urlParagraphImage') }}">

                        {{-- <button onclick="loadImageFromURL()">Load Image</button> --}}
                    </div>
                    <hr />

                    <div class="mb-3">
                        <label class="form-label">Image Alignment</label>
                        <div class="row justify-content-center pl-3 pr-3">
                            <div class="col justify-content-center d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="alignment" id="left"
                                        value="left">
                                    <label class="form-check-label" for="left">Left</label>
                                </div>
                            </div>
                            <div class="col justify-content-center d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="alignment" id="right"
                                        value="right">
                                    <label class="form-check-label" for="right">Right</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="mb-3">
                        <label for="title" class="form-label">Alt</label>
                        <input type="text" class="form-control" id="imageAlt" placeholder="Enter alt">
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Caption</label>
                        <input type="text" class="form-control" id="imageCaption" placeholder="Enter caption">
                    </div>
                    <h4 class="mt-2 p-3">
                        Paragraph
                    </h4>
                    <hr />
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="paragraphTitle" placeholder="Enter title">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="paragraphDescription" rows="3" placeholder="Enter description"></textarea>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button onclick="closeModal()" type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>
                <button onclick="addParagragh()" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>