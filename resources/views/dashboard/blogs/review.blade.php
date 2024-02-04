@extends('dashboard.layouts.index')


@section('content')
    <form enctype="multipart/form-data" action="{{ route('dashboard.blogs.release',$data->id) }}" id="AddForm" method="post">
        @csrf
            <div class="row">
                <div class="col-9">
                    @if ($data->reject_note)
                    <div>
                        <div class="alert alert-danger p-3" role="alert">
                           Reject note: {{ $data->reject_note }}
                        </div>                        
                    </div>
                    @endif
                    <div class="card p-3">
                        
                        <div class="col">
                                <div class="form-group">
                                    <h6>
                                        Title
                                    </h6>
                                    <input disabled value="{{ $data->title }}" name="title" type="text" class="form-control" placeholder="please add the blog title">
                                </div>
                        </div>
                    </div>

                    <div class="card p-3">
                        <div class="col">
                            <div class="form-group">
                                <h6>
                                    Content
                                </h6>
                                <textarea  id="content" name="content" class="form-control" id="w3review" name="w3review" rows="4" cols="50">
                                   
                                </textarea>
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



                    @if ($data->avatar)
                    <div id="imageArea" class="text-center mb-5" style="display: block;">
                        <img id="previewImage" class="mb-3 tembNail" width="600px" height="300px" src="{{ $data->avatar }}" alt="">
                        <div class="text-center">
                            <button id="changeImage" type="button" class="btn btn-gray">Change</button>
                        </div>
                    </div>

                    <div id="uploadArea" class="card" style="display:none;">
                        <div class="text-center mb-3 p-3 m-auto">
                            <i class="fa fa-upload upload-icon"></i>
                            <h6 class="text-bold mt-2">
                                Upload a Thumbnail
                            </h6>
                        </div>
                    </div>
                    <input onchange="readURL(this);" id="thembNail" name="image" type="file" hidden>

                    @else

                    <div id="imageArea" class="text-center mb-5">
                        <img id="previewImage" class="mb-3 tembNail" width="600px" height="300px" src="{{ $data->avatar }}" alt="">
                        <div class="text-center">
                            <button id="changeImage" type="button" class="btn btn-gray">Change</button>
                        </div>
                    </div>

                    <div id="uploadArea" class="card" >
                        <div class="text-center mb-3 p-3 m-auto">
                            <i class="fa fa-upload upload-icon"></i>
                            <h6 class="text-bold mt-2">
                                Upload a Thumbnail
                            </h6>
                        </div>
                    </div>
                    <input onchange="readURL(this);" id="thembNail" name="image" type="file" hidden>
                        
                    @endif
                    
                    
                    <div class="p-3">
                        
                            <div class="col-6">
                                Slug
                            </div>
                            <div class="input-group mb-2">
                              <div class="input-group-prepend">
                                <div class="input-group-text">https://blogs.lavishride.com/Articles/</div>
                              </div>
                              <input disabled value="{{ $data->slug }}" id="slug" name="slug" type="text" class="form-control" id="inlineFormInputGroup" placeholder="slug" required>
                            </div>
                          
                    </div>

                    

                        <div class="col">
                            <div class="form-group">
                                <div class="row d-flex justify-content-between">
                                    <div class="col">
                                        <h6 class="col">
                                            Keywords ( <span id="numberOfKeyWordsSelected">{{ count($selected_keywords) }}</span> /100)
                                        </h6>
                                    </div>
                                    <div class="col text-right mb-2">
                                        
                                        <input hidden type="file" name="import_keywords" id="import-keywords" >
                                    </div>
                                </div>
                                <select disabled id="keywordsSelect" multiple class="form-control" name="keywords[]">
                                    @foreach ($keywords as $item)
                                        <option {{ in_array($item->id,$selected_keywords)?'selected':'' }} value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
            
                </div>
                </div>
                <div class="col-3">
                   
                    <div class="card">
                        <div class="col d-flex">
                            <button onclick="sendPreviewRequest()" class="btn btn-secondary m-2 w-100">Preview</button>
                        </div>
                    </div>
                    <div class="card p-3">
                        <div class="col text-center d-flex">
                            <button type="button" onclick="openRejectModal()" class="btn btn-danger m-2 w-100">Reject</button>
                            <button onclick="sendSaveRequest()" type="submit" class="btn btn-primary m-2">Publish</button>
                        </div>
                    </div>

                    <div class="card p-3">
                        <div class="form-group">
                                Publish Date
                            <input value="{{ $data->date?$data->date->format('Y-m-d\TH:i:s'):'' }}" name="date" type="datetime-local" class="form-control mt-3" >
                        </div>
                    </div>

                    <div class="card p-3">
                        <div class="form-group">
                            <div class="row mb-3">
                                <div class="col-6">
                                    Category
                                </div>
                            </div>
                        
                            <select disabled name="categories[]" placeholder="Please Choose category" class="form-control select2" id="categoriesSelect" multiple>
                            </select>
                        </div>
                    </div>
                
                    <div class="card p-3">
                        <div class="form-group">
                            <div class="row mb-3">
                                <div class="col-6">
                                    Tags
                                </div>
                                <div class="col-6 text-right">
                                    <a id="TagsButton" class="add-pointer" >Add</a>
                                </div>
                            </div>
                            <select disabled multiple class="form-control tags-select" name="tags[]" >
                            </select>
                        </div>
                    </div>
                    <div class="card p-3">
                        <div class="form-group">
                            <div class="row mb-3">
                                <div class="col-6">
                                    Seo Title
                                </div>
                            </div>
                           <input disabled value="{{ $data->seo_title }}" name="seo_title" class="form-control" />
                        </div>
                    </div>

                    <div class="card p-3">
                        <div class="form-group">
                            <div class="row mb-3">
                                <div class="col-7">
                                    Seo Description
                                </div>
                            </div>
                           <textarea disabled name="seo_description" class="form-control">{{ $data->seo_description }}</textarea>
                        </div>
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


    <div id="rejectModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <p>Please add reject note</p>
                    <div class="form-group">
                        <textarea class="form-control" name="rejectNote" id="rejectNote" cols="30" rows="10"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button onclick="sendRjectNote()" type="button" class="btn btn-primary">Submit</button>
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
            const selected_tags = JSON.parse('@json($selected_tags)');
            const selected_categories = JSON.parse('@json($selected_categories)');

            CKEDITOR.replace( 'content',{
                extraPlugins: 'image',
                filebrowserUploadHeaders: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                filebrowserUploadUrl: '{{ route("dashboard.upload_an_Image_ck_editor") }}',
                filebrowserImageUploadUrl: '{{ route("dashboard.upload_an_Image_ck_editor") }}'

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
                getTheNumberOfLinks()


                function getTheNumberOfLinks()
                {
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

                }


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
                            page: params.page,
                            selected:true
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
                    },
                    complete:function(data){
                        $('.tags-select').val(selected_tags);
                        $('.tags-select').trigger('change')
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
                        console.log('resualt: ',e.target.result)
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
                   //checkSlug(form);                            $(form).submit();
                   $(form).submit();
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
                let request = '{{ route("dashboard.blogs.check_slug",":slug") }}';
                request = request.replace(":slug",document.getElementById('slug').value)
                $.ajax({
                    url:request,
                    type:'GET',
                    success:(res)=>{
                        let check = res.data?true:false;
                        if(check)
                        {
                            $(form).submit();
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

            function openRejectModal()
            {
                $('#rejectModal').modal('show')
            }

            function sendRjectNote()
            {
                const rejectNote = document.getElementById('rejectNote');
                if(rejectNote&&rejectNote.value == '')
                {
                    Toast.fire({
                        icon: 'error',
                        title: 'Please an reject note.'
                    })
                    return;
                }
                let request = '{{ route("dashboard.blogs.send_reject_note",[$data->id,"admin_reject"]) }}'
                $.ajax({
                    url:request,
                    data:{
                        '_token':'{{ csrf_token() }}',
                        'reject_note':rejectNote.value
                    },
                    type:'POST',
                    success:(res)=>{
                        location.reload()
                    },
                    error:(err)=>{
                        Toast.fire({
                            icon: 'error',
                            title: 'Server Error!'
                        })
                    }
                });
            }


            function saveDraft()
            {
                document.getElementById('AddForm').submit()
            }

              /** Get categories for the select using Ajax **/
           // const get_categories_request = '{{ route("dashboard.categories.get_categories_select") }}'
            const categorySelect = $('#categoriesSelect');
            const tagSelect = $('.tags-select');

            function getCategorySelect2()
            {
                const request = '{{ route("dashboard.blogs.get_selected_data_select2",$data->id) }}'
                $.ajax({
                    url: request,
                    type: 'GET',
                }).then(function (res) {
                    console.log(res)

                    res.data.categories.forEach((item)=>{
                        var option = new Option(item.title, item.id, true, true);
                        categorySelect.append(option).trigger('change');
                    })
                    res.data.tags.forEach((item)=>{
                        var option = new Option(item.title, item.id, true, true);
                        tagSelect.append(option).trigger('change');
                    })
                    
                    tagSelect.trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });
                });

            }

            getCategorySelect2()




        /*** Add The Content to ckEd **/
        $("#content").val(`{!! $data->content !!}`);


        function sendPreviewRequest()
        {
            document.getElementById('AddForm').action = '{{ route("dashboard.blogs.preview",$data->id) }}'
            document.getElementById('AddForm').submit();
        }
        function sendSaveRequest()
        {
            let formData = new FormData();
            formData.append('content',CKEDITOR.instances.content.getData());
            document.getElementById('AddForm').action = "{{ route('dashboard.blogs.update',[$data->id,'publish']) }}";
            document.getElementById('AddForm').submit();
        }


    </script>
@endsection