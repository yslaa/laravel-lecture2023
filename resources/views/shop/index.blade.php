@extends('layouts.master')
@section('title')
  laravel shopping cart
@endsection
@section('content')
@include('layouts.flash-messages')
@if(Session::has('message'))
<div class="alert alert-danger alert-dismissible  show" role="alert">
  <strong>{{Session::get('message')}}</strong> 
  <button type="button" class="close" data-dismiss="alert " aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
    {{-- @foreach ($items->chunk(4) as $itemChunk) --}}
        <div class="row">
            @foreach ($items as $item)
                <div class="col-sm-6 col-md-4">
                  <div class="thumbnail">
                    <img src="{{ $item->image_path }}" alt="..." class="img-responsive">
                    <div class="caption">
                           <h3>{{ $item->title }}<span>${{ $item->sell_price }}</span></h3>
                      <p>{{ $item->description }}</p>
                       <div class="clearfix">
                           <a href="{{ route('addToCart', ['id'=>$item->item_id]) }}" class="btn btn-primary" role="button"><i class="fas fa-cart-plus"></i> Add to Cart</a> <a href="#" class="btn btn-default pull-right" role="button">

                            <i class="fas fa-info"></i> More Info</a>
                      </div>
                    </div>
                  </div>
                </div>
            @endforeach
    {{-- @endforeach --}}
<div>{{$items->links()}}</div>
@endsection