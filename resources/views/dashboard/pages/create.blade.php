@extends('dashboard.layouts.index')


@section('content')
    <form enctype="multipart/form-data" action="{{ route('dashboard.pages.store') }}" id="AddForm" method="post">
        @csrf
            <div class="row">

                <div class="col-9">
                    <div id="imageArea" class="text-center mb-5">
                        <img id="previewImage" class="mb-3 tembNail" width="600px" height="300px" src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="">
                        <div class="text-center">
                            <button id="changeImage" type="button" class="btn btn-gray">Change</button>
                        </div>
                    </div>
                    <div id="uploadArea" class="card">
                        <div class="text-center mb-3 p-3 m-auto">
                            <i class="fa fa-upload upload-icon"></i>
                            <h6 class="text-bold mt-2">
                                Upload a Thumbnail
                            </h6>
                        </div>
                    </div>
                    <input onchange="readURL(this);" id="thembNail" name="image" type="file" hidden>
                    <div class="card p-3">
                        <div class="col">
                                <div class="form-group">
                                    <h6>
                                        Title
                                    </h6>
                                    <input name="title" type="text" class="form-control" placeholder="please add the blog title">
                                </div>
                        </div>
                    </div>
                    
                    <div class="card p-3">
                        <div class="col">
                            <div class="form-group">
                                <h6>
                                    Content
                                </h6>
                                <textarea id="content" name="content" class="form-control" id="w3review" name="w3review" rows="4" cols="50"></textarea>
                                <div class="">
                                  <div class="mt-4">
                                      <div class="row justify-content-center">
                                          <div class="col-5"><div class="alert alert-success p-2" role="alert">External Links: <span id="numberOfExterinalLinks-change">0</span></div></div>
                                          <div class="col-5"><div class="alert alert-danger p-2" role="alert">Internal Links: <span id="numberOfInternalLinks-change">0</span></div></div>
                                          <div class="col-5"><div class="alert alert-warning p-2" role="alert">Characters: <span id="numberOfCharacters-change">0</span></div></div>
                                          <div class="col-5"><div class="alert alert-info p-2" role="alert">Words: <span id="numberOfWords-change">0</span></div></div>
                                      </div>
                                  </div>
                                </div>
                            </div>
                    </div>

                    <div class="card p-3">
                        
                        <div class="col-6">
                            Slug
                        </div>
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                            <div class="input-group-text">https://lavishride.com/</div>
                          </div>
                          <input id="slug" name="slug" type="text" class="form-control" id="inlineFormInputGroup" placeholder="slug" required>
                        </div>
                      
                </div>

                        <div class="col">
                            <div class="form-group">
                                <div class="row d-flex justify-content-between">
                                    <div class="col">
                                        <h6 class="col">
                                            Keywords ( <span id="numberOfKeyWordsSelected">0</span> /100)
                                        </h6>
                                    </div>
                                    <div class="col text-right mb-2">

                                    </div>
                                </div>
                                <select id="keywordsSelect" multiple class="form-control" name="keywords[]">
                                    @foreach ($keywords as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
            
                </div>
                </div>
                <div class="col-3">


                    <div class="card p-3">
                        <button  type="button" onclick="publish()" class="btn btn-success">
                            Submit
                        </button>
                    </div>

                    <div class="card p-3">
                        <div class="col text-center d-flex">
                            <button onclick="sendPreviewRequest()" disabled type="button" class="btn btn-secondary m-2">Preview</button>
                            <button onclick="sendSaveRequest()" type="button" class="btn btn-primary m-2">Save</button>
                        </div>
                    </div>

                    <div class="card p-3">
                        <div class="form-group">
                            <div class="row mb-3">
                                <div class="col-6">
                                    Seo Title
                                </div>
                            </div>
                            <input maxlength="70" onkeydown="countWord(event,'maxCharSeoTitle')" class="form-control" name="seo_title" />
                        </div>
                        <p>Max Characters <span id="maxCharSeoTitle">0</span>/70</p>
                    </div>

                    <div class="card p-3">
                        <div class="form-group">
                            <div class="row mb-3">
                                <div class="col-7">
                                    Seo Description
                                </div>
                            </div>
                            <textarea maxlength="150" onkeydown="countWord(event,'maxCharSeoDescription')" id="seo_description" name="seo_description"  class="form-control"></textarea>
                        </div>
                        <p>Max Characters <span id="maxCharSeoDescription">0</span>/150</p>
                    </div>

                    <div class="card p-3">
                        <div class="form-group">
                            <div class="row mb-3">
                                <div class="col-7">
                                    Section
                                </div>
                            </div>
                        </div>
                        <select id="navPageSelect" class="form-control select2" name="navPage">
                            @foreach ($navPages as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>


                    </div>



                </div>

            </div>
    </form>


    <div id="createTagModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add New Tag</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="titleInput">Title</label>
                    <input type="text" class="form-control" id="titleInputTag" placeholder="Enter title">
                </div>
            </div>
            <div class="modal-footer">
              <button id="actionButton" onclick="createTagRequest()" type="button" class="btn btn-primary create-hover">Create</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>

    <div id="createCategorieModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add New Category</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="titleInput">Title</label>
                    <input type="text" class="form-control" id="titleInputCategory" placeholder="Enter title">
                </div>
                <div class="form-group">
                    <label for="slugInput">Slug</label>
                    <p>https:{{ env('APP_URL') }}/SSS</p>
                    <input type="text" class="form-control" id="slugInputCategory" placeholder="Enter slug">
                    
                </div>
            </div>
            <div class="modal-footer">
              <button id="actionButton" onclick="createCategoryRequest()" type="button" class="btn btn-primary create-hover">Create</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
            const titleInputTag = document.getElementById('titleInputTag');
            const slugInputTag = document.getElementById('slugInputTag');
            const titleInputCategory = document.getElementById('titleInputCategory');
            const slugInputCategory = document.getElementById('slugInputCategory');

            CKEDITOR.replace( 'content',{
                filebrowserUploadUrl: "{{route('dashboard.upload_an_Image_ck_editor', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'

            });

          
            CKEDITOR.on( 'instanceReady', function( evt )
            {
                var editor = evt.editor;
            
            editor.on('change', function (e) { 
                    var text =  editor.editable().getText();
                    var data =  editor.editable().getData();
                    let numberOfCharacters = text.length;
                    var strippedContent = text.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ');
                    var numberOfWords = strippedContent.split(' ').length;



                    var regex = /<a\s+(?:[^>]*?\s+)?href=(["'])(.*?)\1/g;
                    var links = [];
                    var match;
                    var lavaShregex = /\blavishride\b/;
                    let externalLinks = []
                    let internalLinks = []
                    
                    while ((match = regex.exec(data)) !== null) {
                        links.push(match[2]);
                    }

                    links.forEach(element => {
                        if(lavaShregex.test(element))
                        {
                            if(!internalLinks.includes(element))
                            {
                                internalLinks.push(element);
                            }
                        }
                        else
                        {
                            if(!externalLinks.includes(element))
                            {
                                externalLinks.push(element);
                            }
                        }
                    });



                    $('#numberOfCharacters-change').empty();
                    $('#numberOfWords-change').empty();
                    $('#numberOfExterinalLinks-change').empty();
                    $('#numberOfInternalLinks-change').empty();

                    $('#numberOfCharacters-change').append(numberOfCharacters);
                    $('#numberOfWords-change').append(numberOfWords);
                    $('#numberOfExterinalLinks-change').append(externalLinks.length);
                    $('#numberOfInternalLinks-change').append(internalLinks.length);


                });
            });


            /** Get tags for the selecte using Ajax **/
            const request = '{{ route("dashboard.keywords.get_tags_select") }}'
            $('.tags-select').select2({
                ajax: {
                    url:request ,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (res, params) {
                        params.page = params.page || 1;
                        return {
                            results: res.data,
                            pagination: {
                                more: (params.page * 30) < res.data.total_count
                            }
                        };
                    }
                }
            });

            /** Open Modal **/
            $('#TagsButton').click(function(){
                $('#createTagModal').modal('toggle')
            });

            /** Create Tag */
            function createTagRequest()
            {
                    const url = '{{ route("dashboard.keywords.store") }}';
                    let data = {
                        'title':titleInputTag.value,
                        '_token':'{{ csrf_token() }}'
                    }
                    $.ajax({
                        url,
                        data,
                        type:"POST",
                        success:(res)=>{
                            var newOption = new Option(data.text, data.id, true, true);
                            $('#mySelect2').append(newOption).trigger('change');
                            Toast.fire({
                                icon: 'success',
                                title: 'Data was added'
                            })
                            titleInputTag.value = '';
                            slugInputTag.value = '';
                        },
                        error:(err_res)=>{
                            Toast.fire({
                                icon: 'error',
                                title: err_res.responseJSON.err
                            })
                        }
                    })
            }

            /** Get categories for the select using Ajax **/
            const get_categories_request = '{{ route("dashboard.categories.get_categories_select") }}'
            $('#categoriesSelect').select2({
                ajax:{
                    url:get_categories_request,
                    dataType:'json',
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (res, params) {
                        params.page = params.page || 1;
                        return {
                            results: res.data,
                            pagination: {
                                more: (params.page * 30) < res.data.total_count
                            }
                        };
                    }
                }
            })

            /** Open Modal **/
            $('#addCategoreyButton').click(function(){
                $('#createCategorieModal').modal('toggle')
            });
            /**Create category **/
                function createCategoryRequest()
            {
                const url = '{{ route("dashboard.categories.store") }}';
                    let data = {
                        'title':titleInputCategory.value,
                        'slug':slugInputCategory.value,
                        '_token':'{{ csrf_token() }}'
                    }
                    $.ajax({
                        url,
                        data,
                        type:"POST",
                        success:(res)=>{
                            var newOption = new Option(data.text, data.id, true, true);
                            $('#mySelect2').append(newOption).trigger('change');
                            Toast.fire({
                                icon: 'success',
                                title: 'Data was added'
                            })
                            titleInputCategory.value = '';
                            slugInputCategory.value = '';
                        },
                        error:(err_res)=>{
                            Toast.fire({
                                icon: 'error',
                                title: err_res.responseJSON.err
                            })
                        }
                    })
            }

            $('#uploadArea').click(function(){
                $('#thembNail').click();
            });

            $('#changeImage').click(function(){
                $('#thembNail').click();
            });

            function readURL(input) {
                $("#uploadArea").css("display",'none');
                $("#imageArea").css("display",'block');
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                    $('#previewImage').attr('src', e.target.result).width(600).height(300);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
            /** Keyword **/
            $('#keywordsSelect').select2();
            
            /** Form Valdation **/
            $("#AddForm").validate({
                rules: {
                    slug: {
                        required: true
                    }
                    },
                    messages: {
                    slug: {
                        required: 'Please enter a slug'
                    }
                },
                submitHandler:function(form) {
                    checkSlug(form);
                   return false;
                }
            });

            $('#keywordsSelect').select2({
                maximumSelectionLength: 100
            })

            $('#keywordsSelect').change(function(){
                const selectElement = document.getElementById('keywordsSelect');
                const selectedOptions = selectElement.selectedOptions;
                const numberOfSelectedOptions = selectedOptions.length;
                $('#numberOfKeyWordsSelected').empty();
                $('#numberOfKeyWordsSelected').append(numberOfSelectedOptions);

            });


            function checkSlug(form)
            {
                let request = '{{ route("dashboard.pages.check_slug",":slug") }}';
                request = request.replace(":slug",document.getElementById('slug').value)
                $.ajax({
                    url:request,
                    type:'GET',
                    success:(res)=>{
                        let check = res.data?true:false;
                        if(!check)
                        {
                            document.getElementById('AddForm').submit();
                        }
                        else
                        {
                            Toast.fire({
                                icon: 'error',
                                title: 'Please check and make sure the slug is unique.'
                            })
                        }
                       return;
                    },
                    error:(err)=>{
                       return false
                    }
                });
            }


            function countWord(e,char)
            {
                let value = e.target.value.length;
                $('#'+char).empty();
                $('#'+char).append(value);
            }
                        
            $('#navPageSelect').select2();



            function publish()
            {
                const request = '{{ route("dashboard.pages.store_with_publish") }}';
                const slugValue = document.getElementById('slug').value;
                if(slugValue == '')
                {
                    Swal.fire(
                    'Wrong Data Input',
                    'Please at least add the slug to publish the page',
                    'error'
                    )
                    return;
                }

                const sendRequestPromise = new Promise((resolve, reject) => {
                  
                    let request = '{{ route("dashboard.pages.check_slug",":slug") }}';
                    request = request.replace(":slug",document.getElementById('slug').value)
                    $.ajax({
                        url:request,
                        type:'GET',
                        success:(res)=>{
                            let check = res.data?true:false;
                            if(!check)
                            {
                               resolve()
                            }
                            else
                            {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Please check and make sure the slug is unique.'
                                })
                                reject()
                            }
                        return check;
                        },
                        error:(err)=>{
                        return false
                        }
                    });


                })
                .then(()=>{
                    const form = document.getElementById('AddForm');
                    const formData = new FormData(form);
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', request, true);
                    xhr.onload = function(){
                        window.location.href = '{{ route("dashboard.pages.index","Home") }}';
                    };
                    xhr.onerror = function() {
                        console.error('Request error.');
                    };
                    // Send the request
                    xhr.send(formData);
                },()=>{

                });
            }


    function sendPreviewRequest()
    {
        document.getElementById('AddForm').action = '{{ route("dashboard.pages.preview") }}'
        $('#AddForm').submit();
    }
    function sendSaveRequest()
    {
        document.getElementById('AddForm').action = "{{ route('dashboard.pages.store') }}";
        $('#AddForm').submit();
    }



    </script>
@endsection