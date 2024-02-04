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
        <form enctype="multipart/form-data" action="{{ route('dashboard.faqs.store') }}" id="AddForm" method="post">
            @csrf
            <h4>Create Faq</h4>
            <hr>
             <div class="mb-3">
                <label for="titleInput" class="form-label">Question</label>
                <input name="question" type="text" class="form-control" id="questionInput" />
              </div>
             <div class="mb-3">
                <label for="slugTitle" class="form-label">Answer</label>
                <input name="answer" type="text" class="form-control" id="answerInput" />
             </div>
             <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="General">General</option>
                    <option value="Professional Chauffeur">Professional Chauffeur</option>
                    <option value="Cancellations & Refunds">Cancellations & Refunds</option>
                </select>
             </div>
              <div class="text-right">
                <a href="{{ route("dashboard.pages.index",'Faq') }}" class="btn btn-secondary">Back</a>
                  <button type="submit" class="btn btn-primary">Create</button>
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