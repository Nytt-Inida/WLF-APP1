@extends('admin.layout')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Referral Logs</h3>
                <a href="{{ route('admin.referrals.settings') }}" class="btn btn-secondary">Back to Settings</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Referrer</th>
                            <th>Referred User</th>
                            <th>Status</th>
                            <th>Reward Details</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referrals as $referral)
                        <tr>
                            <td>{{ $referral->referrer->name ?? 'Unknown' }} <br> <small>{{ $referral->referrer->email ?? '' }}</small></td>
                            <td>{{ $referral->referredUser->name ?? 'Unknown' }} <br> <small>{{ $referral->referredUser->email ?? '' }}</small></td>
                            <td>
                                <span class="badge {{ $referral->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($referral->status) }}
                                </span>
                            </td>
                            <td>
                                Type: {{ $referral->reward_type }} <br>
                                Code: <strong>{{ $referral->reward_coupon_code }}</strong>
                            </td>
                            <td>{{ $referral->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No referrals found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                {{ $referrals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
