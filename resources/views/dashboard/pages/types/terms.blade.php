    <form action="{{ route('dashboard.settings.store_terms') }}" method="post">
        @csrf
        <h5 class="mb-3">
            Terms & Conditions
        </h5>
        <div class="mb-3">
            <textarea name="terms" class="form-control" id="termsInput" rows="3"></textarea>
        </div>
    
        <hr>
    
        <h5 class="mb-3">
            Privacy Policy
        </h5>
        <div class="mb-3">
            <textarea name="policy" class="form-control" id="privacyInput" rows="3"></textarea>
        </div>
    
        <div class="text-right mt-2">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
   
    {{-- @dd($pageData); --}}

    @section('scripts')
        <script>
      let termsInput =   CKEDITOR.replace('termsInput',{
                filebrowserUploadUrl: "{{route('dashboard.upload_an_Image_ck_editor', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });

    let privacyInput = CKEDITOR.replace('privacyInput',{
                filebrowserUploadUrl: "{{route('dashboard.upload_an_Image_ck_editor', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });

            termsInput.setData(`{!! $pageData->terms !!}`);
            privacyInput.setData(`{!! $pageData->policy !!}`);
        </script>
    @endsection
    
      