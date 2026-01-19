@extends('admin.layout')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Referral System Settings</h3>
                <div>
                     <a href="{{ route('admin.referrals.discounts') }}" class="btn btn-info">Manage Discount Codes</a>
                     <a href="{{ route('admin.referrals.logs') }}" class="btn btn-secondary">View Logs</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.referrals.settings.update') }}" method="POST">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label>Enable Referral System</label>
                        <select name="is_enabled" class="form-control">
                            <option value="1" {{ ($settings['is_enabled'] ?? '') == '1' ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ ($settings['is_enabled'] ?? '') == '0' ? 'selected' : '' }}>Disabled</option>
                        </select>
                        <small class="text-muted">If disabled, the referral code field will be hidden on signup and no rewards will be processed.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                             <h4>Referrer Reward (Person inviting)</h4>
                             <div class="form-group mb-2">
                                 <label>Reward Type</label>
                                 <select name="referrer_reward_type" class="form-control">
                                     <option value="discount" selected>Discount Coupon</option>
                                 </select>
                             </div>
                             <div class="form-group mb-2">
                                 <label>Discount Value (% off)</label>
                                 <input type="number" name="referrer_reward_value" class="form-control" value="{{ $settings['referrer_reward_value'] ?? '20' }}">
                                 <small class="text-muted">Enter the <strong>percentage</strong> discount for the coupon generated for the referrer (e.g., 20 for 20% off). This coupon will be emailed to them.</small>
                             </div>
                        </div>

                        <div class="col-md-6">
                            <h4>New User Reward (Person joining)</h4>
                            <div class="form-group mb-2">
                                <label>Discount Type</label>
                                <select name="referee_discount_type" class="form-control">
                                    <option value="percent" {{ ($settings['referee_discount_type'] ?? '') == 'percent' ? 'selected' : '' }}>Percentage</option>
                                    <option value="flat" {{ ($settings['referee_discount_type'] ?? '') == 'flat' ? 'selected' : '' }}>Flat Amount</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label>Discount Value</label>
                                <input type="number" name="referee_discount_amount" class="form-control" value="{{ $settings['referee_discount_amount'] ?? '10' }}">
                                <small class="text-muted">Enter the value to be deducted. If Percentage, enter e.g. 10 for 10%. If Flat Amount, enter e.g. 50 for $50 off.</small>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
