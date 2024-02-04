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
        Edit #{{ $coupon->id }}
    </h3>
    <hr>
    <form enctype="multipart/form-data" action="{{ route('dashboard.coupons.update',$coupon->id) }}" id="AddForm" method="post">
        @csrf
     
          {{--  coupon name --}}
          <div class="row  flex-row">
            <div class="col-md-6">

                <div class="form-group  p-3">
                    <label for="coupon_name">coupon name</label>
                    <input type="text" class="form-control" id="coupon_name" name="coupon_name"
                        value="{{ isset($coupon->coupon_name) ? $coupon->coupon_name: ''}}">
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
                        value="{{ isset($coupon->coupon_code) ? $coupon->coupon_code: ''}}">
                        </div>
                        <div class="col-md-6">
                        <button id="generate_button" class="btn btn-primary remove-border" type="button">Generate Code</button>
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
                        value="{{ isset($coupon->usage_limit) ? $coupon->usage_limit: ''}}">
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
                    <input type="number" id="percentage_discount" class="form-control" name="percentage_discount" step="0.1"
                        value="{{ isset($coupon->percentage_discount) ? $coupon->percentage_discount: ''}}">
                </div>
            </div> --}}
         
        </div>

        {{-- active_from --}}
        <div class="row  flex-row">
            <div class="col-md-6">
                <div class="form-group  p-3">
                    <label for="active_from"> Active from
                        <strong class="text-danger">
                            *
                            @error('active_from')
                                -
                                {{ $message }}
                            @enderror
                        </strong>
                    </label>
                    <input type="date" id="active_from" class="form-control" name="active_from" onchange="setDateFilter()"
                        value="{{ isset($coupon->active_from) ? $coupon->active_from->format('Y-m-d'): ''}}"  min="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group  p-3">
                    <label for="active_to"> Active To
                        <strong class="text-danger">
                            *
                            @error('active_to')
                                -
                                {{ $message }}
                            @enderror
                        </strong>
                    </label>
                    <input type="date" id="active_to" class="form-control" name="active_to"
                        value="{{ isset($coupon->active_to) ? $coupon->active_to->format('Y-m-d'): ''}}" min="{{ date('Y-m-d') }}" >
                </div>
            </div>
            
            <div class="col-6 mb-3">
                <label for="radius" class="form-label">Discount Type</label>
                <select id="discountType" onchange="changeTypeOfDiscount(event.target.value)" class="form-control" name="discount_type">
                    <option {{ $coupon->discount_type == 'Price'?'selected':'' }} value="price">Price</option>
                    <option {{ $coupon->discount_type == 'Percentage'?'selected':'' }} value="percentage">Percentage</option>
                </select>
            </div>
            <div id="priceDiv" class="col-6" style={{ $coupon->discount_type == 'Percentage'?"display:none;":"" }}>
                <label for="radius" class="form-label">Price</label>
                <input id="price" value="{{ $coupon->discount_type == 'Price'?$coupon->percentage_discount:'' }}" name="price" required min="1" type="number" class="form-control">
            </div>
            <div id="percentageDiv" style={{ $coupon->discount_type == 'Price'?"display:none;":"" }} class="col-6" >
                <label for="radius" class="form-label">Percentage</label>
                <input value="{{ $coupon->discount_type == 'Percentage'?$coupon->percentage_discount:'' }}" name="percentage" id="percentage" required min="1" max="100" type="number" class="form-control">
            </div>

         
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
               
                
                    $('form').attr('action', '{{ route("dashboard.coupons.update",$coupon->id) }}');

                
                $('#AddForm').submit();

            }

    </script>
 
      
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const generateButton = document.getElementById("generate_button");
        const generatedCodeInput = document.getElementById("generated_code");

        generateButton.addEventListener("click", function () {
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

        const discountTypeInput = document.getElementById('discountType');
        const percentageInput = document.getElementById('percentage');
        const priceInput = document.getElementById('price');

    document.addEventListener('DOMContentLoaded', function() {



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