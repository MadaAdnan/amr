@extends('dashboard.layouts.index')


@section('content')
    @php
        $role = auth()->user()->roles[0]->name;
        $status = [
            'in-progress' => 'In Progress',
            'publish' => 'Published',
            'draft' => 'Draft',
            'pending' => 'Pending',
            'rejected' => 'Rejected',
            'admin_reject' => 'Admin reject',
        ];
        $myBlogs = auth()
            ->user()
            ->Blogger()
            ->get()
            ->pluck('id')
            ->toArray();
        $checkMyBlog = in_array($reservation->id, $myBlogs);
    @endphp

    {{-- =========================================================== --}}
    {{-- ================== Sweet Alert Section ==================== --}}
    {{-- =========================================================== --}}
    <div>
        @if (session()->has('success'))
            <script>
                swal("Great Job !!!", "{!! Session::get('success') !!}", "success", {
                    button: "OK",
                });
            </script>
        @endif
        @if (session()->has('danger'))
            <script>
                swal("Oops !!!", "{!! Session::get('danger') !!}", "error", {
                    button: "Close",
                });
            </script>
        @endif
    </div>
    {{-- ============================================== --}}
    {{-- ================== Header ==================== --}}
    {{-- ============================================== --}}

    <style>

    </style>
    <div class="page-title card">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>

        </div>
        <div class="row p-2">
            <div class="row p-4">
                <h4 class="w-50 font-weight-bold">Edit Reservations</h4>
                <div class="w-50 text-right">
                    {{-- <a href="{{ route('dashboard.reservations.create') }}" type="button" class="btn btn-primary remove-border w-50">Create</a> --}}
                </div>
            </div>

            <form enctype="multipart/form-data" action="{{ route('dashboard.reservations.update', $reservation->id) }}"
                id="AddForm" method="post">

                @csrf

                {{--  Pick-up Location --}}
                <div class="row  flex-row">
                    <div class="col-6 ">
                        <div class="form-group p-3">
                            <label for="pick_up_location">Pick-up Location

                                <strong class="text-danger">
                                    *
                                    @error('pick_up_location')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="text" class="form-control" id="pick_up_location" name="pick_up_location"
                                value="{{ isset($reservation->pick_up_location) ? $reservation->pick_up_location : '----' }}"
                                disabled>
                        </div>
                    </div>
                    {{--  Drop-off Location --}}
                    <div class="col-6 ">
                        <div class="form-group  p-3">
                            <label for="drop_off_location">Drop-off Location
                            </label>
                            <input type="text" class="form-control" id="drop_off_location" name="drop_off_location"
                                value="{{ isset($reservation->drop_off_location) ? $reservation->drop_off_location : '----' }}"disabled>
                        </div>
                    </div>
                </div>

                {{--  Pick-up Date --}}
                <div class="row  flex-row">
                    <div class="col-6">
                        <div class="form-group  p-3">
                            <label for="pick_up_date">Pick-up Date
                                <strong class="text-danger">
                                    *
                                    @error('pick_up_date')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="date" class="form-control" id="pick_up_date" name="pick_up_date"
                                value="{{ isset($reservation->pick_up_date) ? $reservation->pick_up_date : '----' }}"disabled>
                        </div>
                    </div>
                    {{--  Pick-up Time --}}
                    <div class="col-6">
                        <div class="form-group  p-3">
                            <label for="pick_up_time">Pick-up Time
                                <strong class="text-danger">
                                    *
                                    @error('pick_up_time')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="time" class="form-control" id="pick_up_time" name="pick_up_time"
                                value="{{ isset($reservation->pick_up_time) ? $reservation->pick_up_time : '----' }}"disabled>
                        </div>
                    </div>
                </div>




                {{--  Duration --}}
                <div class="row  flex-row">
                    <div class="col-md-6">

                        <div class="form-group  p-3">
                            <label for="duration">Duration</label>
                            <input type="text" class="form-control" id="duration" name="duration"
                                value="{{ isset($reservation->duration) ? $reservation->duration : '----' }}"disabled>
                        </div>
                    </div>
                    {{--  Distance --}}
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="distance">Distance</label>
                            <input type="text" class="form-control" id="distance" name="distance"
                                value="{{ isset($reservation->distance) ? $reservation->distance : '----' }}"disabled>
                        </div>
                    </div>
                </div>


                {{--  RETURN DATE --}}
                <div class="row  flex-row">
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="return_date">Return Date</label>
                            <input type="date" class="form-control" id="return_date" name="return_date"
                                value="{{ isset($reservation->return_date) ? $reservation->return_date : '----' }}"disabled>
                        </div>
                    </div>
                    {{--  RETURN TIME --}}
                    <div class="col-md-6">

                        <div class="form-group  p-3">
                            <label for="return_time">Return Time</label>
                            <input type="time" class="form-control" id="return_time" name="return_time"
                                value="{{ isset($reservation->return_time) ? $reservation->return_time : '----' }}"disabled>
                        </div>
                    </div>
                </div>

                {{--  TIP --}}
                <div class="row  flex-row">
                    <div class="col-md-6">

                        <div class="form-group  p-3">
                            <label for="tip">Tip</label>
                            <input type="text" class="form-control" id="tip" name="tip"
                                value="{{ isset($reservation->tip) ? $reservation->tip : '----' }}"disabled>
                        </div>
                    </div>
                    {{--  PRICE --}}
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="price">Price
                                <strong class="text-danger">
                                    *
                                    @error('price')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="text" class="form-control" id="price" name="price"
                                value="{{ isset($reservation->price) ? $reservation->price : '----' }}"disabled>
                        </div>
                    </div>
                </div>


                {{-- PRIMARY PHONE --}}
                <div class="row  flex-row">
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="phone_primary">Primary Phone
                                <strong class="text-danger">
                                    *
                                    @error('phone_primary')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="text" class="form-control" id="phone_primary" name="phone_primary"
                                value="{{ isset($reservation->phone_primary) ? $reservation->phone_primary : '----' }}"disabled>
                        </div>
                    </div>
                    {{-- SECONDARY PHONE --}}
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="phone_secondary">Secondary Phone</label>
                            <input type="text" class="form-control" id="phone_secondary" name="phone_secondary"
                                value="{{ isset($reservation->phone_secondary) ? $reservation->phone_secondary : '----' }}"disabled>
                        </div>
                    </div>
                </div>


                {{-- SERVICE TYPE --}}
                <div class="row  flex-row">
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="service_type">Service Type
                                <strong class="text-danger">
                                    *
                                    @error('service_type')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="text" class="form-control" id="service_type" name="service_type"
                                value="{{ isset($reservation->serviceType->service_name) ? $reservation->serviceType->service_name : '----' }}"
                                disabled>
                        </div>
                    </div>
                    {{-- Comment --}}

                    <div class="col-md-6">

                        <div class="form-group  p-3">
                            <label for="comment">Comment</label>
                            <textarea class="form-control" id="comment" name="comment" disabled>{!! isset($reservation->comment) ? $reservation->comment : '' !!}</textarea>
                        </div>
                    </div>



                </div>



                {{-- Airline --}}
                <div class="row  flex-row">
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="airline">Airline</label>
                            <input type="text" class="form-control" id="airline" name="airline"
                                value="{{ isset($reservation->airline) ? $reservation->airline : '----' }}" disabled>
                        </div>
                    </div>
                    {{-- Flight Number --}}
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="flight_number">Flight Number</label>
                            <input type="text" class="form-control" id="flight_number" name="flight_number"
                                value="{{ isset($reservation->flight_number) ? $reservation->flight_number : '----' }}"
                                disabled>
                        </div>
                    </div>
                </div>
                {{-- status --}}
                <div class="row  flex-row ">
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="status">Status
                                <strong class="text-danger">
                                    *
                                    @error('status')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <select class="form-control" id="status" name="status" disabled>
                                <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="accepted"{{ $reservation->status == 'accepted' ? 'selected' : '' }}>
                                    Accepted
                                </option>
                                <option value="on board"{{ $reservation->status == 'on board' ? 'selected' : '' }}>
                                    On
                                    Board
                                </option>
                                <option value="on the way"{{ $reservation->status == 'on the way' ? 'selected' : '' }}>
                                    On
                                    the Way
                                </option>
                                <option
                                    value="under review"{{ $reservation->status == 'under review' ? 'selected' : '' }}>
                                    Under
                                    Review</option>
                                <option value="finished"{{ $reservation->status == 'finished' ? 'selected' : '' }}>
                                    Finished
                                </option>
                                <option value="trip began"{{ $reservation->status == 'trip began' ? 'selected' : '' }}>
                                    Trip Began
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Fleet Category --}}
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="fleet">Fleets Category
                                <strong class="text-danger">
                                    *
                                    @error('category_id')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <select class="form-control" id="fleet" name="category_id"disabled>
                                @if (isset($fleet_categories) && count($fleet_categories) > 0)
                                    @foreach ($fleet_categories as $fleet)
                                        <option value="{{ $fleet->id }}"
                                            {{ $fleet->id == $reservation->category_id ? 'selected' : '' }}>

                                            {{ $fleet->title }}

                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                {{--  transfer Type --}}
                <div class="row  flex-row">
                    <div class="col-6">
                        <div class="form-group  p-3">
                            <label for="transfer_type">Transfer Type
                                <strong class="text-danger">
                                    *
                                    @error('transfer_type')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <select class="form-control" id="transfer_type" name="transfer_type"disabled>
                                <option value="On Way"
                                    {{ isset($reservation->transfer_type) && $reservation->transfer_type == 'On Way' ? 'selected' : '----' }}>
                                    One Way</option>
                                <option value="Round"
                                    {{ isset($reservation->transfer_type) && $reservation->transfer_type == 'Round' ? 'selected' : '----' }}>
                                    Round</option>
                            </select>
                        </div>
                    </div>
                    {{-- Coupon --}}

                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="fleet">Coupon
                            </label>
                            <select class="form-control select2" id="coupon_id" name="coupon_id" onchange="discount()"
                                disabled>
                                @if (isset($coupons) && count($coupons) > 0)
                                    @foreach ($coupons as $coupon)
                                        <option value="{{ $coupon->id }}"
                                            @if ($reservation->coupon_id == $coupon->id) selected @endif>

                                            {{ $coupon->coupon_name }}

                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <p>Discount Percentage: <span id="discountPercentage">0%</span></p>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-6">
            <div class="form-group  p-3">
                <label for="driver_id">Drivers
                    <strong class="text-danger">
                        *
                        @error('driver_id')
                            -
                            {{ $message }}
                        @enderror
                    </strong>
                </label>
                <select disabled class="form-control" id="driver_id" name="driver_id">
                    @if (isset($drivers) && count($drivers) > 0)
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}" @if ( $driver->id  == $driver->id) selected  @endif>
                                {{ $driver->first_name }}
                                {{ $driver->last_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>



        @if (isset($reservation->childSeats) && count($reservation->childSeats) > 0)
            <div class="row  flex-row">
                {{-- Child Seats --}}
                <div class="col-md-12">
                    <div class="form-group  p-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Child Seats</th>
                                    <th scope="col">Amount</th>

                                </tr>
                            </thead>
                            <tbody>

                                {{-- <label for="child_seats">Child Seats</label> --}}

                                @foreach ($reservation->childSeats as $index => $seat)
                                    <tr>
                                        <td> <input disabled name="child_seats[{{ $index }}][id]"
                                                class="form-control"
                                                value="{{ $seat->title . ' (' . $seat->price . '$ )' }}" />
                                            <input disabled type="hidden" name="child_seats[{{ $index }}][id]"
                                                class="form-control" value="{{ $seat->id }}" />
                                        </td>
                                        <td>
                                            {{-- <label for="child_seats[{{ $index }}][amount]">Amount of Seats:</label> --}}
                                            <input name="child_seats[{{ $index }}][amount]" type="number"
                                                min="1" class="form-control"
                                                value="{{ $seat->pivot->amount ?? 0 }}" disabled>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        

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
        const titleInputTag = document.getElementById('titleInputTag');
        const slugInputTag = document.getElementById('slugInputTag');
        const titleInputCategory = document.getElementById('titleInputCategory');
        const slugInputCategory = document.getElementById('slugInputCategory');
        const myRole = '{{ $role }}';

        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{ route('dashboard.upload_an_Image_ck_editor', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'

        });

        CKEDITOR.on('instanceReady', function(evt) {
            var editor = evt.editor;

            editor.on('change', function(e) {
                var text = editor.editable().getText();
                var data = editor.editable().getData();
                let numberOfCharacters = text.length;
                var strippedContent = text.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ');
                var numberOfWords = strippedContent.split(' ').length;
                var regex = /<a\s+(?:[^>]*?\s+)?href=(["'])(.*?)\1/g;
                var links = [];
                var match;
                var lavaShregex = /\blavishride\b/;
                let externalLinks = []
                let internalLinks = []

                while ((match = regex.exec(data)) !== null) {
                    links.push(match[2]);
                }

                links.forEach(element => {
                    if (lavaShregex.test(element)) {
                        if (!internalLinks.includes(element)) {
                            internalLinks.push(element);
                        }
                    } else {
                        if (!externalLinks.includes(element)) {
                            externalLinks.push(element);
                        }
                    }
                });



                $('#numberOfCharacters-change').empty();
                $('#numberOfWords-change').empty();
                $('#numberOfExterinalLinks-change').empty();
                $('#numberOfInternalLinks-change').empty();

                $('#numberOfCharacters-change').append(numberOfCharacters);
                $('#numberOfWords-change').append(numberOfWords);
                $('#numberOfExterinalLinks-change').append(externalLinks.length);
                $('#numberOfInternalLinks-change').append(internalLinks.length);


            });

            getTheNumberOfLinks()


            function getTheNumberOfLinks() {
                var text = editor.editable().getText();
                var data = editor.editable().getData();
                let numberOfCharacters = text.length;
                var strippedContent = text.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ');
                var numberOfWords = strippedContent.split(' ').length;



                var regex = /<a\s+(?:[^>]*?\s+)?href=(["'])(.*?)\1/g;
                var links = [];
                var match;
                var lavaShregex = /\blavishride\b/;
                let externalLinks = []
                let internalLinks = []
                while ((match = regex.exec(data)) !== null) {

                    links.push(match[2]);
                }

                links.forEach(element => {
                    if (lavaShregex.test(element)) {
                        if (!internalLinks.includes(element)) {
                            internalLinks.push(element);
                        }
                    } else {
                        if (!externalLinks.includes(element)) {
                            externalLinks.push(element);
                        }
                    }
                });


                $('#numberOfCharacters-change').empty();
                $('#numberOfWords-change').empty();
                $('#numberOfExterinalLinks-change').empty();
                $('#numberOfInternalLinks-change').empty();

                $('#numberOfCharacters-change').append(numberOfCharacters);
                $('#numberOfWords-change').append(numberOfWords);
                $('#numberOfExterinalLinks-change').append(externalLinks.length);
                $('#numberOfInternalLinks-change').append(internalLinks.length);

            }
        });


        /** Get tags for the selecte using Ajax **/
        const request = '{{ route('dashboard.keywords.get_tags_select') }}'
        $('.tags-select').select2({
            ajax: {
                url: request,
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function(res, params) {
                    params.page = params.page || 1;
                    return {
                        results: res.data,
                        pagination: {
                            more: (params.page * 30) < res.data.total_count
                        }
                    };
                }
            }
        });

        /** Open Modal **/
        $('#TagsButton').click(function() {
            $('#createTagModal').modal('toggle')
        });

        /** Create Tag */
        function createTagRequest() {
            const url = '{{ route('dashboard.tags.store') }}';
            let data = {
                'title': titleInputTag.value,
                '_token': '{{ csrf_token() }}'
            }
            $.ajax({
                url,
                data,
                type: "POST",
                success: (res) => {
                    var newOption = new Option(data.text, data.id, true, true);
                    $('#mySelect2').append(newOption).trigger('change');
                    Toast.fire({
                        icon: 'success',
                        title: 'Data was added'
                    })
                    titleInputTag.value = '';
                    slugInputTag.value = '';
                },
                error: (err_res) => {
                    Toast.fire({
                        icon: 'error',
                        title: err_res.responseJSON.msg
                    })
                }
            })
        }

        /** Get categories for the select using Ajax **/
        const get_categories_request = '{{ route('dashboard.categories.get_categories_select') }}'
        const categorySelect = $('#categoriesSelect');
        const keywordsSelect = $('#keywordsSelect');
        const tagSelect = $('.tags-select');

        function getCategorySelect2() {
            const request = '{{ route('dashboard.blogs.get_selected_data_select2', $reservation->id) }}'
            $.ajax({
                url: request,
                type: 'GET',
            }).then(function(res) {
                console.log(res)

                res.data.categories.forEach((item) => {
                    var option = new Option(item.title, item.id, true, true);
                    categorySelect.append(option).trigger('change');
                })
                res.data.tags.forEach((item) => {
                    var option = new Option(item.title, item.id, true, true);
                    tagSelect.append(option).trigger('change');
                })

                tagSelect.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            });

        }

        getCategorySelect2()



        categorySelect.select2({
            ajax: {
                url: get_categories_request,
                dataType: 'json',
                data: function(params) {

                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function(res, params) {
                    params.page = params.page || 1;
                    return {
                        results: res.data,
                        pagination: {
                            more: (params.page * 30) < res.data.total_count
                        }
                    };
                }
            }
        })





        /** Open Modal **/
        $('#addCategoreyButton').click(function() {
            $('#createCategorieModal').modal('toggle')
        });



        /**Create category **/
        function createCategoryRequest() {
            const url = '{{ route('dashboard.categories.store') }}';
            let data = {
                'title': titleInputCategory.value,
                'slug': slugInputCategory.value,
                '_token': '{{ csrf_token() }}'
            }
            $.ajax({
                url,
                data,
                type: "POST",
                success: (res) => {
                    var newOption = new Option(data.text, data.id, true, true);
                    $('#mySelect2').append(newOption).trigger('change');
                    Toast.fire({
                        icon: 'success',
                        title: 'Data was added'
                    })
                    titleInputCategory.value = '';
                    slugInputCategory.value = '';
                },
                error: (err_res) => {
                    Toast.fire({
                        icon: 'error',
                        title: err_res.responseJSON.err
                    })
                }
            })
        }


        $('#uploadArea').click(function() {
            $('#thembNail').click();
        });

        $('#changeImage').click(function() {
            $('#thembNail').click();
        });

        function readURL(input) {
            $("#uploadArea").css("display", 'none');
            $("#imageArea").css("display", 'block');
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result).width(600).height(300);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        /** Keyword **/
        $('#keywordsSelect').select2();

        /** Form Valdation **/
        $("#AddForm").validate({
            rules: {
                slug: {
                    required: true
                }
            },
            messages: {
                slug: {
                    required: 'Please enter a slug'
                }
            },
            submitHandler: function(form) {
                checkSlug(form);

                let preview = $("<input type='text'  name />").appendTo('.selector');

                if (true) {
                    event.preventDefault();
                    var newTab = window.open("", "_blank");
                    this.target = "_blank";
                    newTab.close();
                }

                return false;
                // $(form).submit();
            }
        });

        $('#keywordsSelect').select2({
            maximumSelectionLength: 100
        })

        $('#keywordsSelect').change(function() {
            const selectElement = document.getElementById('keywordsSelect');
            const selectedOptions = selectElement.selectedOptions;
            const numberOfSelectedOptions = selectedOptions.length;
            $('#numberOfKeyWordsSelected').empty();
            $('#numberOfKeyWordsSelected').append(numberOfSelectedOptions);

        });


        function checkSlug(form) {
            let request = '{{ route('dashboard.blogs.check_slug', ':slug') }}';
            let slug = document.getElementById('slug') ? document.getElementById('slug').value : Math.random();
            request = request.replace(":slug", )
            request = request + '?my_slug=' + '{{ $reservation->slug }}'
            $.ajax({
                url: request,
                type: 'GET',
                success: (res) => {
                    let check = res.data ? true : false;
                    if (!check) {
                        document.getElementById('AddForm').submit();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Please check and make sure the slug is unique.'
                        })
                    }
                    return;
                },
                error: (err) => {
                    return false
                }
            });
        }




        function publish() {
            const request = '{{ route('dashboard.blogs.publish', $reservation->id) }}';
            document.getElementById('AddForm').action = request;
            $('#AddForm').submit();
            //    $.ajax({
            //     url:request,
            //     type:'POST',
            //     data:{
            //         '_token':'{{ csrf_token() }}'
            //     },
            //     success:(res)=>{
            //        window.location.href = '{{ route('dashboard.blogs.index') }}';
            //     },
            //     error:()=>{},
            //    });
        }


        /*** Add The Content to ckEd **/
        $("#content").val(`{!! $reservation->content !!}`);


        function countWord(e, char) {
            let value = e.target.value.length;
            $('#' + char).empty();
            $('#' + char).append(value);
        }
        
        function sendPreviewRequest() {
            document.getElementById('AddForm').action = '{{ route('dashboard.blogs.preview', $reservation->id) }}'
            $('#AddForm').submit();
        }

        function sendSaveRequest() {
            document.getElementById('AddForm').action = "{{ route('dashboard.blogs.update', $reservation->id) }}";
            $('#AddForm').submit();
        }

        function sendSubmitRequest() {
            document.getElementById('AddForm').action =
                "{{ route('dashboard.blogs.update', [$reservation->id, 'in-progress']) }}";
        }

        function sendActionToBlogger(status) {
            if (status == 'Rejected') {
                $('#rejectNoteModal').modal('show');
            } else {
                Swal.fire({
                    title: 'Are you sure you want to submit this blog?',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Continue editing',
                    denyButtonText: `Yes`,
                    confirmButtonColor: 'success',
                    confirmButtonColor: 'danger',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    let categories = document.getElementById('categoriesSelect').value;
                    if (!categories) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please add categorie',
                        })
                        return;
                    }
                    if (!result.isConfirmed) {
                        let status = 'Pending';
                        if (myRole == 'Super-admin') {
                            status = 'publish';
                        }
                        let updateRequest = "{{ route('dashboard.blogs.update', [$reservation->id, ':status']) }}";
                        updateRequest = updateRequest.replace(':status', status);
                        document.getElementById('AddForm').action = updateRequest;
                        $('#AddForm').submit();
                    } else if (result.isDenied) {
                        // Swal.fire('Changes are not saved', '', 'info')
                    }
                })

            }
        }

        function sendRejectToBlogger() {
            const request = '{{ route('dashboard.blogs.send_reject_note', $reservation->id) }}';
            let reject_note = document.getElementById('reject_note').value;
            if (reject_note == '') {
                Swal.fire(
                    'Input Error',
                    'Please add reject note',
                    'error'
                )
                return;
            }

            $.ajax({
                url: request,
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    reject_note
                },
                success: (res) => {
                    window.location.href = '{{ route('dashboard.blogs.index') }}';
                },
                error: (err) => {}
            })
        }

        function addKeywords() {
            Swal.fire({
                title: "Add Your Keywords with ',' between them",
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off',
                    required: true
                },
                showCancelButton: true,
                confirmButtonText: 'Add',
                showLoaderOnConfirm: true,
                preConfirm: (text) => {

                    const textToArray = text.split(",");
                    const request = '{{ route('dashboard.keywords.save_keywords') }}';
                    return $.ajax({
                        url: request,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'items': textToArray
                        },
                        success: (res) => {
                            console.log('success response: ', res)
                            return res;
                        },
                        error: (err) => {}
                    });

                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((res) => {
                console.log('response: ', res)
                const items = res.value.data.items;
                if (items) {
                    items.forEach((item) => {
                        var newOption = new Option(item.value, item.id, true, true);
                        $('#keywordsSelect').append(newOption).trigger('change');
                    })
                } else {
                    Swal.fire({
                        title: 'Something wrong!',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                }
            })

        }
    </script>
@endsection
