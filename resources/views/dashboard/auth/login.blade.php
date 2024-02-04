@extends('dashboard.auth.layout.main')



@section('main')

<style>
    #title{
        font-size: 1.35rem !important;
    }
</style>

<div class="row">
    <div class="col-md-5 col-sm-12 mx-auto">
        <div class="card pt-4">
            <div class="card-body">
                <div class="text-center mb-5">
                    <img src="{{ asset('assets_new/img/lavishride_original_logo.webp') }}" height="80" class='mb-4'>
                    <h1 id="title">Sign In</h1>
                    <p>Please sign in to continue to Lavish Ride.</p>
                       @if ($errors->any())
                       <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $error)
                                {{$error}}
                            @endforeach
                        </div>
                        @endif
                </div>
                <form method="POST" action="{{ route('login_submit') }}">
                    @csrf
                    <div class="form-group position-relative has-icon-left">
                        <label for="username">Email</label>
                        <div class="position-relative">
                            <input name="email" type="email" class="form-control" id="username" placeholder="please add your email">
                            <div class="form-control-icon">
                                <i data-feather="user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-right">
                        <div class="clearfix">
                            <label for="password">Password</label>
                        </div>
                        <div class="position-relative">
                            <div id="togglePassword" class="form-control-icon">
                                <i class="fa fa-eye" ></i>
                            </div>
                            <input name="password" type="password" class="form-control" id="password" placeholder="please enter your password">
                        </div>
                        
                    </div>
                      

                    <div class='form-check clearfix my-4'>
                        <div class="checkbox float-left">
                            <input type="checkbox" id="checkbox1" class='form-check-input' >
                            <label for="checkbox1">Remember me</label>
                        </div>
                        <div class="checkbox float-right">
                            {{-- <a href="{{ route('user.login') }}" for="checkbox1">User Login</a> --}}
                        </div>
                      
                    </div>
                    <div class="clearfix">
                        <button type="submit" class="btn btn-primary float-right">Login</button>
                    </div>
                </form>
               
             
            </div>
        </div>
    </div>
</div>

<script>
      document.getElementById("togglePassword").addEventListener("click", function() {
            const passwordField = document.getElementById("password");
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
            this.querySelector("i").classList.toggle("fa-eye");
            this.querySelector("i").classList.toggle("fa-eye-slash");
        });

</script>
@endsection