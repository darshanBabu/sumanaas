@extends('layouts.app')

@section('content')
    @section('style')
    <style>
      body {
        text-align: center;
        background: #EBF0F5;
      }
        h1 {
          color: #e36a55;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-weight: 900;
          font-size: 40px;
          margin-bottom: 10px;
        }
        p {
          color: #404F5E;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-size:20px;
          margin: 0;
        }
      i {
        color: #e36a55;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
      }
      .card {
        background: white;
        padding: 60px;
        border-radius: 3px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
      }
      .btn{
        padding : 10px;
        border-radius:10px;
        margin:3%;
      }
    </style>
    @endsection
    <div class="card">
      <div style="border-radius:200px; height:200px; width:200px; background: #f1eceb; margin:0 auto;">
      <i class="warning icon">!</i>
      </div>
        <h1> Oops! Something went wrong.</h1> 
        <p>
        <a href = "{{ route('home')}}"><button  id="card-button"  class="btn btn-primary">Home</button> </a>
        <a href = "{{ route('productDetails')}}/{{ $product_id }}"><button  id="card-button"  class="btn btn-success">Retry Payment</button> </a>
        </p>
      </div>
@endsection
      