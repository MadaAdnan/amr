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
                <h4 class="w-50 font-weight-bold">Create Coupon</h4>
                <div class="w-50 text-right">
                    {{-- <a href="{{ route('dashboard.coupons.create') }}" type="button" class="btn btn-primary remove-border w-50">Create</a> --}}
                </div>
            </div>

            <form enctype="multipart/form-data" action="{{ route('dashboard.coupons.store') }}" id="AddForm" method="post">

                @csrf



                {{--  coupon name --}}
                <div class="row  flex-row">
                    <div class="col-md-6">

                        <div class="form-group  p-3">
                            <label for="coupon_name">coupon name
                                <strong class="text-danger">
                                    *
                                    @error('coupon_name')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="text" class="form-control" id="coupon_name" name="coupon_name"
                                value="{{ old('coupon_name') }}">
                        </div>
                    </div>
                    {{--  Code --}}
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="coupon_code">Code
                                <strong class="text-danger">
                                    *
                                    @error('coupon_code')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <div class="row  flex-row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="generated_code" name="coupon_code"
                                    maxlength="8" value="{{ old('coupon_code') }}">
                                </div>
                                <div class="col-md-6">
                                    <button id="generate_button" class="btn btn-primary remove-border"
                                        type="button">Generate Code</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                {{-- usage_limit --}}
                <div class="row  flex-row">
           
                    <div class="col-md-12">
                        <div class="form-group  p-3">
                            <label for="usage_limit"> Usage Limit
                                <strong class="text-danger">
                                    *
                                    @error('usage_limit')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="number" id="usage_limit" class="form-control" name="usage_limit"
                                value="{{ old('usage_limit') ? old('usage_limit') : 1 }}" min="1">
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="percentage_discount"> Percentage Discount
                                <strong class="text-danger">
                                    *
                                    @error('percentage_discount')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="number" id="percentage_discount" class="form-control" name="percentage_discount"
                                step="1" value="{{ old('percentage_discount') ? old('percentage_discount') : 1 }}"
                                min="1" max="100">
                        </div>
                    </div> --}}

                </div>

                {{-- active_from --}}
                <div class="row  flex-row">
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="active_from"> Start Date
                                <strong class="text-danger">
                                    *
                                    @error('active_from')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input onchange="setDateFilter()" type="date" id="active_from" class="form-control"
                                name="active_from" value="{{ old('active_from') }}" min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group  p-3">
                            <label for="active_to"> End Date
                                <strong class="text-danger">
                                    *
                                    @error('active_to')
                                        -
                                        {{ $message }}
                                    @enderror
                                </strong>
                            </label>
                            <input type="date" id="active_to" class="form-control" name="active_to"
                                value="{{ old('active_to') }}" min="{{ date('Y-m-d') }}" disabled>
                        </div>
                    </div>


                    <div class="col-md-6 mb-3">
                        <label for="radius" class="form-label">Price Increase Type</label>
                        <select id="discountType" onchange="changeTypeOfDiscount(event.target.value)" class="form-control" name="discount_type">
                            <option value="price">Price</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>
                    <div id="priceDiv" class="col-md-6">
                        <label for="radius" class="form-label">Price</label>
                        <input name="price" id="price" min="1" type="number" class="form-control">
                    </div>
                    <div id="percentageDiv" class="col-md-6" style="display: none;">
                        <label for="radius" class="form-label">Percentage</label>
                        <input name="percentage" id="percentage" min="1" max="100" type="number" class="form-control">
                    </div>

                </div>








                <div class="form-group  col-4 p-3">

                    <button type="submit" onclick="sendActionToBlogger('Accepted')"
                        class="btn btn-primary remove-border w-50">
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
    const discountTypeInput = document.getElementById('discountType');
    const percentageInput = document.getElementById('price');
    const priceInput = document.getElementById('percentage');

        $("#AddForm").validate({
            rules: {
                coupon_name: {
                    required: true
                },
                coupon_code: {
                    required: true
                },
                usage_limit: {
                    required: true
                },
                active_from: {
                    required: true
                },
                active_to: {
                    required: true
                },
                discount_type: {
                    required: true
                },
                price:{
                    required:{
                        depends:()=>{
                            return discountTypeInput.value == 'price'?true:false
                        }
                    },
                    min:1
                },
                percentage:{
                    required:{
                        depends:()=>{
                                return discountTypeInput.value == 'percentage'?true:false
                        }
                    },
                    min:0,
                    max:100
                },
            }
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const generateButton = document.getElementById("generate_button");
            const generatedCodeInput = document.getElementById("generated_code");

            generateButton.addEventListener("click", function() {
                event.preventDefault(); // Prevent the default form submission behavior

                const generatedCode = generateRandomCode(8);
                generatedCodeInput.value = generatedCode;
            });

            function generateRandomCode(length) {
                const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                let code = "";
                for (let i = 0; i < length; i++) {
                    const randomIndex = Math.floor(Math.random() * characters.length);
                    code += characters.charAt(randomIndex);
                }
                return code;
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('active_from');
            const endDateInput = document.getElementById('active_to');

            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
                validateEndStartDate();
            });

            endDateInput.addEventListener('change', function() {
                startDateInput.max = this.value;
                validateEndStartDate();
            });

            function validateEndStartDate() {
                if (endDateInput.value && startDateInput.value > endDateInput.value) {
                    endDateInput.setCustomValidity('End date must be greater than or equal to start date.');
                } else {
                    endDateInput.setCustomValidity('');
                }
            }
        });


        function setDateFilter() {

            const endDate = document.getElementById('active_to');
            endDate.disabled = false;
            endDate.min = document.getElementById('active_from').value;
            endDate.value = "";
        }
        function changeTypeOfDiscount(value)
        {
            if(value == 'price')
            {
                $('#percentageDiv').hide();
                $('#priceDiv').show();
                percentageInput.value = '';
            }
            else
            {
                $('#percentageDiv').show();
                $('#priceDiv').hide();
                priceInput.value = '';
            }
        }
    </script>
@endsection
