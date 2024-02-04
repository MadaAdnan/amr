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
            Edit #{{ $data->id }}
        </h3>
        <hr>
        <form enctype="multipart/form-data" action="{{ route('dashboard.admins.update', $data->id) }}" id="AddForm"
            method="post">
            @csrf

            <div class="form-group">
                <label for="firstNameInput">First Name</label>
                <input {{ $role == 'Seo-admin' ? 'disabled' : '' }} value="{{ $data->first_name }}" type="text"
                    class="form-control" required name="first_name" id="firstNameInput" placeholder="add first name">
            </div>
            <div class="form-group">
                <label for="firstNameInput">Last Name</label>
                <input {{ $role == 'Seo-admin' ? 'disabled' : '' }} value="{{ $data->last_name }}" type="text"
                    class="form-control" required name="last_name" id="lastNameInput" placeholder="add first name">
            </div>
            <div class="form-group">
                <label for="emailInput">Email address</label>
                <input  value="{{ $data->email }}" type="email" class="form-control" required name="email"
                    name="email" placeholder="add admin email ex.name@example.com" readonly>
            </div>

            <div class="form-group">
                <label for="rolesSelect">Roles</label>
                <select {{ $role == 'Seo-admin' ? 'disabled' : '' }} required name="role_id" class="form-control"
                    id="rolesSelect">
                    @foreach ($roles as $item)
                        <option {{ $item->id == $data->roles[0]->id ? 'selected' : '' }} value="{{ $item->id }}">
                            {{ $item->name }}</option>
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
                    value="{{ isset($data->phone) ? $data->phone : '' }}">
            </div>
            @if ($role != 'Seo-admin')
                <div class="text-right">
                    <button onclick="sendRequest()" type="button" class="btn btn-primary">
                        Update
                    </button>
                    <button onclick="sendRequest(true)" type="button" class="btn btn-danger">
                        Reset Password
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
        $.validator.addMethod("passwordStrength", function(value, element) {
            var result = zxcvbn(value);
            return result.score >= 4;
        }, "Please enter a stronger password.");

        $("#AddForm").validate({
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                email: {
                    required: true
                },
                password: {
                    passwordStrength: false
                },
                password_confirm: {
                    minlength: 5,
                    equalTo: "#password"
                }
            },
            messages: {
                slug: {
                    required: 'Please enter a slug'
                },
            },
            submitHandler: function(form) {
                // checkSlug(form);
                return true;
            }
        });

        function sendRequest(is_change_password = false) {
            const form = document.getElementById('#AddForm');
            if (is_change_password) {
                $('form').attr('action', '{{ route('dashboard.admins.generatePassword', $data->id) }}');

                $('#AddForm').submit();
            } else {
                $('form').attr('action', '{{ route('dashboard.admins.update', $data->id) }}');

            }
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
