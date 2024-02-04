@extends('layouts.app')
@section('pageTitle')
<title>
Chauffeur Partners - Lets help you grow | LavishRide
</title>
@endsection
@section('seo')
<meta name="title" content="Chauffeur Partners - Lets help you grow | LavishRide">
<meta name="description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta name="keywords" content="Become a chauffeur partner, Chauffeur, Grow your business with Lavishride, driver login">
<link rel="canonical"    href="{{ route('chauffeur_application') }}">

<meta property="og:title" content="Chauffeur Partners - Lets help you grow | LavishRide" />
<meta property="og:description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta property="og:image" content="{{ asset("assets_new/Lavish-Ride-Chauffeur.jpg") }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('chauffeur_application') }}/" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Chauffeur Partners - Lets help you grow | LavishRide" />
<meta name="twitter:description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta name="twitter:image" content="{{ asset("assets_new/Lavish-Ride-Chauffeur.jpg") }}" />
@endsection

@section('page_name')
    <h1 class="text-white">Chauffeur</h1>
@endsection
@section('content')
    <style>
            #banner_section{
                background-image: url('{{ asset("assets_new/Lavish-Ride-Chauffeur.jpg") }}') !important;
            }
    </style>


    <!-- content-area -->
    <div class="white-section section-block cp-md-margin-top-80">
        <div class="">
            <div class="row test image-back-2">
                <div class="col-md-4 margin-top-100 ">
                </div>

                <div class="col-md-6 col-12 content">
                    <div class="card forms-card">
                        <div class="card-body">
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2 class="padding-bottom-35">Chauffeur Application</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <form accept-charset="UTF-8" action="{{route('chauffeur_application')}}" class="require-validation" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label class="control-label">Full Name <span class="text-danger">*</span></label>
                                                <input name="name" value="{{ old('name') }}" autocomplete="off" size="20" type="text" class="with-border"
                                                       placeholder="Full Name" required>
                                                @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label class="control-label">Phone <span class="text-danger">*</span></label>
                                                <input name="phone" value="{{ old('phone') }}" autocomplete="off" type="text" class="with-border"
                                                       placeholder="Phone" required>
                                                @error('phone')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="control-label">Email <span class="text-danger">*</span></label>
                                                <input name="email" value="{{ old('email') }}" autocomplete="off" size="20" type="email" class="with-border"
                                                       placeholder="Email" required>
                                                @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label class="control-label">State <span class="text-danger">*</span></label>
                                                <select name="state" id="state" value="{{ old('state') }}" lass="selectpicker with-border" required>
                                                    <option value="">Choose State</option>
                                                </select>
                                                @error('state')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <label class="control-label">City <span class="text-danger">*</span></label>
                                                <input name="city" value="{{ old('city') }}" autocomplete="off" size="20" type="text" class="with-border"
                                                       placeholder="City" required>
                                                @error('city')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label class="control-label">Address <span class="text-danger">*</span></label>
                                                <input name="address" value="{{ old('address') }}" autocomplete="off" type="text" class="with-border"
                                                       placeholder="Address" required>
                                                @error('address')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label class="control-label">Date Of Birth <span class="text-danger">*</span></label>
                                                <input name="date_of_birth" value="{{ old('date_of_birth') }}" autocomplete="off" type="date" class="with-border" required>
                                                @error('date_of_birth')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="control-label">Experience Years <span class="text-danger">*</span></label>
                                                <input name="experience_years" value="{{ old('experience_years') }}" autocomplete="off" size="20" type="text" class="with-border"
                                                       placeholder="3 Years" required>
                                                @error('experience_years')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-sm-6 margin-top-15">
                                                @error('availability')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                <label class="control-label">Can you be available on short notice? <span class="text-danger">*</span></label>
                                                <div>
                                                    <div class="radio margin-right-25">
                                                        <input name="availability" value="1" id="radio-1" type="radio">
                                                        <label for="radio-1"><span class="radio-label"></span>Yes </label>
                                                    </div>
                                                    <div class="radio">
                                                        <input  name="availability" value="0" id="radio-2" type="radio">
                                                        <label for="radio-2"><span class="radio-label"></span>No </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 margin-top-15">
                                                @error('texas_license')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                <label class="control-label">Do you have Valid State Texas Driver's License? <span class="text-danger">*</span></label>
                                                <div>
                                                    <div class="radio margin-right-25">
                                                        <input name="texas_license" value="1" id="radio-3" type="radio">
                                                        <label for="radio-3"><span class="radio-label"></span>Yes</label>
                                                    </div>
                                                    <div class="radio">
                                                        <input name="texas_license" value="0" id="radio-4" type="radio">
                                                        <label for="radio-4"><span class="radio-label"></span>No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 margin-top-15">
                                                @error('houston_limo_license')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                <label class="control-label">Do you have Valid City of Houston Limo License?  <span class="text-danger">*</span></label>
                                                <div>
                                                    <div class="radio margin-right-25">
                                                        <input name="houston_limo_license" value="0" id="radio-5" type="radio">
                                                        <label for="radio-5"><span class="radio-label"></span>Yes</label>
                                                    </div>
                                                    <div class="radio">
                                                        <input name="houston_limo_license" value="1" id="radio-6" type="radio">
                                                        <label for="radio-6"><span class="radio-label"></span>No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row margin-top-15">
                                            <div class="form-group col-sm-6">
                                                <label class="control-label">Upload your resume <span class="text-danger">*</span></label>
                                                <input name="resume" accept="application/pdf" value="{{ old('resume') }}" autocomplete="off" type="file" class="with-border" required>
                                                @error('resume')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label class="control-label">Additional Information</label>
                                                <textarea name="additional_information" value="{{ old('additional_information') }}"  rows="3" class="margin-top-10 with-border"></textarea>
                                            </div>
                                        </div>


                                        <div class="row justify-content-center margin-top-40">
                                            <div class="col-6">
                                                <button type="submit" class="custom-btn btn-5">Submit Now</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-space"></div>

    </div>

    <script>
        $.getJSON('/general/states-object.json', function(data) {
            $.each(data, function(key, value) {
                $("#state").append("<option value='" + key + "'>  " + value + "</option>");
            });
        });
    </script>
@endsection
