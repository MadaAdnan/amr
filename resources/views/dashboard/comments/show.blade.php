@extends('dashboard.layouts.index')


@section('content')
<div class="card" style="height: 100vh !importent">
   <div class="p-2">
    <h3>
        
    </h3>
   </div>
    <iframe style="width:100%;height:100vh;" src="{{ route('dashboard.comments.preview',$data->id) }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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

    </script>
@endsection