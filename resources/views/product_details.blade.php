@extends('layouts.app')

@section('content')
    @section('style')
    <style>
        .card{
            'border-radius': 10px;
        }
    </style>

    @endsection
<div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('product.jpeg') }}" title = "{{ $product->Name }}" alt="{{ $product->Name }}" class="img-fluid">
            </div>
            <div class="col-md-6">
                <h2>{{ $product->Name }}</h2>
                <p>{{ $product->Description }}</p>
                <h3>Price: &#x20b9;{{ $product->Price }}</h3>
    <form action="{{ route('payment.charge') }}" method="POST" id="subscribe-form">
    <div class="form-group">
        <label for="card-number">Card Holder Name</label>
        <input type="text" id="card-holder-name" name="card-holder-name" class="form-control" value = "{{ ucFirst($user->name) }}">
        <input type="hidden" name="product_id" value="{{ base64_encode($product->id) }}">
    </div>
        @csrf
        <div class="form-row">
            <label for="card-element">Credit or debit card</label>
            <div id="card-element" class="form-control">
            </div>
            <!-- Used to display form errors. -->
            <div id="card-errors" role="alert"></div>
        </div>
        <div class="stripe-errors"></div>
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            {{ $error }}<br>
            @endforeach
        </div>
        @endif
        <br/>
        <div class="form-group text-center">
            <button  id="card-button" data-secret="{{ $intent->client_secret }}" class="btn btn-lg btn-success btn-block">Pay & Checkout</button>
        </div>
    </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };
    var card = elements.create('card', {hidePostalCode: true,
        style: style});
    card.mount('#card-element');
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;
    cardButton.addEventListener('click', async (e) => {
        e.preventDefault();
        console.log("attempting");
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: { name: cardHolderName.value }
                }
            }
            );
        if (error) {
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
        } else {
            paymentMethodHandler(setupIntent.payment_method);
        }
    });
    function paymentMethodHandler(payment_method) {
        var form = document.getElementById('subscribe-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'payment_method');
        hiddenInput.setAttribute('value', payment_method);
        form.appendChild(hiddenInput);
        form.submit();
    }
</script>
@endsection

