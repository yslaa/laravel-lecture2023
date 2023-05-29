@extends('layouts.master')
@section('content')

    <div class="row d-flex justify-content-between">
        <div class="col-12 col-lg-8 mt-5 order-details">
            <h1 class="my-5">Order # {{$order->orderinfo_id}}</h1>
            <h4 class="mb-4">Shipping Info</h4>
            <p><b>Name:</b> {{Auth::user()->name}}</p>
            <p><b>Phone:</b> {{$order->customer->phone}}</p>
            <p class="mb-4"><b>Address:</b>{{$order->customer->addressline}}</p>
            <p><b>Amount:</b>{{$total}} </p>
            <hr />
            <h4 class="my-4">Order Status:</h4>
            <p class="" ><b></b>{{$order->status}}</p>
            <h4 class="my-4">Order Items:</h4>
            <hr />
            @foreach($order->items as $item)
                <div class="cart-item my-1">
                    <div  class="row my-5">
                        <div class="col-4 col-lg-2">
                            <img src="{{$item->image_path}}" alt="" height="45" width="65" />
                        </div>
                        <div class="col-5 col-lg-5">
                            <a href="">{{$item->description}}</a>
                        </div>
                        <div class="col-4 col-lg-2 mt-4 mt-lg-0">
                            <p>{{$item->sell_price}}</p>
                        </div>
                        <div class="col-4 col-lg-3 mt-4 mt-lg-0">
                            <p> {{$item->pivot->quantity}}Piece(s)</p>
                        </div>
                    </div>
                </div>
            @endforeach
            <hr />
        </div>
    </div>
@endsection