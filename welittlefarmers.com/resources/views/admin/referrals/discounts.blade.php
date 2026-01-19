@extends('admin.layout')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Manage Discount Codes</h3>
                <a href="{{ route('admin.referrals.settings') }}" class="btn btn-secondary">Back to Settings</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Create Form -->
                <div class="card mb-4">
                    <div class="card-header">Create New Code</div>
                    <div class="card-body">
                        <form action="{{ route('admin.referrals.discounts.store') }}" method="POST" class="row">
                            @csrf
                            <div class="col-md-2 mb-2">
                                <label class="small text-muted">Coupon Code</label>
                                <input type="text" name="code" class="form-control" placeholder="e.g. SUMMER" required>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="small text-muted">Type</label>
                                <select name="type" class="form-control">
                                    <option value="percent">Percent(%)</option>
                                    <option value="flat">Flat (Fixed Amount)</option>
                                </select>
                            </div>
                            <div class="col-md-1 mb-2">
                                <label class="small text-muted">Val (INR)</label>
                                <input type="number" step="0.01" name="value_inr" class="form-control" placeholder="500" required>
                            </div>
                            <div class="col-md-1 mb-2">
                                <label class="small text-muted">Val (USD)</label>
                                <input type="number" step="0.01" name="value_usd" class="form-control" placeholder="5" required>
                            </div>
                            <div class="col-md-1 mb-2">
                                <label class="small text-muted">Min (INR)</label>
                                <input type="number" name="min_order_inr" class="form-control" placeholder="0" value="0">
                            </div>
                            <div class="col-md-1 mb-2">
                                <label class="small text-muted">Min (USD)</label>
                                <input type="number" name="min_order_usd" class="form-control" placeholder="0" value="0">
                            </div>
                            <div class="col-md-1 mb-2"> <!-- New Field -->
                                <label class="small text-muted">Limit</label>
                                <input type="number" name="max_usage" class="form-control" placeholder="Inf">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="small text-muted">Expires</label>
                                <input type="date" name="expires_at" class="form-control">
                            </div>
                            <div class="col-md-1 mb-2">
                                <label class="small text-muted">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block w-100">Add</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- List -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Value</th>
                            <th>Min Order</th>
                            <th>Usage</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($discounts as $discount)
                        <tr>
                            <td>{{ $discount->code }}</td>
                            <td>
                                @if($discount->type == 'percent')
                                    {{ $discount->value_inr }}%
                                @else
                                    ₹{{ $discount->value_inr }} / ${{ $discount->value_usd }}
                                @endif
                            </td>
                            <td>
                                @if($discount->min_order_inr > 0 || $discount->min_order_usd > 0)
                                    ₹{{ $discount->min_order_inr }} / ${{ $discount->min_order_usd }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $discount->usage_count }} / {{ $discount->max_usage ?? '∞' }}</td>
                            <td>
                                <span class="badge {{ $discount->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $discount->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.referrals.discounts.toggle', $discount->id) }}" class="btn btn-sm btn-warning">
                                    {{ $discount->is_active ? 'Disable' : 'Enable' }}
                                </a>
                                <a href="{{ route('admin.referrals.discounts.delete', $discount->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this code?');">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
