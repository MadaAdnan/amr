@extends('dashboard.layouts.index')


@section('content')
    <style>
        input#flexSwitchCheckDefault {
            width: 50px;
            height: 25px;
        }

        #previewImage {
            height: 300px;
            object-fit: cover;
        }
    </style>
    <div class="card p-3">
        @if ($errors->any())
            <div class="alert alert-danger p-5" role="alert">
                @foreach ($errors->all() as $error)
                    <li> {{ $error }}</li>
                @endforeach
            </div>
        @endif
        <form enctype="multipart/form-data" action="{{ route('dashboard.fleets.store') }}" id="AddForm" method="post">
            @csrf
            <div class="text-center">
                <img id="previewImage" src="{{ asset('dashboard/assets/images/upload_img.png') }}" alt="">
            </div>
            <div class="mb-3">
                <label for="imageAlt" class="form-label">Image Alt</label>
                <input name="image_alt" type="text" class="form-control" id="imageAlt">
            </div>
            <input name="image" onchange="handeImageInputChange(event)" id="uploadImage" type="file" hidden>

            <div class="mb-3">
                <label for="titleInput" class="form-label">Title</label>
                <input name="title" type="text" class="form-control" id="titleInput" />
            </div>
            <div class="mb-3">
                <label for="slugTitle" class="form-label">Slug</label>
                <input name="slug" type="text" class="form-control" id="slug" />
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Description</label>
                <textarea id="content" name="content" class="form-control"></textarea>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label for="descriptionInput" class="form-label">Category</label>
                        <select name="categories" id="categories" class="form-control">
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="license" class="form-label">License</label>
                    <input value="{{ old('license') }}" name="license" type="text" class="form-control" id="license" required />
                  </div>
                  
            </div>



            <h3 class="mt-5">Seo</h3>
            <hr class="mb-3">

            <div class="col">
                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">Title</label>
                    <input name="seo_title" type="text" class="form-control">
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">Description</label>
                    <input name="seo_description" type="text" class="form-control">
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">Keyphrase</label>
                    <input name="seo_keyphrase" type="text" class="form-control">
                </div>
            </div>

            <div class="text-right">
                <a href="{{ route('dashboard.pages.index', 'Fleet') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>


        </form>
    </div>

@endsection

@section('scripts')
    <script>
        $("#AddForm").validate({
            rules: {
                image_alt: {
                    required: true
                },
                title: {
                    required: true
                },
                slug: {
                    required: true
                },
                content: {
                    required: true
                },
                passengers: {
                    required: true
                },
                luggage: {
                    required: true
                },
                categories: {
                    required: true
                }

            },

            submitHandler: function(form) {
                checkSlug()
                return false;
            }
        });

        function handeImageInputChange(event) {
            const image_url = URL.createObjectURL(event.target.files[0]);
            document.getElementById('previewImage').src = image_url;
        }

        $("#previewImage").click(function() {
            $('#uploadImage').trigger('click')
        });

        function checkSlug(form) {
            let request = '{{ route('dashboard.fleetCategories.check_slug', ':slug') }}';
            let slug = document.getElementById('slug') ? document.getElementById('slug').value : Math.random();
            request = request.replace(":slug", slug)
            $.ajax({
                url: request,
                type: 'GET',
                success: (res) => {
                    let is_available = res.data.is_available;

                    if (is_available) {
                        document.getElementById('AddForm').submit();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Please check and make sure the slug is unique.'
                        })
                    }
                    return is_available;
                },
                error: (err) => {
                    return false
                }
            });
        }
    </script>
@endsection
