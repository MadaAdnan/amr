@extends('dashboard.layouts.index')


@section('content')
    <style>
        input#flexSwitchCheckDefault {
            width: 50px;
            height: 25px;
        }

        button.btn.btn-primary.preview-button {
            background: #babec1;
            border: #babec1;
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <form enctype="multipart/form-data" action="{{ route('dashboard.services.store') }}" id="AddForm" method="post">
        @csrf
        <div class="card p-3">
            <div class="container">
                <div class="d-flex justify-content-between">
                    <h3 class="text-bold">Create Services</h3>
                    <div>
                        <button onclick="checkSlug(true)" type="button"
                            class="btn btn-primary preview-button">Preview</button>
                        <button onclick="checkSlug(false)" type="button" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                <hr />

                <div class="text-center align-items-center w-100 mb-3">
                    <img id="previewImage" width="400px" src="{{ asset('assets_new/img/upload_image_placeholder.jpg') }}"
                        alt="">
                </div>
                <div class="mb-3">
                    <label class="form-label">Choose Input Method:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="inputMethod" id="fileInput" value="file"
                            checked>
                        <label class="form-check-label" for="fileInput">Upload Image</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="inputMethod" id="urlInput" value="url">
                        <label class="form-check-label" for="urlInput">Use Image URL</label>
                    </div>
                </div>

                <div class="mb-3" id="fileInputContainer">
                    <label for="title" class="form-label">Thumbnail</label>
                    <input name="image" type="file" onchange="handeImageInputChange(event)" class="form-control"
                        id="image" placeholder="the slider title">
                </div>

                <div class="mb-3" id="urlInputContainer" style="display:none;">
                    <label for="image_url" class="form-label">URL</label>
                    <input name="image_url" class="form-control" id="image_url" placeholder="image URL"
                        value="{{ old('image_url') }}">
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label"> Title</label>
                    <input id="title" name="title" type="text" class="form-control" id="title"
                        placeholder="please add the title">
                </div>
                <div class="mb-3">
                    <label for="short_title" class="form-label">Short Title</label>
                    <input id="short_title" name="short_title" type="text" class="form-control" id="short_title"
                        placeholder="please add the shortTitle">
                </div>

                <div class="mb-3">
                    <label for="caption" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" placeholder="please add the description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="" @if (old('status') == '') selected @endif>Select</option>
                        <option value="Draft" @if (old('status') == 'Draft') selected @endif>Draft</option>
                        <option value="Published" @if (old('status') == 'Published') selected @endif>Published</option>
                    </select>

                </div>
                <div class="mb-3">
                    <label for="city_id" class="form-label">City</label>
                    <select name="city_id" id="cityId" class="form-control">
                        <option value="">Select City</option>
                        @forelse ($cities??[] as $item)
                            <option value="{{ $item->id }}"
                                @if (isset($city_id) && $city_id == $item->id) selected @elseif (old('city_id') == $item->id) selected @endif>
                                {{ $item->name }}</option>
                        @empty
                        @endforelse

                    </select>

                </div>
                <div>
                    <div class="col-6">
                        Slug
                    </div>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">https://lavishride.com/services/xxx</div>
                        </div>
                        <input id="slug" name="slug" type="text" class="form-control"
                            id="inlineFormInputGroup" placeholder="slug" required>
                    </div>

                    <div class="col-6 mt-4">
                        Orphan
                    </div>
                    <div class="input-group mb-2 d-flex flex-row-reverse">
                        <div class="form-check form-switch">
                            <input name="is_orphan" class="form-check-input" type="checkbox" id="isOrphan">
                        </div>
                    </div>


                    <h3 class="mt-5">Seo</h3>
                    <hr class="mb-3">

                    <div class="col">
                        <div class="mb-3">
                            <label for="seo_title" class="form-label">Title</label>
                            <input id="seo_title" name="seo_title" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="seo_description" class="form-label">Description</label>
                            <input id="seo_description" name="seo_description" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="seo_key_phrase" class="form-label">Keyphrase</label>
                            <input id="seo_key_phrase" name="seo_key_phrase" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <hr />
                <h2>
                    Paragraphs
                </h2>
                <div class="text-right">
                    <button onclick="opeanModal()" type="button" class="btn btn-primary">Create</button>
                </div>

                <div id="paragraphsSection" class="row mt-5 drag_area dropzone"></div>
            </div>
    </form>

    {{-- add modal --}}
    @include('dashboard.services.includes.serviceModal',['id'=>'addModal'])

    {{-- loader modal --}}
    @include('dashboard.services.includes.loaderModal')



@endsection


@section('scripts')
@include('dashboard.services.script')

    <script>

        function opeanModal() {
            $('#addModal').modal('toggle');
        }
        
        function inputValdation() {
            const titleInput = document.getElementById('title').value;
            const descriptionInput = document.getElementById('description').value;
            const imageInput = document.getElementById('image');
            const imageUrl = document.getElementById('image_url');
            const urlParagraphImage = document.getElementById('urlParagraphImage');
            if ((imageInput.value == '') && (imageUrl.value = '')) {
                Toast.fire({
                    icon: 'error',
                    title: 'Please add an image'
                })
                return false;
            }
            if (titleInput == '') {
                Toast.fire({
                    icon: 'error',
                    title: 'Please add title'
                })

                return false;
            }
            return true;

        }

    </script>
@endsection
