@extends('dashboard.layouts.index')


@section('content')
@php
    $routeName = \Request::route()->getName();
@endphp
<style>
    .error {
        color: red !important;
    }
</style>
<div class="card p-3">
    <h3>
        Update Profile
    </h3>
    <hr>
    <form enctype="multipart/form-data" action="{{ route('dashboard.admins.update',[$data->id,1]) }}" id="AddForm" method="post">
        @csrf
     
        <div class="form-group">
            <label for="firstNameInput">First Name
                <strong class="text-danger">
                    *
                </strong>
            </label>
            <input value="{{ $data->first_name }}"  type="text" class="form-control" required name="first_name" id="firstNameInput" placeholder="add first name">
        </div>
        <div class="form-group">
            <label for="firstNameInput">Last Name  <strong class="text-danger">
                *
            </strong></label>
            <input value="{{ $data->last_name }}"  type="text" class="form-control" required name="last_name" id="lastNameInput" placeholder="add first name">
        </div>
        <div class="form-group">
            <label for="emailInput">Email address <strong class="text-danger">
                *
            </strong></label>
            <input readonly value="{{  $data->email }}" type="email" class="form-control" required name="email" name="email" placeholder="add admin email ex.name@example.com">
        </div>

        <div class="form-group">
            <label for="oldPassword">Old Password <strong class="text-danger">
                *
            </strong></label>
            <input  type="password" class="form-control"  name="oldPassword" id="oldPassword" placeholder="password">
        </div>
        <div class="form-group">
            <label for="password">Password <strong class="text-danger">
                *
            </strong></label>
            <input  type="password" class="form-control"  name="password" id="password" placeholder="password">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Password Confirm <strong class="text-danger">
                *
            </strong></label>
            <input  type="password" class="form-control"  name="password_confirmation" placeholder="confirm password"  id="password_confirmation" >
        </div>
       
    
        
        <div class="text-right">
            <button type="submit" class="btn btn-primary">
                Update
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
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    email: {
                        required: true
                    },
                    password:{
                        passwordStrength: false
                    },
                    oldPassword:{
                        required: true
                        passwordStrength: false
                    },
                    password_confirmation: {
                        minlength: 5,
                        equalTo: "#password"
                    }
                    },
                    messages: {
                    slug: {
                        required: 'Please enter a slug'
                    },
                },
                submitHandler:function(form) {
                    // checkSlug(form);
                   return true;
                }
            });

           

    </script>
@endsection