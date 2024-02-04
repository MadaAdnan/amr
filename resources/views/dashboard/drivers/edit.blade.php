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
        Edit #{{ $driver->id }}
    </h3>
    <hr>
    <form enctype="multipart/form-data" action="{{ route('dashboard.drivers.update',$driver->id) }}" id="AddForm" method="post">
        @csrf
     
        <div class="form-group">
            <label for="firstNameInput">First Name</label>
            <input {{ $role == 'Seo-admin'?'disabled':'' }} value="{{ $driver->first_name }}"  type="text" class="form-control" required name="first_name" id="firstNameInput" placeholder="add first name">
        </div>
        <div class="form-group">
            <label for="firstNameInput">Last Name</label>
            <input {{ $role == 'Seo-admin'?'disabled':'' }} value="{{ $driver->last_name }}"  type="text" class="form-control" required name="last_name" id="lastNameInput" placeholder="add first name">
        </div>
        <div class="form-group">
            <label for="emailInput">Email address</label>
            <input disabled value="{{  $driver->email }}" type="email" class="form-control" required name="email" name="email" placeholder="add admin email ex.name@example.com">
        </div>
       
       
        <div class="form-group">
            <label for="rolesSelect">Roles</label>
            <select {{ $role == 'Seo-admin'?'disabled':'' }} required name="role_id" class="form-control" id="rolesSelect">
              @foreach ($roles as $item)
                  <option  {{ $item->id == $driver->roles[0]->id?'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
              @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="phone"> Phone
                <strong class="text-danger">
                    *
                    @error('phone')
                        -
                        {{ $message }}
                    @enderror
                </strong>
            </label>
            <input type="tel" id="phone" class="form-control" name="phone"
                value="{{ isset($driver->phone)? $driver->phone : ''}}">
        </div>
        @if ($role != 'Seo-admin')
            <div class="text-right">
                <button onclick="sendRequest()" type="button" class="btn btn-primary">
                    Update
                </button>
                
            </div>
            @else
            <div class="w-100 text-right">
                <a href="{{ route('dashboard.admins.index') }}" type="button" class="btn btn-primary">
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
               
                
                    $('form').attr('action', '{{ route("dashboard.drivers.update",$driver->id) }}');

                
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