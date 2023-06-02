@extends('layouts.app')
@section('style')
    <style>
        .card-img-top {
          border-radius: 20px;
        }
    </style>

    @endsection
@section('content')
<div class="container">
    <h2>Product Listing</h2>
    <div class="row">
    @foreach($products as $product)
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
          <img src="{{ asset('product.jpeg') }}" title = "{{ $product->Name }}" class="card-img-top" alt="Product 1">
          <div class="card-body">
            <h5 class="card-title">{{ $product->Name }}</h5>
            <p class="card-text">{{ $product->Description }}</p>
            <p class="card-text">&#8377; {{ $product->Price }}</p>
            <a href="{{ route('productDetails') }}/{{ base64_encode($product->id) }}" class="btn btn-primary">Buy Now</a>
          </div>
        </div>
      </div>
    @endforeach
      <!-- Add more product cards here -->
    </div>
  </div>
@endsection