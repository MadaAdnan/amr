<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.13.0/Sortable.min.js"></script>

<script>
        // global vars
        let currentImage = null;
        let currentImageUrl = null;
        let formData = new FormData(document.getElementById('AddForm'))
        let imagesArray = [];
        let deletedItems = [];
        let serviceId = null;

        // make a valdation for the url
        $.validator.addMethod(
            "validLink",
            function(value, element) {
                // Regular expression to validate the link
                var linkPattern =
                    /^(http:\/\/|https:\/\/)?([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5})(:[0-9]{1,5})?(\/.*)?$/i;
                return this.optional(element) || linkPattern.test(value);
            },
            "Please enter a valid link."
        );



        // show ck editor on the bootstrap modal
        $.fn.modal.Constructor.prototype.enforceFocus = function() {
            modal_this = this
            $(document).on('focusin.modal', function(e) {
                if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
                    // add whatever conditions you need here:
                    &&
                    !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select') && !$(e.target
                        .parentNode).hasClass('cke_dialog_ui_input_text')) {
                    modal_this.$element.focus()
                }
            })
        };

        //change the upload url for the ck-editor
        CKEDITOR.replace('paragraphDescription', {
            filebrowserUploadUrl: "{{ route('dashboard.upload_an_Image_ck_editor', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });

        //chnage the image when the user select a new user
        function handeImageInputChange(event) {
            const image_url = URL.createObjectURL(event.target.files[0]);
            currentImageUrl = image_url;
            document.getElementById('previewImage').src = image_url;
        }

        //trigger file when the user click on the image
        $('#paragraphImage').click(function() {
            $('#paragraphFileInput').trigger('click');
        });

        //adding a new paragraph
        function addParagragh() 
        {
            
            // check valdation for the new paragraph
            if (!addParagraghValdation()) {
                return;
            }

            //collect the input to append it to the form
            let titleInput = document.getElementById('paragraphTitle');
            let imageCaption = document.getElementById('imageCaption');
            let imageAlt = document.getElementById('imageAlt');
            let isRight = document.getElementById('right').checked ? true : false;
            let pargraphUrl = document.getElementById('urlParagraphImage');
            let paragraphFileInput = document.getElementById('paragraphFileInput');
            let descriptionInput = CKEDITOR.instances.paragraphDescription;


            let object = {
                image: currentImage,
                title: titleInput.value,
                imageUrl: pargraphUrl.value,
                description: descriptionInput.getData(),
                isRight: isRight,
                imageAlt: imageAlt,
                imageCaption: imageCaption
            }
            

            let itemId = Math.random() * 100;
            itemId = itemId.toFixed(0);

            //appended the data
            formData.append(`images[${itemId}][image]`, currentImage);
            formData.append(`images[${itemId}][urlParagraphImage]`, pargraphUrl.value);
            formData.append(`images[${itemId}][title]`, titleInput.value);
            formData.append(`images[${itemId}][description]`, descriptionInput.getData());
            formData.append(`images[${itemId}][alt]`, imageAlt.value);
            formData.append(`images[${itemId}][caption]`, imageCaption.value);
            formData.append(`images[${itemId}][isRight]`, object.isRight);
            formData.append(`images[${itemId}][sort]`, itemId);
            formData.append(`images[${itemId}][ignore]`, false)

            //get image url
            let getImageUrl = pargraphUrl.value == '' ? currentImageUrl : pargraphUrl.value;
          
            let itemDiv = `
            <div id="${itemId}" class="col-12 mt-4 draggable">
                <hr/>
                <div class="d-flex justify-content-between mb-3">
                        <h3>
                            <i class="fa fa-arrows-v h3"></i>
                            Drag
                        </h3>
                        <div>
                            <button onclick="deleteItem('${itemId}','${itemId}',true)" type="button" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                        <div class="row flex-row-reverse cp-md-margin-bottom-80 cp-margin-bottom-20">
                            ${
                                isRight?`<div class="col-md-6 col-12 wow fadeIn${isRight == false?'Left':'Right'} animated" data-wow-duration=".5s" data-wow-delay=".3s">
                                                    <div class="about-img">
                                                        <img src="${getImageUrl}" class="img-fluid" alt="${imageAlt}" loading="lazy">
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
                                        ${descriptionInput.getData()}
                                    </div>
                                </div>
                            </div>
                            ${
                                !isRight?`<div class="col-md-6 col-12 wow fadeIn${isRight == false?'Left':'Right'} animated" data-wow-duration=".5s" data-wow-delay=".3s">
                                                    <div class="about-img">
                                                        <img src="${getImageUrl}" class="img-fluid" alt="${imageAlt}" loading="lazy">
                                                        <figcaption>${imageCaption.value}</figcaption>
                                                    </div>
                                                </div>`:''
                            }
                           
                        </div>
                    </div>
            `;

            $('#paragraphsSection').append(itemDiv);

            titleInput.value = '';
            descriptionInput.setData('');
            imageAlt.value = '';
            imageCaption.value = '';
            pargraphUrl.value = '';
            paragraphFileInput.value = '';

            imagesArray.push(object);
            $('#paragraphImage').attr('src', '{{ asset('assets_new/img/upload_image_placeholder.jpg') }}').width(300);
            $('#paragraphFileInput').val('');
        }
        
        //close modal
        function closeModal(id)
        {
            const modalId = '#'+id;
            $(modalId).modal('hide');
            $(modalId).modal('hide');
        }

        //open edit modal
        function editModal(index, alt, caption, title, description, isRight) 
        {
            let object = {
                id: index,
                alt,
                caption,
                title,
                description,
                isRight
            };

            document.getElementById('edit-imageAlt').value = alt;
            document.getElementById('edit-imageCaption').value = caption
            document.getElementById('edit-paragraphTitle').value = title
            document.getElementById('edit-paragraphDescription').value = description;
            document.getElementById('edit-right').checked = isRight == 'true' ? true : false;
            document.getElementById('edit-left').checked = isRight == 'false' ? true : false;

            $('#editModal').modal('toggle');
        }

        //valdation when adding a new paragraph
        function addParagraghValdation(is_edit = false) 
        {

            let titleInput = document.getElementById('paragraphTitle');
            let imageCaption = document.getElementById('imageCaption');
            let imageAltCreate = document.getElementById('imageAlt');
            let paragraphFileInput = document.getElementById('paragraphFileInput');
            let urlParagraphImage = document.getElementById('urlParagraphImage');
            let isRight = document.getElementById('right').checked ? true : false;
            let descriptionInput = CKEDITOR.instances.paragraphDescription.getData();
          


            if (!is_edit && (paragraphFileInput.value == '' && urlParagraphImage.value == '' )) {

                Toast.fire({
                    icon: 'error',
                    title: 'Please add image'
                })

                return false;
            }

            if (imageAltCreate.value == '') {
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
            if (descriptionInput.trim() == '') {
                Toast.fire({
                    icon: 'error',
                    title: 'Please add description'
                })
                return false;
            }

            return true;

        }

        //delete item from the form 
        function deleteItem(id, index) 
        {
            Swal.fire({
                title: 'Do you want to delete this paragraph?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    let itemId = '#' + id;
                    formData.delete(`images[${index}][image]`)
                    formData.delete(`images[${index}][title]`)
                    formData.delete(`images[${index}][description]`)
                    formData.delete(`images[${index}][isRight]`)
                    formData.delete(`images[${index}][sort]`)
                    $(itemId).remove();

                    deletedItems.push(id)
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })

        }
        
        //check the slug
        function checkSlug(is_preview = false , is_edit = false) 
        {
            let request = '{{ route('dashboard.services.check_slug', ':slug') }}';

            if(serviceId)
            {
                request = '{{ route('dashboard.services.check_slug', [':slug',':id']) }}';
                request = request.replace(':id',serviceId);
            }

            const slug = document.getElementById('slug').value;
            if (slug == '') {
                Swal.fire(
                    'Wrong Valdation',
                    'Please add a slug to complete the action',
                    'error'
                )
                return;
            }
            request = request.replace(":slug", slug)
            console.log(request)
            $.ajax({
                url: request,
                type: 'GET',
                success: (res) => {

                    let is_available = res.data.is_available;
                    if (is_available) {

                        let updateUrl = '{{ route('dashboard.services.update',':id') }}';

                        if(serviceId)
                        {
                           updateUrl =  updateUrl.replace(":id" , serviceId)
                        }

                        let requestUrl = serviceId ? updateUrl : '{{ route('dashboard.services.store') }}'
                        return sendSaveRequest(is_preview,requestUrl);
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

        //make a dragable section 
        var dropZone = document.getElementById('paragraphsSection');
        var sortable = new Sortable(dropZone, {
            draggable: '.draggable',
            animation: 150,
            onEnd: function(evt) {
                var sortedItems = Array.from(dropZone.children);
                var currentItem = sortedItems[evt.newIndex];
                var itemId = currentItem.id;
                var sortNumber = Array.from(sortedItems).indexOf(currentItem) + 1;

                for (let index = 0; index < sortedItems.length; index++) {
                    const itemId = sortedItems[index].id;
                    formData.append(`images[${itemId}][sort]`, index + 1)
                }

            }
        });

        //show the user the image
        function readURL(input , is_edit = false) 
        {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var files = event.target.files;




                reader.onload = function(e) {

                    let id = 'paragraphImage';

                    //add edit input
                    if(is_edit) id ='edit-'+id;

                    $('#'+id).attr('src', e.target.result)
                        .width(300);
                    currentImageUrl = e.target.result;
                };

                currentImage = input.files[0]

                reader.readAsDataURL(input.files[0]);
            }
        }
        
        //Show/hide the image and the url input's
        document.querySelectorAll('input[name="imageTypeServiceSection"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                // Hide both input groups
                document.getElementById('fileInputGroup').style.display = 'none';
                document.getElementById('urlInputGroup').style.display = 'none';

                // Show the selected input group
                if (this.value === 'file') {
                    document.getElementById('fileInputGroup').style.display = 'block';
                } else if (this.value === 'url') {
                    document.getElementById('urlInputGroup').style.display = 'block';
                }
            });
        });

        //Show/hide the image and the url input's
        document.querySelectorAll('input[name="inputMethod"]').forEach(function(inputMethodRadio) {
            inputMethodRadio.addEventListener('change', function() {
                // Hide both containers initially
                document.getElementById('fileInputContainer').style.display = 'none';
                document.getElementById('urlInputContainer').style.display = 'none';

                // Show the selected container
                const selectedValue = document.querySelector('input[name="inputMethod"]:checked').value;
                document.getElementById(selectedValue + 'InputContainer').style.display = 'block';
            });
        });

        //Edit Radio
        //Show/hide the image and the url input's (Edit)
        document.querySelectorAll('input[name="edit-imageTypeServiceSection"]').forEach(function(radio) {
            radio.addEventListener('change', function() {

                // Hide both input groups
                document.getElementById('edit-fileInputGroup').style.display = 'none';
                document.getElementById('edit-urlInputGroup').style.display = 'none';

                // Show the selected input group
                if (this.value === 'file') {
                    document.getElementById('edit-fileInputGroup').style.display = 'block';
                } else if (this.value === 'url') {
                    document.getElementById('edit-urlInputGroup').style.display = 'block';
                }
            });
        });

        //Show/hide the image and the url input's(Edit)
        document.querySelectorAll('input[name="edit-inputMethod"]').forEach(function(inputMethodRadio) {
            inputMethodRadio.addEventListener('change', function() {
                // Hide both containers initially
                document.getElementById('edit-fileInputContainer').style.display = 'none';
                document.getElementById('edit-urlInputContainer').style.display = 'none';

                // Show the selected container
                const selectedValue = document.querySelector('input[name="edit-inputMethod"]:checked').value;
                document.getElementById(selectedValue + 'edit-InputContainer').style.display = 'block';
            });
        });

        //Send store request
        function sendSaveRequest(is_preview = false , request_url) 
        {
            var form = document.getElementById('AddForm');
            formData.append('title', document.getElementById('title').value);
            formData.append('short_title', document.getElementById('short_title').value);
            formData.append('image_url', document.getElementById('image_url').value);
            formData.append('slug', document.getElementById('slug').value);
            formData.append('city_id', document.getElementById('cityId').value);
            formData.append('description', document.getElementById('description').value);
            formData.append('imageCaption', document.getElementById('imageCaption').value);
            formData.append('seo_title', document.getElementById('seo_title').value);
            formData.append('seo_description', document.getElementById('seo_description').value);
            formData.append('seo_key_phrase', document.getElementById('seo_key_phrase').value);
            formData.append('status', document.getElementById('status').value);
            formData.append("image", document.getElementById('image').files[0]);
            formData.append("isOrphan", document.getElementById('isOrphan').checked ? 1 : 0);

            if (is_preview) {
                formData.append('is_preview', 1)
            }
            let haveImages = false;
            for (let [name, value] of formData) {
                let checkImage = name.indexOf('images') !== -1;
                if (checkImage) haveImages = true
            }

            if (!haveImages) {
                Swal.fire(
                    'Wrong Valdation',
                    'Please add paragraphes',
                    'error'
                )
                return false;
            }
            $('#loaderModal').modal('show');
            if (!inputValdation()) {
                $('#loaderModal').modal('hide');
                return false;
            }
            // Create a new FormData object

            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Set the AJAX request parameters
            xhr.open('POST', request_url, true);

            // push deleted paragraph sections
            deletedItems.forEach((item) => {
                formData.append(`deleted_paragraphs[${item}]`, item)
            });


            // Set the AJAX response type
            xhr.responseType = 'json';

            // Define the AJAX event listeners
            xhr.onload = function() {

                if (xhr.status === 200) {
                    // Request was successful
                    var response = xhr.response;
                    let url = '{{ route('dashboard.pages.index', 'Services') }}';
                    let previewRequest = '{{ route('dashboard.services.preview', ':slug') }}';
                    previewRequest = previewRequest.replace(':slug', response.data.service_slug)
                    window.location.href = response.data.is_preview ? previewRequest : url;
                    // Handle the server response here
                } else {
                    // Request failed
                    $('#loaderModal').modal('hide');
                    Toast.fire({
                        icon: 'error',
                        title: 'Please check the image sizes'
                    })
                    console.error('Error: ' + xhr.status);
                }
            };

            xhr.onerror = function() {
                // Request error
                $('#loaderModal').modal('hide');
                Toast.fire({
                    icon: 'error',
                    title: 'Please check the image sizes'
                })
                console.error('Request error');
            };

            // Send the FormData object as the payload
            xhr.send(formData);
        }

</script>