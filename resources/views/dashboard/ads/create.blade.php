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
                <li> {{$error}}</li>
             @endforeach
         </div>
         @endif
        <form enctype="multipart/form-data" action="{{ route('dashboard.ads.store') }}" id="AddForm" method="post">
            @csrf
            <div class="text-center mb-3">
                <img id="previewImage" src="{{ asset('dashboard/assets/images/upload_img.png') }}" >
            </div>
           
              <input name="image" onchange="handeImageInputChange(event)" id="uploadImage" type="file" hidden>
              
             <div class="mb-3">
                <label for="titleInput" class="form-label">Title</label>
                <input name="title" type="text" class="form-control" id="titleInput" />
              </div>
             
              <div class="mb-3">
                <label for="content" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control"></textarea>
              </div>

              <div class="row mb-4">
                <div class="col-6">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input name="start_date" type="datetime-local" class="form-control" id="start_date" />
                  </div>
                <div class="col-6">
                    <label for="end_date" class="form-label">End Date</label>
                    <input name="end_date" type="datetime-local" class="form-control" id="end_date" />
                  </div>
              </div>
             


              <div class="text-right">
                <a href="{{ route("dashboard.ads.index") }}" class="btn btn-secondary">Back</a>
                  <button type="submit" class="btn btn-primary">Create</button>
              </div>
            
    
        </form>        
    </div>    
   
@endsection

@section('scripts')
    <script>
                   $.validator.addMethod("startDateBeforeEndDate", function(value, element) {
                var startDate = new Date($("#start_date").val());
                var endDate = new Date($("#end_date").val());
                return startDate < endDate;
            }, "Start date must be less than the end date.");

          $("#AddForm").validate({
                rules: {
                    title: {
                        required: true
                    },
                    image:{
                        required:true
                    },
                    description:{
                        required: true
                    },
                    start_date:{
                        required:true
                    },
                    end_date:{
                        required:true,
                        startDateBeforeEndDate: true
                    }
                },
                   
                submitHandler:function(form) {
                    checkSlug()
                   return false;
                }
            });
            
        function handeImageInputChange(event)
        {
            const image_url = URL.createObjectURL(event.target.files[0]);
           document.getElementById('previewImage').src = image_url;
        }
        
        $("#previewImage").click(function() {
           $('#uploadImage').trigger('click')
        });

      

    </script>
@endsection