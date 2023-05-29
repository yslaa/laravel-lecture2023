<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Models\Item;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $customer = Customer::find(1);
        // // dump($customer->orders);
        // foreach($customer->orders as $order) {
        //     dump($order->orderinfo_id,$order->date_placed);
        // }

        // $order = Order::find(1);
        // dump($order->customer->title, $order->customer->fname, $order->customer->addressline);
        // $user = User::find(5)->customer;
        // dump($user->lname, $user->fname);
        // $user = Customer::find(17)->user;
        // dump($user->name, $user->email);
        // $items = Order::find(1)->items;
        // // dump($items);
        // foreach($items as $item) {
        //     dump($item->description, $item->sell_price);
        // }

        // $orders = Item::find(4);
        // $items = Item::all();
        // dump($items->orders());
        // foreach($items as $item) {
        //     dump($item->description, $item->sell_price);
        // }
        // foreach($items as $item) {
        //     // dump($item->orders);
        //     foreach($item->orders as $order) {
        //         dump($order->orderinfo_id);
        //     }
        // }

        //  $orders = Order::all();
        // // dump($orders->items);
        // foreach($orders as $order) {
        //     dump($order->items);
        // }
        // $customers = Customer::with('orders')->get();
        // dump($customers);
        // foreach($customers as $customer){
        //     dump($customer->lname);
        //     foreach($customer->orders as $order) {
        //         dump($order->orderinfo_id, $order->date_placed);
        //     }
        // }

        $orders = Order::with(['customer','items'])->where('orderinfo_id', 1)->get();
        dump($orders);
        foreach($orders as $order){
            dump($order->customer->customer_id, $order->customer->lname);
            foreach($order->items as $item){
                dump($item->description, $item->sell_price);
            }
            
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
