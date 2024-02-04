<style>
    label.error {
        color: #a94442;
        border-color: #ebccd1;
        padding: 0px 0px 0px 2px;
    }
    
</style>
<form id="AddForm" enctype='multipart/form-data' method="POST"  action="{{ route('dashboard.settings.store_about_page') }}" class="p-2">
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="p-5">
                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
           
        </div>
     @endif

    <h4 class="mb-4">
        About us
    </h4>
    <hr/>

    <h4 class="mt-5">
        Section 1
    </h4>
    <div class="row">
        <div class="form-group mt-3">
            <label for="inputField">Title</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_one_title }}" name="section_one_title" type="text" class="form-control">
        </div>    
        <div class="form-group mt-3">
            <label for="inputField">Description</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_one_description }}" name="section_one_description" type="text" class="form-control">
        </div>    
    </div>
    <h4 class="mt-5">
        Paragraph 1
    </h4>
    <div class="row">
        <div class="form-group mt-3">
            <label for="inputField">Title</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_one_paragraph_one_title }}" name="section_one_paragraph_one_title" type="text" class="form-control">
        </div>    
        <div class="form-group mt-3">
            <label for="inputField">Description</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_one_paragraph_one_description }}" name="section_one_paragraph_one_description" type="text" class="form-control">
        </div>    
    </div>
    <h4 class="mt-5">
        Paragraph 2
    </h4>
    <div class="row">
        <div class="form-group mt-3">
            <label for="inputField">Title</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_one_paragraph_two_title }}" name="section_one_paragraph_two_title" type="text" class="form-control">
        </div>    
        <div class="form-group mt-3">
            <label for="inputField">Description</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_one_paragraph_two_description }}" name="section_one_paragraph_two_description" type="text" class="form-control">
        </div>    
    </div>
    <hr/>
    <h4 class="mt-5">
        Section 2
    </h4>
    <div class="row">
        <div class="form-group mt-3">
            <label for="inputField">Title</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_two_title }}" name="section_two_title" type="text" class="form-control">
        </div>    
        <div class="form-group mt-3">
            <label for="inputField">Description</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_two_description }}" name="section_two_description" type="text" class="form-control">
        </div>    
    </div>
    <h4 class="mt-5">
        Paragraph 1
    </h4>
    <div class="row">
        <div class="form-group mt-3">
            <label for="inputField">Title</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_two_paragraph_one_title }}" name="section_two_paragraph_one_title" type="text" class="form-control">
        </div>    
        <div class="form-group mt-3">
            <label for="inputField">Description</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_two_paragraph_one_description }}" name="section_two_paragraph_one_description" type="text" class="form-control">
        </div>    
    </div>
    <h4 class="mt-5">
        Paragraph 2
    </h4>
    <div class="row">
        <div class="form-group mt-3">
            <label for="inputField">Title</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_two_paragraph_two_title }}" name="section_two_paragraph_two_title" type="text" class="form-control">
        </div>    
        <div class="form-group mt-3">
            <label for="inputField">Description</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_two_paragraph_two_description }}" name="section_two_paragraph_two_description" type="text" class="form-control">
        </div>    
    </div>
    <hr/>
    <h4 class="mt-5">
        Section 3
    </h4>
    <div class="row">
        <div class="form-group mt-3">
            <label for="inputField">Title</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_three_title }}" name="section_three_title" type="text" class="form-control">
        </div>    
        <div class="form-group mt-3">
            <label for="inputField">Description</label>
            <input placeholder="Please enter the title" value="{{ $pageData->section_three_description }}" name="section_three_description" type="text" class="form-control">
        </div>    
    </div>

    <hr/>
    <div class="text-right">
        <button type="submit" class="btn btn-primary">
            Update
        </button>
    </div>
</form>

@section('scripts')
<script>
    $("#AddForm").validate({
    rules: {
    section_one_title: {
        required: true
      },
      section_one_description:{
        required:true
      },
      section_one_paragraph_one_title: {
        required: true
      },
      section_one_paragraph_one_description:{
        required:true
      },
      section_one_paragraph_two_title:{
        required:true
      },
      section_one_paragraph_two_description:{
        required:true
      },
      section_two_title: {
        required: true
      },
      section_two_description:{
        required:true
      },
      section_two_paragraph_one_title: {
        required: true
      },
      section_two_paragraph_one_description:{
        required:true
      },
      section_two_paragraph_two_title:{
        required:true
      },
      section_two_paragraph_two_description:{
        required:true
      },
      section_three_title: {
        required: true
      },
      section_three_description:{
        required:true
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


    