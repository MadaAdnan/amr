<style>
/* Style for the email suggestion container */
/* Style for the email suggestion container */
#emailResults {
    position: absolute;
    background-color: #fff;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Style for each email suggestion */
.email-suggestion {
    padding: 10px 15px;
    cursor: pointer;
    transition: background-color 0.2s;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
}

.email-suggestion:last-child {
    border-bottom: none;
}

.email-suggestion:hover {
    background-color: #f4f4f4;
}

/* Style for the email text */
.email-suggestion-text {
    flex: 1;
    margin-left: 10px;
    color: #333;
}

/* Style for the email icon */
.email-suggestion-icon {
    width: 20px;
    height: 20px;
    margin-right: 10px;
}


</style>
<div class="row mt-5">
    <div class="col-6 text-left d-flex align-items-center">
        <h5 class="align-middle">Customer Information</h5>
    </div>
    <div class="col-6 text-right mb-2">
        {{-- <button onclick="getCustomer()" type="button" class="btn btn-primary">Current Customer ?</button> --}}
    </div>
    <hr />
    <div class="col-6">
        <label for="firstNameInput">First Name
            <strong class="text-danger">
                *
            </strong>
        </label>
        <input type="text" class="form-control" name="first_name" id="firstNameInput" placeholder="add first name"
            value='{{ isset($reservation->users) ? $reservation->users->first_name : '' }}'
            @if (isset($pageType) && $pageType == 'edit') readonly="readonly" @endif>
            {{-- <div id="firstNme-error" class="error-container"></div> --}}

    </div>
    <div class="col-6">
        <label for="firstNameInput">Last Name
            <strong class="text-danger">
                *
            </strong>
        </label>
        <input type="text" class="form-control" name="last_name" id="lastNameInput" placeholder="add last name"
            value='{{ isset($reservation->users) ? $reservation->users->last_name : '' }}'
            @if (isset($pageType) && $pageType == 'edit') readonly="readonly" @endif>
            {{-- <div id="lastName-error" class="error-container"></div> --}}

    </div>
    <div class="col-6">
        <label for="emailInput">Email address
            <strong class="text-danger">
                *
            </strong>
        </label>
        <input type="email" class="form-control" required name="email" id="emailInput"  onkeyup="autoComplete()"
            placeholder="add customer email ex.name@example.com"
            value='{{ isset($reservation->users) ? $reservation->users->email : '' }}'
            @if (isset($pageType) && $pageType == 'edit') disabled @endif>
            <div id="email-error" class="error-container">
                <!-- Error message will be inserted here -->
            </div>
            <div id="emailResults"></div> <!-- Display available email suggestions here -->
    </div>
    <div class="col-6">
        <label for="phone"> Phone
            <strong class="text-danger">
                *
            </strong>
        </label>
        <input type="tel" id="phone" class="form-control" name="phone"
            value='{{ isset($reservation->users) ?$reservation->users->phone : '' }}'
            @if (isset($pageType) && $pageType == 'edit') disabled @endif>
        <div id="phoneInput-error" class="error-container">
            <!-- Error message will be inserted here -->
        </div>

    </div>

    {{-- <div class="col-6">
        <label for="customerId">Customer Stripe Id</label>
        <input id="customerId" type="text" class="form-control" name="customerId"
            placeholder="please add an customer id" disabled
            value="{{ isset($customer) && $customer->stripe_id ? $customer->stripe_id : '' }}"
            @if (isset($pageType) && $pageType == 'edit') readonly="readonly" @endif>
        <small class="form-text text-muted">Note: Use the customer Stripe ID to link an existing Stripe customer to a new Lavish Ride customer</small> --}}
        {{-- <small class="form-text text-muted">Note: If a customer ID is not provided, it will be generated automatically,
            and
            this action cannot be undone</small> --}}
    {{-- </div>

    {{-- <div class="col-6">
    <label for="paymentMethodSelect">Payment Methods</label>
    <select id="paymentMethodSelect" disabled class="form-control">
        <!-- Payment methods will be dynamically added here -->
    </select> --}}
    <div class="col-6" id='customerContainer'>
        <label for="customerId">Customer IDs</label>
        <select id="customerId" disabled class="form-control" name="customerId">
            @if (isset($pageType) && $pageType == 'edit')
                <option value="{{$reservation->users->stripe_id}}">{{$reservation->users->stripe_id}}</option>
            @endif
            <!-- Customer IDs will be dynamically added here -->
        </select>
    </div>
    
</div>
    {{-- <div class="col-6">
        <label for="emailInput">Payment Stripe Id</label>
        <input id="paymentId" type="text" class="form-control" name="payment_intent_id"
            placeholder="please add a payment id"
            value="{{ isset($reservation->payment_intent_id) ? $reservation->payment_intent_id : '' }}"
            @if (isset($pageType) && $pageType == 'edit') readonly="readonly" @endif>
        <small class="form-text text-muted">Note: The payment ID needs to be linked with the provided customer
            ID.</small>
    </div> --}}

    
    {{-- 
        <div class="col-6 mb-2">
        <label for="phone">Price</label>
        <input min="1" type="price" id="price" class="form-control" name="price" inputmode="numeric"
            value='{{ isset($reservation) ? $reservation->price + $reservation->tip : '' }}'  >
    </div>
    
    <div class="col-6 d-flex align-content-end flex-wrap">
        <button type="button" onclick="getLivePricing()" class="btn btn-primary">Get Live Pricing</button>
    </div> --}}

</div>
