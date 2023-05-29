@extends('layouts.master')
@section('title')
    Laravel Shopping Cart
@endsection
@section('content')
@include('layouts.flash-messages')

    @if(Session::has('cart'))
        <div class="row">
            <div class="col-sm-6 col-md-6 col-md-offset-3 col-sm-offset-3">
                <ul class="list-group">
                    @foreach($items as $item)
                        <li class="list-group-item">
                           
                            <strong>{{ $item['item']['description'] }}</strong>
                            <span class="label label-success">{{ $item['item']['sell_price'] }}</span>
                            <span class="badge badge-primary">{{ $item['qty'] }}</span>
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                  <a class="dropdown-item" href="{{ route('item.reduceByOne',['id'=>$item['item']['item_id']]) }}">Reduce By 1</a>
                                  <a class="dropdown-item" href="{{ route('item.remove',['id'=>$item['item']['item_id']])}}">Reduce All</a>
                                </div>
                              </div>
                     
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-md-offset-3 col-sm-offset-3">
                <strong>Total: {{ $totalPrice }}</strong>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-md-offset-3 col-sm-offset-3">
                <a href="{{ route('checkout') }}" type="button" class="btn btn-success">Checkout</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-6 col-md-6 col-md-offset-3 col-sm-offset-3">
                <h2>No Items in Cart!</h2>
            </div>
        </div>
    @endif
@endsection