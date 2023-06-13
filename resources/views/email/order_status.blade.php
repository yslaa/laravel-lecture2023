@extends('layouts.master')
@section('content')
    <p>{{ $orderId }}</p>
    <table class="table responsive table-striped">
        <thead>
            <tr>
                <th scope="col">item name</th>
                <th scope="col">price</th>
                <th scope="col">quantity</th>
                <th scope="col">total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <th scope="row">{{ $item->description }}</th>
                    <td>{{ $item->sell_price }}</td>
                    <td>{{ $item->pivot->quantity }}</td>
                    <td>{{ $item->sell_price * $item->pivot->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h2>{{ $orderTotal }}</h2>
@endsection
