@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Payment for {{ $event->title }}</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h5>Registration Details</h5>
                        <p class="mb-1">Event: {{ $event->title }}</p>
                        <p class="mb-1">Participant: {{ $registration->first_name }} {{ $registration->last_name }}</p>
                        <p class="mb-3">Amount: ₹{{ number_format($registration->amount, 2) }}</p>
                        
                        <button id="rzp-button" class="btn btn-primary">Pay Now</button>
                    </div>

                    <form id="payment-form" action="{{ route('user.events.payment.handle') }}" method="POST" class="d-none">
                        @csrf
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id" value="{{ $registration->razorpay_order_id }}">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('rzp-button').onclick = function(e) {
    var options = {
        "key": "{{ config('services.razorpay.key') }}", 
        "amount": "{{ $registration->amount * 100 }}", 
        "currency": "INR",
        "name": "{{ config('app.name') }}",
        "description": "Registration for {{ $event->title }}",
        "order_id": "{{ $registration->razorpay_order_id }}",
        "handler": function (response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.getElementById('payment-form').submit();
        },
        "prefill": {
            "name": "{{ $registration->first_name }} {{ $registration->last_name }}",
            "email": "{{ $registration->email }}",
            "contact": "{{ $registration->mobile }}"
        },
        "theme": {
            "color": "#3399cc"
        }
    };
    var rzp = new Razorpay(options);
    rzp.open();
    e.preventDefault();
}
</script>
@endpush 