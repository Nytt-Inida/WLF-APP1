@extends('main')

@section('content')
<section class="payment-cancel-area pt-10 pb-35">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2 class="mb-4">Payment Canceled</h2>
                <p>Your payment was canceled. If this was unintentional, you can try again by going back to the payment page.</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">Return to Home</a>
            </div>
        </div>
    </div>
</section>
<style>
    .payment-cancel-area {
        padding: 40px;
        text-align: center;
    }
    .payment-cancel-area h2 {
        color: #ff4d4d; /* Customize color as needed */
    }
</style>

@endsection
