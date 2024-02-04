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
        <form enctype="multipart/form-data" action="{{ route('dashboard.faqs.update',$data->id) }}" id="AddForm" method="post">
            @csrf
            <h4>Create Faq</h4>
            <hr>
             <div class="mb-3">
                <label for="titleInput" class="form-label">Question</label>
                <input value="{{ $data->question }}" name="question" type="text" class="form-control" id="questionInput" />
              </div>
             <div class="mb-3">
                <label for="slugTitle" class="form-label">Answer</label>
                <input value="{{ $data->answer }}" name="answer" type="text" class="form-control" id="answerInput" />
             </div>
             <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-control">
                    <option {{ $data->type == 'General'?'selected':''  }} value="General">General</option>
                    <option {{ $data->type == 'Professional Chauffeur'?'selected':''  }} value="Professional Chauffeur">Professional Chauffeur</option>
                    <option {{ $data->type == 'Cancellations & Refunds'?'selected':''  }} value="Cancellations & Refunds">Cancellations & Refunds</option>
                </select>
             </div>
              <div class="text-right">
                <a href="{{ route("dashboard.pages.index",'Faq') }}" class="btn btn-secondary">Back</a>
                  <button type="submit" class="btn btn-primary">Update</button>
              </div>
            
    
        </form>        
    </div>    
   
@endsection

@section('scripts')
    <script>
          $("#AddForm").validate({
                rules: {
                    question: {
                        required: true
                    },
                    answer: {
                        required: true
                    },
                    type: {
                        required: true
                    },

                }
            });
            
  
       



    </script>
@endsection