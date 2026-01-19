@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Site Settings</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Homepage Special Offer</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="stickyBarEnabled" name="sticky_bar_enabled" {{ $stickyBarEnabled ? 'checked' : '' }}>
                        <label class="custom-control-label" for="stickyBarEnabled">Enable Limited Time Offer Bar</label>
                    </div>
                    <small class="form-text text-muted">Toggle the sticky bottom bar on the homepage.</small>
                </div>

                <div class="form-group mt-3">
                    <label for="stickyBarCoupon">Coupon Code to Promote</label>
                    <input type="text" class="form-control" id="stickyBarCoupon" name="sticky_bar_coupon_code" value="{{ $stickyBarCoupon }}" placeholder="e.g. SAVE99">
                    <small class="form-text text-muted">If set, the sticky bar will say "Use code [CODE]..."</small>
                </div>

                <div class="form-group mt-3">
                    <label for="stickyBarPercent">Discount Percentage (%)</label>
                    <input type="number" class="form-control" id="stickyBarPercent" name="sticky_bar_discount_percent" value="{{ $stickyBarPercent }}" step="1" min="0" max="100" placeholder="e.g. 50">
                    <small class="form-text text-muted">If set, text will highlight "X% OFF". Takes priority over price display.</small>
                </div>

                <hr class="my-4">

                <h6 class="font-weight-bold text-primary mb-3">Course & Currency Management</h6>
                <div class="row">

                    <div class="col-md-4 mt-3">
                        <div class="form-group">
                            <label for="coursePrice">Indian Price (INR)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" id="coursePrice" name="course_price" value="{{ $mainCourse->price ?? 0 }}" step="1" min="0">
                            </div>
                            <small class="form-text text-muted">Price for Indian users.</small>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="form-group">
                            <label for="coursePriceUsd">International Price (USD)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="coursePriceUsd" name="course_price_usd" value="{{ $mainCourse->price_usd ?? 0 }}" step="0.01" min="0">
                            </div>
                            <small class="form-text text-muted">Price for International users.</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection
