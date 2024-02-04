@extends('dashboard.layouts.index')

@section('content')
<div class="card p-3">
    <form>
        <div class="text-center">
            <h3>
                Pricing Rules
            </h3>
        </div>
        <h5>
            Hourly
        </h5>
        <hr>
        <div class="row">
            <div class="mb-3 col-3">
                <label for="minmum_hours" class="form-label">Minmum Hours</label>
                <input type="number" class="form-control" id="minmum_hours" placeholder="add minmum hours">
            </div>
            <div class="mb-3 col-3">
                <label for="mile_per_hour" class="form-label">Mile Per Hour</label>
                <input type="number" class="form-control" id="mile_per_hour" placeholder="add mile per hour">
            </div>
            <div class="mb-3 col-3">
                <label for="price_per_hour" class="form-label">Price Per Hour</label>
                <input type="number" class="form-control" id="price_per_hour" placeholder="add price per hour">
            </div>
            <div class="mb-3 col-3">
                <label for="price_per_extra_mile" class="form-label">Price Per Extra Mile</label>
                <input type="number" class="form-control" id="price_per_extra_mile" placeholder="add price per extra mile">
            </div>
        </div>
       
            <h5 class="mt-2">
            Point to point
        </h5>
        <hr>
        <div class="row">
            <div class="col-4">
                <div class="mb-3">
                    <label for="initial_fee" class="form-label">Initial Fee</label>
                    <input type="number" class="form-control" id="initial_fee" placeholder="add initial fee">
                </div>
            </div>
            <div class="col-4">
                <div class="mb-3">
                    <label for="minimum_mile" class="form-label">Minimum mile</label>
                    <input type="number" class="form-control" id="minimum_mile" placeholder="add minimum mile">
                </div>
            </div>
            <div class="col-4">
                <div class="mb-3">
                    <label for="extra_price_per_mile" class="form-label">Extra price per mile</label>
                    <input type="number" class="form-control" id="extra_price_per_mile" placeholder="add extra price per mile">
                </div>
            </div>
        </div>
       
       
        <div class="text-right">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection