<style>
label.error {
    color: #a94442;
    border-color: #ebccd1;
    padding: 0px 0px 0px 2px;
}

</style>
<form id="AddForm" enctype='multipart/form-data' method="POST"  action="{{ route('dashboard.settings.store_home_page') }}" class="p-2">
  @csrf
  <h4 class="mb-4">
   Slider
  </h4>
  <hr/>
  <div class="row">
    <div class="mb-5">
      <h6>
        Image #1
      </h6>
      <div class="text-center">
       
        <img width="300px" src="{{ $settingModal->sliderOne }}" alt="Thumbnail" onerror="this.onerror=null; this.src='https://placehold.co/600x400'; this.alt='Image failed to load';">
      </div>
      <div class="mt-3">
        <input name="image_one" type="file" class="form-control">
        <input value="{{ $pageData->alt_image_one }}" placeholder="please add the alt" class="form-control" id="alt_image_one" name="alt_image_one" type="text" class="mt-2">
      </div>
      <div class="form-group mt-3">
        <label for="inputField">Title</label>
        <input placeholder="Please enter the title" value="{{ $pageData->image_one_title }}" name="image_one_title" type="text" class="form-control">
      </div>
      <div class="form-group mt-3">
        <label for="inputField">Description</label>
        <textarea placeholder="Please enter the description" value="{{ $pageData->image_one_description }}" name="image_one_description" class="form-control">{{ $pageData->image_two_description }}</textarea>
      </div>
    </div>
    <div class="mb-3">
      <h6>
        Image
      </h6>
      <div class="text-center">
        <img width="300px" src="{{ $settingModal->sliderTwo }}" alt="Thumbnail" onerror="this.onerror=null; this.src='https://placehold.co/600x400'; this.alt='Image failed to load';">
      </div>
      <div class="mt-3">
        <input name="image_two" type="file" class="form-control">
        {{-- <input value="{{ $pageData->alt_image_two }}" placeholder="please add the alt" class="form-control" id="alt_image_two" name="alt_image_two" type="text" class="mt-2"> --}}
      </div>
      <div class="form-group mt-3">
        <label for="inputField">Title</label>
        <input placeholder="Please enter the title" value="{{ $pageData->image_two_title }}" name="image_two_title" type="text" class="form-control">
      </div>
      <div class="form-group mt-3">
        <label for="inputField">Description</label>
        <textarea placeholder="Please enter the description" value="{{ $pageData->image_two_description }}" name="image_two_description" class="form-control">{{ $pageData->image_two_description }}</textarea>
      </div>
  
  
    </div>
    <h4 class="mt-4">
      About Us
    </h4>
    <hr/>
    <div class="form-group">
      <label for="inputField">Title</label>
      <input placeholder="Please enter the title" value="{{ $pageData->title_about_us }}" name="title_about_us" type="text" class="form-control">
    </div>
    <div class="form-group">
      <label for="inputField">Description</label>
      <textarea placeholder="Please enter the description" name="description_about_us" class="form-control">{{ $pageData->description_about_us }}</textarea>
    </div>

    <h4 class="mt-4">
      Our Services
    </h4>
    <hr/>
    <div class="form-group">
      <label for="inputField">Title</label>
      <input value="{{ $pageData->title_our_services }}" placeholder="Please enter the title" name="title_our_services" type="text" class="form-control">
    </div>
    <div class="form-group">
      <label for="inputField">Description</label>
      <textarea placeholder="Please enter the description" name="description_our_services" class="form-control">{{ $pageData->description_our_services }}</textarea>
    </div>

    <h4 class="mt-4">
      What Makes US A Lavish Ride
    </h4>
    <hr/>
    <div class="form-group">
      <label for="inputField">Title</label>
      <input value="{{ $pageData->what_makes_us_title }}" type="text" placeholder="please add the title" class="form-control" name="what_makes_us_title">
    </div>
    <div class="form-group">
      <label for="inputField">Description</label>
     <textarea class="form-control" placeholder="please add the description" name="what_makes_us_description" id="" cols="30" rows="10">{{ $pageData->what_makes_us_description }}</textarea>
    </div>
    {{-- <div class="form-group">
      <label for="inputField">Icon 1</label>
      <input placeholder="Please enter the title" name="icon_one" type="file" class="form-control">
    </div> --}}
    <div class="form-group">
      <label for="inputField">Icon 1 Title</label>
      <input value="{{ $pageData->icon_what_makes_us_one_title }}" placeholder="Please enter the title" name="icon_what_makes_us_one_title" type="text" class="form-control">
    </div>
    <div class="form-group">
      <label for="inputField">Icon 1 Description</label>
      <textarea placeholder="Please enter the description" name="icon_what_makes_us_one_description" class="form-control">{{ $pageData->icon_what_makes_us_one_description }}</textarea>
    </div>
   
    <div class="form-group">
      <label for="inputField">Icon 2 Title</label>
      <input value="{{ $pageData->icon_what_makes_us_two_title }}" placeholder="Please enter the title" name="icon_what_makes_us_two_title" type="text" class="form-control">
    </div>
    <div class="form-group">
      <label for="inputField">Icon 2 Description</label>
      <textarea placeholder="Please enter the description" name="icon_what_makes_us_two_description" class="form-control">{{ $pageData->icon_what_makes_us_two_description }}</textarea>
    </div>
    
    <div class="form-group">
      <label for="inputField">Icon 3 Title</label>
      <input value="{{ $pageData->icon_what_makes_us_three_title }}" placeholder="Please enter the title" name="icon_what_makes_us_three_title" type="text" class="form-control">
    </div>
    <div class="form-group">
      <label for="inputField">Icon 3 Description</label>
      <textarea placeholder="Please enter the description" name="icon_what_makes_us_three_description" class="form-control">{{ $pageData->icon_what_makes_us_three_description }}</textarea>
    </div>
   
    <div class="form-group">
      <label for="inputField">Icon 4 Title</label>
      <input value="{{ $pageData->icon_what_makes_us_four_title }}" placeholder="Please enter the title" name="icon_what_makes_us_four_title" type="text" class="form-control">
    </div>
    <div class="form-group">
      <label for="inputField">Icon 4 Description</label>
      <textarea placeholder="Please enter the description" name="icon_what_makes_us_four_description" class="form-control">{{ $pageData->icon_what_makes_us_four_description }}</textarea>
    </div>
   
    <div class="form-group">
      <label for="inputField">Icon 5 Title</label>
      <input value="{{ $pageData->icon_what_makes_us_five_title }}" placeholder="Please enter the title" name="icon_what_makes_us_five_title" type="text" class="form-control">
    </div>
    <div class="form-group">
      <label for="inputField">Icon 5 Description</label>
      <textarea placeholder="Please enter the description" name="icon_what_makes_us_five_description" class="form-control">{{ $pageData->icon_what_makes_us_five_description }}</textarea>
    </div>
    
    <div class="form-group">
      <label for="inputField">Icon 6 Title</label>
      <input value="{{ $pageData->icon_what_makes_us_six_title }}" placeholder="Please enter the title" name="icon_what_makes_us_six_title" type="text" class="form-control">
    </div>
    <div class="form-group">
      <label for="inputField">Icon 6 Description</label>
      <textarea placeholder="Please enter the description" name="icon_what_makes_us_six_description" class="form-control">{{ $pageData->icon_what_makes_us_six_description }}</textarea>
    </div>
        
  </div>
  <div class="text-right mt-5">
      <button type="submit" class="btn btn-primary">Update</button>
  </div>

  <hr/>
     
  </form>

  <script>
        function handleImageError(image) {
      console.log(image)
      image.src = 'https://placehold.co/600x400';
      image.alt = 'Image failed to load';
    }

  </script>

  @section('scripts')
  <script>
    $("#AddForm").validate({
        rules: {
          title_about_us: {
            required: true
          },
          description_about_us:{
            required:true
          },
          title_our_services: {
            required: true
          },
          title_our_services:{
            required:true
          },
          description_our_services:{
            required:true
          },
          alt_image_one:{
            required:true
          },
          alt_image_two:{
            required:false
          },

        },
        messages: {
          title_about_us: {
              required: 'Please enter a title for the about us'
            }
        }
    });



  </script>
  @endsection
 