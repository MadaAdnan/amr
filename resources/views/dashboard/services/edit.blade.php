@extends('dashboard.layouts.index')


@php
    $sections = $data
        ->sections()
        ->select('id', 'title')
        ->get()
        ->toArray();
    foreach ($sections as &$value) {
        $value = str_replace("\n", '', $value);
        $value = str_replace("\r", '', $value);
    }
@endphp

@section('content')
    <style>
        input#flexSwitchCheckDefault {
            width: 50px;
            height: 25px;
        }

        .back-buuton {
            background-color: #ad2227;
            border-color: red;
            padding: 5px;
            font-size: 25px;
            border-radius: 5px;
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
    <form enctype="multipart/form-data" action="{{ route('dashboard.services.update', $data->id) }}" id="AddForm"
        method="post">
        @csrf
        <div class="card p-3">
            <div class="container">
                <div class="d-flex justify-content-between">
                    <h3 class="text-bold">Edit Services</h3>
                    <div>
                        <button onclick="checkSlug(true)" type="button"
                            class="btn btn-primary preview-button">Preview</button>
                        <button onclick="checkSlug()" type="button" class="btn btn-primary">Update</button>
                    </div>
                </div>
                <hr />

                <div class="text-center align-items-center w-100 mb-3">
                    <img id="previewImage" width="400px" src="{{ $data->image_url??$data->image}}"
                        onerror="this.onerror=null;this.src='{{ asset('dashboard/assets/images/upload_image_placeholder.png') }}';"
                        alt="">
                </div>
                <div class="mb-3">
                    <label class="form-label">Choose Input Method:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="inputMethod" id="fileInput" value="file" checked>
                        <label class="form-check-label" for="fileInput">Upload Image</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="inputMethod" id="urlInput" value="url">
                        <label class="form-check-label" for="urlInput">Use Image URL</label>
                    </div>
                </div>
                
                <div class="mb-3" id="fileInputContainer">
                    <label for="title" class="form-label">Thumbnail</label>
                    <input name="image" type="file" onchange="handeImageInputChange(event)" class="form-control" id="image" placeholder="the slider title">
                    <span class="mt-2">Note:When you add a link and upload an image together, it will always prioritize the link.</span>
                </div>
                
                <div class="mb-3" id="urlInputContainer" style="display:none;">
                    <label for="image_url" class="form-label">URL</label>
                    <input name="image_url"  class="form-control" id="image_url" placeholder="image URL" value="{{$data->image_url??''}}">
                    <span class="mt-2">Note:When you add a link and upload an image together, it will always prioritize the link.</span>
                </div>


                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input id="title" value="{{ $data->title }}" name="title" type="text" class="form-control"
                        id="title" placeholder="please add the title">
                </div>
                <div class="mb-3">
                    <label for="shortTitle" class="form-label">Short Title</label>
                    <input id="short_title" name="short_title" type="text" class="form-control" id="short_title"
                        placeholder="please add the shortTitle" value="{{ $data->short_title}}">
                </div>

                <div class="mb-3">
                    <label for="caption" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" placeholder="please add the description">{{ $data->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="Draft" @if(isset($data) && $data->status == "Draft") selected @endif>Draft</option>
                        <option value="Published" @if(isset($data) && $data->status == "Published") selected @endif>Published</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="cityId" class="form-label">City</label>
                    <select name="city_id" id="cityId" class="form-control">
                        <option>Select City</option>
                        @foreach ($cities  as $item)
                            <option value="{{ $item->id }}" @if ($data->city_id == $item->id) selected @endif>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-6">
                    Slug
                </div>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">https://lavishride.com/services/xxx</div>
                    </div>
                    <input id="slug" name="slug" type="text" value="{{ $data->slug }}" class="form-control"
                        id="inlineFormInputGroup" placeholder="slug" required>
                </div>
                <div class="col-6 mt-4">
                    Orphan
                </div>
                <div class="input-group mb-2 d-flex flex-row-reverse">

                    <div class="form-check form-switch">
                        <input {{ $data->is_orphan ? 'checked' : '' }} name="is_orphan" class="form-check-input" type="checkbox"
                            id="isOrphan">
                    </div>
                </div>

                <h3 class="mt-5">Seo</h3>
                <hr class="mb-3">

                <div class="col">
                    <div class="mb-3">
                        <label for="seo_title" class="form-label">Title</label>
                        <input value="{{ $data->seo_title }}" id="seo_title" name="seo_title" type="text"
                            class="form-control">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="seo_description" class="form-label">Description</label>
                        <input value="{{ $data->seo_description }}" id="seo_description" name="seo_description"
                            type="text" class="form-control">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="seo_key_phrase" class="form-label">Keyphrase</label>
                        <input value="{{ $data->seo_key_phrase }}" id="seo_key_phrase" name="seo_key_phrase" type="text"
                            class="form-control">
                    </div>
                </div>


                <hr />
                <h2>
                    Paragraphs
                </h2>
                <div class="text-right">
                    <button onclick="opeanModal()" type="button" class="btn btn-primary">Create</button>
                </div>

                <div class="drag_area dropzone" id="paragraphsSection" class="row mt-5">

                    @foreach ($data->sections()->orderBy('sort_number', 'asc')->get() as $index => $item)
                        @php
                            $image_url = $item->paragraph_image_url ?? $item->thumbnail;
                        @endphp
                        <div id="{{ $item->id }}" class="col-12 mt-4 draggable">
                            <hr />
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h3>
                                        <i class="fa fa-arrows-v h3"></i>
                                        Drag
                                    </h3>
                                </div>
                                <div>
                                    <button onclick="deleteItem('{{ $item->id }}','{{ $index }}',true)"
                                        type="button" class="btn btn-danger">Delete</button>
                                    <button onclick="editItem('{{ $item->id }}')" type="button"
                                        class="btn btn-warning">Edit</button>
                                </div>
                            </div>
                            <div class="row flex-row-reverse cp-md-margin-bottom-80 cp-margin-bottom-20">
                                @if ($item->is_left == 0)
                                    <div class="col-md-6 col-12 wow fadeInLeft animated" data-wow-duration=".5s"
                                        data-wow-delay=".3s">
                                        <div class="about-img">
                                            <img src="{{ $image_url }}" class="img-fluid"
                                                alt="{{ $item->caption }}" loading="lazy">
                                            <figcaption>{{ $item->caption }}</figcaption>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6 col-12 cp-margin-top-15">
                                    <div class="content-area">
                                        <div class="col-6 padding-0 padding-bottom-15 border-bottom-main-1">
                                            <h2 class="section-title">{{ $item->title }}</h2>
                                        </div>
                                        <div class="margin-top-25">
                                            {!! $item->description !!}
                                        </div>
                                    </div>
                                </div>
                                @if ($item->is_left != 0)
                                    <div class="col-md-6 col-12 wow fadeInRight animated" data-wow-duration=".5s"
                                        data-wow-delay=".3s">
                                        <div class="about-img">
                                            <img src="{{ $image_url }}" class="img-fluid" alt=""
                                                loading="lazy">
                                            <figcaption>{{ $item->caption }}</figcaption>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
    </form>

        {{-- add modal --}}
        @include('dashboard.services.includes.serviceModal',['id'=>'addModal'])

        {{-- edit modal --}}
        @include('dashboard.services.includes.serviceModal',['id'=>'editModal' , 'isEdit'=>true])

        {{-- loader modal --}}
        @include('dashboard.services.includes.loaderModal')
    

@endsection

@section('scripts')
    @include('dashboard.services.script')

    <script>
        // get the current selected item id
        let paragraphEditededId = 0;

        // service id was sent
         serviceId = '{{ $data->id }}';
        
        //forbidden items ids it's ids could not begenerated
        let forbiddenItemIds = JSON.parse('{!! json_encode(
            $data->sections()->pluck('id')->toArray(),
        ) !!}');

        //get already created items so it will not be created again in the front-end
        addAlreadyExistItems();

        function editItem(id) 
        {
            
            paragraphEditededId = id;

            Swal.fire({
                title: 'Getting Data...',
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            })

            let request = '{{ route('dashboard.services.get_paragraph', ':id') }}';
            request = request.replace(':id', id);
            $.ajax({
                url: request,
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                type: 'GET',
                success: (res) => {
                    const item = res.data;

                    /** Set Data For The Edit Modal **/
                    document.getElementById('edit-paragraphImage').src = item.thumbnail;
                    document.getElementById('edit-left').checked = item.is_left == 1 ? true : false;
                    document.getElementById('edit-right').checked = item.is_left == 0 ? true : false;
                    document.getElementById('edit-imageAlt').value = item.alt;
                    document.getElementById('edit-imageCaption').value = item.caption;
                    document.getElementById('edit-paragraphTitle').value = item.title;
                    CKEDITOR.instances.editparagraphDescription.setData(item.description);

                    Swal.close();

                    $('#editModal').modal('show');

                },
                error: (err) => {
                    return Swal.fire(
                        'Something went wrong',
                        'Please try again later',
                        'error'
                    )

                }
            });
        }

        function createIitemId() {
            let id = Math.floor(Math.random() * (1000 - 0 + 1) + 1);
            if (forbiddenItemIds.includes(id)) return createIitemId();
            return id;
        }

        function addAlreadyExistItems() {
            let items = JSON.parse(`{!! json_encode($sections) !!}`);
            items.forEach((element, index) => {
                formData.append(`images[${element.id}][title]`, element.title)
                formData.append(`images[${element.id}][ignore]`, true)
                formData.append(`images[${element.id}][sort]`, index)
                formData.append(`images[${element.id}][id]`, element.id)
            });
        }

        function editParagragh(id) 
        {
            if (!updateParagraghValdation(true)) {
                return;
            }

            let titleInput = document.getElementById('edit-paragraphTitle');
            let descriptionInput = document.getElementById('edit-paragraphDescription');
            let imageCaption = document.getElementById('edit-imageCaption');
            let imageAlt = document.getElementById('edit-imageAlt');
            let isRight = document.getElementById('edit-right').checked ? true : false;

            let object = {
                image: currentImage,
                title: titleInput.value,
                description: descriptionInput.value,
                isRight: isRight
            }

            let createImageId = createIitemId();
            formData.append(`images[${createImageId}]['image']`, currentImage)
            formData.append(`images[${createImageId}]['title']`, titleInput)
            formData.append(`images[${createImageId}]['description']`, descriptionInput)
            formData.append(`images[${createImageId}]['isRight']`, object.isRight)
            formData.append(`images[${createImageId}][ignore]`, false)
            formData.append(`images[${createImageId}][sort]`, imagesArray.length)

            let itemDiv = `
            <div id='${createIitemId()}' class="col-12 mt-4 draggable">
                <hr/>
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <h3>Actions</h3>
                    </div>
                    <div>
                        <button onclick="editModal('${imagesArray.length}','${currentImageUrl}','${titleInput.value}','${descriptionInput.value}','${object.isRight}','${imageAlt.value}','${imageCaption.value}')" type="button" class="btn btn-success">Edit</button>    
                        <button type="button" class="btn btn-danger">Delete</button>    
                    </div>
                </div>
                        <div class="row flex-row-reverse cp-md-margin-bottom-80 cp-margin-bottom-20">
                            ${
                                isRight?`<div class="col-md-6 col-12 wow fadeIn${isRight == false?'Left':'Right'} animated" data-wow-duration=".5s" data-wow-delay=".3s">
                                    <div class="about-img">
                                        <img src="${currentImageUrl}" class="img-fluid" alt="${imageAlt.value}" loading="lazy">
                                        <figcaption>${imageCaption.value}</figcaption>
                                    </div>
                                </div>`:''
                            }
                            <div class="col-md-6 col-12 cp-margin-top-15">
                                <div class="content-area">
                                    <div class="col-6 padding-0 padding-bottom-15 border-bottom-main-1">
                                        <h2 class="section-title">${titleInput.value}</h2>
                                    </div>
                                    <div class="margin-top-25">
                                        <p class="responsive-paragraph">${descriptionInput.value}</p>
                                    </div>
                                </div>
                            </div>
                            ${
                                !isRight?`<div class="col-md-6 col-12 wow fadeIn${isRight == false?'Left':'Right'} animated" data-wow-duration=".5s" data-wow-delay=".3s">
                                    <div class="about-img">
                                        <img src="${currentImageUrl}" class="img-fluid" alt="${imageAlt.value}" loading="lazy">
                                        <figcaption>${imageCaption.value}</figcaption>
                                    </div>
                                </div>`:''
                            }
                           
                        </div>
            </div>
            `;
            $('#paragraphsSection').append(itemDiv);

            titleInput.value = '';
            descriptionInput.value = '';
            imageAlt.value = '';
            imageCaption.value = '';

            imagesArray.push(object);
            $('#paragraphImage').attr('src', '{{ asset('assets_new/img/upload_image_placeholder.jpg') }}').width(300);
            $('#paragraphFileInput').val('');
        }

        function opeanModal() {
            $('#addModal').modal('toggle');
        }

        // update paragraph
        function updateParagraph() 
        {
            // valdaite the data
            if (!updateParagraghValdation(true)) {
                return;
            }

            let request = '{{ route('dashboard.services.update_paragraph', ':id') }}';
            request = request.replace(":id", paragraphEditededId);
            let image = document.getElementById('edit-paragraphFileInput').files;
            const form = new FormData(document.getElementById('edit-form'));

            // add data to the form
            form.set('title',document.getElementById('edit-paragraphTitle').value);
            form.set('caption',document.getElementById('edit-imageCaption').value);
            form.set('alt',document.getElementById('edit-imageAlt').value);
            form.set('is_left',document.getElementById('edit-right').checked ? 'right' : 'left');

            if(document.getElementById('edit-urlParagraphImage').value != '')
            {
                form.set('paragraph_image_url',document.getElementById('edit-urlParagraphImage').value);
            }
            form.set('description', CKEDITOR.instances.editparagraphDescription.getData());

            if (image&&image.length > 0) {
                form.set('image', image[0]);
            }

            $.ajax({
                url: request,
                type: 'POST',
                processData: false,
                contentType: false,
                data: form,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (res) => {
                     location.reload();
                },
                error: (err) => {
                    console.log(err)
                }
            });
        }

        // ??
        function updateParagraghValdation(is_edit = false) {

            let titleInput = document.getElementById('edit-paragraphTitle');
            let imageCaption = document.getElementById('edit-imageCaption');
            let paragraphFileInput = document.getElementById('paragraphFileInput');
            let imageAltInput = document.getElementById('edit-imageAlt');
            let descriptionInput = CKEDITOR.instances.editparagraphDescription.getData();


            if (imageAltInput.value == '') {
                Toast.fire({
                    icon: 'error',
                    title: 'Please add alt'
                })
                return false;
            }


            if (titleInput.value == '') {
                Toast.fire({
                    icon: 'error',
                    title: 'Please add title'
                })
                return false;
            }
            return true;

        }

        //Change the edit method
        CKEDITOR.replace('editparagraphDescription', {
            filebrowserUploadUrl: "{{ route('dashboard.upload_an_Image_ck_editor', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });

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

        //when the user click on the image trigger the image file input
        $('#edit-paragraphImage').click(()=>{
            $('#edit-paragraphFileInput').click();
        });

    </script>
@endsection
