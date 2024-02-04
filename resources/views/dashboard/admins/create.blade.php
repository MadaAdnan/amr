@extends('dashboard.layouts.index')


@section('content')
<style>
    .error {
        color: red !important;
    }
</style>
    <div class="card p-3">
        <h3>
            Create
        </h3>
        <hr>
        <form enctype="multipart/form-data" action="{{ route('dashboard.admins.store') }}" id="AddForm" method="post">
            @csrf
            <div class="mb-3">
                @if($errors->any())
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                @endif
            </div>

            <div class="form-group">
                <label for="firstNameInput">First Name</label>
                <input type="text" class="form-control"  name="first_name" id="firstNameInput" placeholder="add first name">
            </div>
            <div class="form-group">
                <label for="firstNameInput">Last Name</label>
                <input type="text" class="form-control"  name="last_name" id="lastNameInput" placeholder="add first name">
            </div>
            <div class="form-group">
                <label for="emailInput">Email address</label>
                <input type="email" class="form-control" required name="email" placeholder="add admin email ex.name@example.com">
            </div>
            <div class="form-group">
                <label for="rolesSelect">Roles</label>
                <select required name="role_id" class="form-control" id="rolesSelect">
                  @foreach ($roles as $item)
                      <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                    value="{{ old('phone') }}">
            </div>
            
            <div class="text-right">
                <button type="submit" class="btn btn-primary">
                    Create
                </button>
            </div>
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
                    email: {
                        required: true
                    },
                },
                messages: {
                    slug: {
                        required: 'Please enter a slug'
                    },
                },
                submitHandler:function(form) {
                    checkSlug(form);
                   return false;
                }
            });

        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.0/build/js/utils.js",
        });
        window.iti = iti;

    </script>
@endsection