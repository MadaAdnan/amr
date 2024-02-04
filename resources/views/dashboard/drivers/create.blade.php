@extends('dashboard.layouts.index')


@section('content')
    {{-- @php
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
    @endphp --}}

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
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>

        </div>
        <div class="row p-2 card">
            <div class="row p-4">
                <h4 class="w-50 font-weight-bold">Create Driver</h4>
                <div class="w-50 text-right">
                    {{-- <a href="{{ route('dashboard.drivers.create') }}" type="button" class="btn btn-primary remove-border w-50">Create</a> --}}
                </div>
            </div>

            <form enctype="multipart/form-data" action="{{ route('dashboard.drivers.store') }}" id="AddForm"
                method="post">

                @csrf

             

                {{--  First name --}}
                <div class="row  flex-row">
                    <div class="col-md-6">

                        <div class="form-group  p-3">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                value="{{ old('first_name') }}">
                        </div>
                    </div>
                    {{--  Last Name --}}
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="last_name">Last Name
                                <strong class="text-danger">
                                    *
                                    @error('last_name')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                value="{{ old('last_name') }}">
                        </div>
                    </div>
                </div>


                {{-- PRIMARY PHONE --}}
                <div class="row  flex-row">
                    <div class="col-md-6">
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
                            <input type="tel" id="phone" class="form-control" name="phone"
                                value="{{ old('phone') }}">
                        </div>
                    </div>
                 
                </div>




             

                
             
                <div class="form-group  col-4 p-3">

                    <button type="submit" onclick="sendActionToBlogger('Accepted')"
                        class="btn btn-primary remove-border w-50">
                        create
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
        $("#AddForm").validate({
            rules: {
                question: {
                    required: true
                },
                answer: {
                    required: true
                },
                type: {
                    required: true
                },
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            discount();
            $('.select2').select2()
        });

        function discount() {

            var selectedCouponId = $("#coupon_id").val();
            // Perform an AJAX request to get the discount percentage
            $.ajax({
                url: '{{ route('dashboard.drivers.discount') }}',
                type: 'Post',
                data: {

                    coupon_id: selectedCouponId,


                    _token: $('meta[name="csrf-token"]').attr('content')

                },



                success: function(data) {
                    $('#discountPercentage').text(data + '%');
                }
            });

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
