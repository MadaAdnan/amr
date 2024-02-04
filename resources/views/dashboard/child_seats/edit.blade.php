@extends('dashboard.layouts.index')


@section('content')

    @php
        $role = Auth::user()->roles[0]->name;
    @endphp

<style>
    .error {
        color: red !important;
    }
</style>
<div class="card p-3">
    <h3>
        Edit #{{ $seat->id }}
    </h3>
    <hr>
    <form enctype="multipart/form-data" action="{{ route('dashboard.childSeats.update',$seat->id) }}" id="AddForm" method="post">
        @csrf
     
           {{--  Title --}}
           <div class="row  flex-row">
            <div class="col-md-6">

                <div class="form-group  p-3">
                    <label for="title">Title
                        <strong class="text-danger">
                            *
                            @error('title')
                                -
                                {{ $message }}
                            @enderror
                        </strong>
                    </label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="{{ isset($seat->title)?$seat->title:'' }}">
                </div>
            </div>
            {{-- Price --}}
            <div class="col-md-6">
                <div class="form-group  p-3">
                    <label for="price"> Price
                        <strong class="text-danger">
                            *
                            @error('price')
                                -
                                {{ $message }}
                            @enderror
                        </strong>
                    </label>
                    <input type="number" id="price" class="form-control" name="price"
                        value="{{ isset($seat->price)?$seat->price:1 }}" min="1">
                </div>
                </div>

            {{-- Status --}}
            <div class="row  flex-row">

            <div class="col-md-6">
                <div class="form-group  p-3">
                    <label for="status"> Status
                        <strong class="text-danger">
                            *
                            @error('status')
                                -
                                {{ $message }}
                            @enderror
                        </strong>
                    </label>
                    <select id="status" class="form-select" name='status'>
                        <option value="">Select</option>
                        <option value="Published" @if($seat->status == 'Published') selected @endif>Publish</option>
                        <option value="Disabled" @if($seat->status == 'Disabled') selected @endif>Disable</option>
                    </select>
                </div>
            </div>
        </div>

        </div>


        


        <div class="col-md-12">
            <div class="form-group  p-3">
                <label for="description">Discription
                    <strong class="text-danger">
                        *
                        @error('description')
                            -
                            {{ $message }}
                        @enderror
                    </strong>
                </label>
                <textarea id="description" name="description" class="form-control" id="w3review" name="w3review" rows="4"
                    cols="50">{!! isset($seat->description)?$seat->description:'' !!}</textarea>


            </div>
        </div>



        @if ($role != 'Seo-admin')
            <div class="text-right">
                <button onclick="sendRequest()" type="button" class="btn btn-primary">
                    Update
                </button>
                
            </div>
            @else
            <div class="w-100 text-right">
                <a href="{{ route('dashboard.childSeats.index') }}" type="button" class="btn btn-primary">
                    Back
                </a>
            </div>
        @endif
    </form>

</div>
@endsection

@section('scripts')
    <script>

      

            function sendRequest()
            {
                const form = document.getElementById('#AddForm');
               
                
                    $('form').attr('action', '{{ route("dashboard.childSeats.update",$seat->id) }}');

                
                $('#AddForm').submit();

            }

    </script>
    <script>
        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
            // separateDialCode:true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.0/build/js/utils.js",
        });
    
        // store the instance variable so we can access it in the console e.g. window.iti.getNumber()
        window.iti = iti;
    </script>
   
@endsection