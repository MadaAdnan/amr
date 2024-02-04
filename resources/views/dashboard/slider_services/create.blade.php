@extends('dashboard.layouts.index')


@section('content')
<style>
    input#flexSwitchCheckDefault {
    width: 50px;
    height: 25px;
}
</style>
    <form enctype="multipart/form-data" action="{{ route('dashboard.sliderServices.store') }}" id="AddForm" method="post">
        @csrf
         <div class="card p-3">
            <div class="container">
                <h3 class="text-bold">Create Services Slider</h3>
                <hr/>
                <div class="text-center align-items-center w-100 mb-3">
                    <img id="previewImage" width="400px" src="{{ asset('assets_new/img/upload_image_placeholder.jpg') }}" alt="">
                </div>
                    <div class="mb-3">
                    <label for="title" class="form-label">Image</label>
                    <input name="image" type="file" onchange="handeImageInputChange(event)" class="form-control" id="image" placeholder="the slider title">
                  </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input name="title" type="text" class="form-control" id="title" placeholder="the slider title">
                  </div>
                  <div class="mb-3">
                    <label for="caption" class="form-label">Description</label>
                    <input name="caption" type="text" class="form-control" id="caption" placeholder="Enter your Description">
                  </div>
                  <div class="mb-3">
                    <label for="alt" class="form-label">Alt</label>
                    <input name="alt" type="text" class="form-control" id="alt" placeholder="Enter your alt">
                  </div>
                  <div class="mb-3">
                    <label for="link" class="form-label">Link</label>
                    <input name="link" type="link" class="form-control" id="link" placeholder="Enter your link">
                  </div>
                  <div class="text-right">
                      <button type="submit" class="btn btn-primary">Create</button>
                  </div>
              
              
         </div>
    </form>


   
   

@endsection

@section('scripts')
    <script>
          
        function handeImageInputChange(event)
        {
            const image_url = URL.createObjectURL(event.target.files[0]);
           document.getElementById('previewImage').src = image_url;
        }

        $.validator.addMethod(
        "validLink",
        function(value, element) {
          // Regular expression to validate the link
          var linkPattern = /^(http:\/\/|https:\/\/)?([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5})(:[0-9]{1,5})?(\/.*)?$/i;
          return this.optional(element) || linkPattern.test(value);
        },
        "Please enter a valid link."
      );


        $("#AddForm").validate({
                rules: {
                    image: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    link: {
                        required: true,
                        validLink: true
                    },
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

 

    </script>
@endsection