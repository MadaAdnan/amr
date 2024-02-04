@extends('dashboard.layouts.index')


@section('content')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>

        </div>
        <div class="row p-2 card">
            <div class="row p-4">
                <h4 class="w-50 font-weight-bold">Create Company</h4>
                <div class="w-50 text-right">
                    {{-- <a href="{{ route('dashboard.companys.create') }}" type="button" class="btn btn-primary remove-border w-50">Create</a> --}}
                </div>
            </div>

            <form enctype="multipart/form-data" action="{{ route('dashboard.companies.store') }}" id="createForm"
                method="post">

                @csrf

                <div class="row  flex-row">
                    <div class="col-md-6">

                        <div class="form-group  p-3">
                            <label for="company_name">Company name
                                <strong class="text-danger">
                                    *
                                    @error('company_name')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="text" class="form-control" id="company_name" name="company_name"
                                value="{{ old('company_name') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="company_name">Postal Code
                                <strong class="text-danger">
                                    *
                                    @error('postal_code')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code"
                                value="{{ old('postal_code') }}">
                        </div>
                    </div>
                </div>




        <div class="row  flex-row">
            {{--  Email --}}
            <div class="col-md-6">
                <div class="form-group  p-3">
                    <label for="email">Email
                        <strong class="text-danger">
                            *
                            @error('email')
                                -
                                {{ $message }}
                            @enderror
                        </strong>
                    </label>
                    <div class="row  flex-row">
                        <div>
                            <input type="text" class="form-control" id="generated_code" name="email"
                                value="{{ old('email') }}">
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                {{-- Phone --}}

                <div class="form-group  p-3">
                    <label for="phone"> Phone
                        <strong class="text-danger">
                            *
                            @error('phone')
                                -
                                {{ $message }}
                            @enderror
                        </strong>
                    </label>
                    <input type="tel" id="phone" class="form-control" name="phone" value="{{ old('phone') }}">
                    <input type="hidden" id="country_code" name="country_code" value="">
                </div>
            </div>


        </div>

        <div class="row  flex-row">
            {{-- Country --}}
            <div class="col-md-6">
                <div class="form-group  p-3">
                    <label for="country_id"> Country
                        <strong class="text-danger">
                            *
                            @error('country_id')
                                -
                                {{ $message }}
                            @enderror
                        </strong>
                    </label>

                    <select class="form-control" onchange="getStates()" name="country_id" id="country-dropdown">
                        <option value="">Select Country</option>
                        @if (isset($countries) && $countries->count() > 0)
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" @if ($country->id == old('country_id')) selected @endif>
                                    {{ $country->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                {{-- state --}}
                <div class="form-group  p-3">
                    <label for="state-dropdown"> State
                        <strong class="text-danger">
                            *
                            @error('state_id')
                                -
                                {{ $message }}
                            @enderror
                        </strong>
                    </label>
                    <input type="hidden" value="{{ old('state_id') }}" id="state_id">
                    <select class="form-control" onchange="getCities()" name="state_id" id="state-dropdown" disabled>
                        <option value="">Select State</option>
                    </select>
                </div>
            </div>


        </div>
        <div class="row  flex-row">
            {{--  City --}}
            <div class="col-md-6">
                <div class="form-group  p-3">
                    <label for="city-dropdown"> City
                        <strong class="text-danger">
                            *
                            @error('city_id')
                                -
                                {{ $message }}
                            @enderror
                        </strong>
                    </label>

                    <div class="row  flex-row">
                        <div>
                            <input type="hidden" value="{{ old('city_id') }}" id="city_id">
                            <select class="form-control" name="city_id" id="city-dropdown" disabled>
                                <option value="">Select City</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
            {{--  Street --}}
            <div class="col-md-12">
                <div class="form-group  p-3">
                    <label for="city-dropdown"> Street
                        <strong class="text-danger">
                            *
                            @error('street')
                                -
                                {{ $message }}
                            @enderror
                        </strong>
                    </label>

                    <div class="row  flex-row">
                        <div>
                            <textarea class="form-control" id='street' name="street"></textarea>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="form-group  col-4 p-3">

            <button type="submit" onclick="sendActionToBlogger('Accepted')" class="btn btn-primary remove-border w-50">
                Create
            </button>
        </div>
        </form>
    </div>


    </div>
    <div id="createTagModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Tag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="titleInput">Title</label>
                        <input type="text" class="form-control" id="titleInputTag" placeholder="Enter title">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="actionButton" onclick="createTagRequest()" type="button"
                        class="btn btn-primary create-hover">Create</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="createCategorieModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="titleInput">Title</label>
                        <input type="text" class="form-control" id="titleInputCategory" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label for="slugInput">Slug</label>
                        <p>https:{{ env('APP_URL') }}/</p>
                        <input type="text" class="form-control" id="slugInputCategory" placeholder="Enter slug">

                    </div>
                </div>
                <div class="modal-footer">
                    <button id="actionButton" onclick="createCategoryRequest()" type="button"
                        class="btn btn-primary create-hover">Create</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="rejectNoteModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Reject</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <label for="rejectNote">Please add reject note:</label>
                    <textarea class="form-control" id="reject_note" rows="5"></textarea>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button onclick="sendRejectToBlogger()" type="button" class="btn btn-primary">Send</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
            // separateDialCode:true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.0/build/js/utils.js",
        });

        // store the instance variable so we can access it in the console e.g. window.iti.getNumber()
        window.iti = iti;
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            getStates();
            setTimeout(function() {
                getCities();
            }, 2000);
        });



        function getStates() {
            // $('#country-dropdown').on('change', function() {
            var country_id = $('#country-dropdown').val();
            $.ajax({
                url: "{{ route('dashboard.companies.getStates') }}",
                type: "POST",
                data: {
                    country_id: country_id,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    console.log(result);

                    // Enable the state-dropdown
                    $('#state-dropdown').prop('disabled', false);

                    // Clear and populate the state-dropdown
                    $('#state-dropdown').html('<option value="">Select State</option>');
                    $.each(result, function(key, value) {
                        var selected = '';
                        // Check if the value.id matches the old('state_id') value
                        if (value.id == '{{ old('state_id') }}') {
                            selected = 'selected';
                        }
                        $("#state-dropdown").append('<option value="' + value.id + '" ' +
                            selected +
                            '>' + value.name + '</option>');
                    });

                    // Set the old value for state
                    $('#state-dropdown').val('{{ old('state_id') }}');

                    // Disable and reset the city-dropdown
                    $('#city-dropdown').prop('disabled', true);
                    $('#city-dropdown').html('<option value="">Select State First</option>');
                }
            });

        }

        function getCities() {
            var state_id = $('#state-dropdown').val();
            $.ajax({
                url: "{{ route('dashboard.companies.getCities') }}",
                type: "POST",
                data: {
                    state_id: state_id,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {


                    $('#city-dropdown').prop('disabled', false);

                    // Clear and populate the city-dropdown
                    $('#city-dropdown').html('<option value="">Select City</option>');
                    $.each(result, function(key, value) {
                        var selected = '';
                        // Check if the value.id matches the old('city_id') value
                        if (value.id == '{{ old('city_id') }}') {
                            selected = 'selected';
                        }
                        $("#city-dropdown").append('<option value="' + value.id + '" ' +
                            selected +
                            '>' + value.title + '</option>');
                    });

                    // Set the old value for city
                    $('#city-dropdown').val('{{ old('city_id') }}');
                }
            });

        }
    </script>
@endsection
