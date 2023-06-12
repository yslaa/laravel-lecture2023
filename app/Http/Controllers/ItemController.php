<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Customer;
use App\Imports\ItemImport;
use App\Imports\ItemStockImport;
use App\Imports\ItemCustomerSheetImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Rules\ItemExcelRule;
use Barryvdh\Debugbar\Facade as DebugBar;

use Session;
use App\Cart;
use DB;
use App\DataTables\ItemsDataTable;
use App\Events\OrderCreated;
use Auth;
use Redirect;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ItemsDataTable $dataTable)
    {
        return $dataTable->render('items.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
       
        $item = new Item();
        $item->title = $request->title;
        $item->description = trim($request->description);
        $item->sell_price = $request->sell;
        $item->cost_price = $request->cost;
        // $item->image_path = $request->cost;
        $item->save();
        
       
        if($request->document !== null) { 
            foreach ($request->input("document", []) as $file) {
                $item
                    ->addMedia(storage_path("item/images/" . $file))
                    ->toMediaCollection("images");
            }
        }
        return Redirect::to("item")->with(
            "success",
            "Item added successfully!"
        );
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
        $item = Item::find($id);
        $images = $item->getMedia('images');
        // dd($images);
        // foreach($images as $image) {
        //     if($image[0] !== null) {
        //         DebugBar::info($image[0]->getPath());
        //     }
        //     // DebugBar::info($image[0]);
        // }
       
       return view('items.edit', compact('item', 'images'));
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

    public function getItems()
    {
        // $items = Item::all();
        // $items = DB::table('item')->join('stock','item.item_id', '=', 'stock.item_id')->get();
        $items = Item::with('stock')
            ->whereHas('stock')
            ->paginate(4);
        return view('shop.index', compact('items'));
    }
    public function addToCart($id)
    {
        // dd(Session::has('cart'));
        $item = Item::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        // dd($oldCart);
        $cart = new Cart($oldCart);
        // dd($cart);
        $cart->add($item, $item->item_id);
        // $request->session()->put('cart', $cart);
        // dd($cart);
        Session::put('cart', $cart);
        // dd(Session::get('cart'));
        // $request->session()->save();
        // Session::save();
        // dump( Session::get('cart'));
        return redirect()
            ->route('getItems')
            ->with('message', 'item added to cart');
    }
    public function getCart()
    {
        if (!Session::has('cart')) {
            return view('shop.shopping-cart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        // dd($oldCart);
        return view('shop.shopping-cart', [
            'items' => $cart->items,
            'totalPrice' => $cart->totalPrice,
        ]);
    }

    public function removeItem($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
            // Session::save();
        } else {
            Session::forget('cart');
        }
        return redirect()->route('shoppingCart');
    }
    public function getReduceByOne($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->reduceByOne($id);
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }

        return redirect()->route('shoppingCart');
    }

    public function postCheckout(Request $request)
    {
        if (!Session::has('cart')) {
            return redirect()->route('item.index');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        // dd($cart);
        try {
            DB::beginTransaction();

            // dd(Auth::id());
            if (Auth::check()) {
                $customer = Customer::where('user_id', Auth::id())->first();
            } else {
                return redirect()
                    ->route('user.signin')
                    ->with('info', 'Please Login before Checkout');
            }

            // dd($customer);
            // $customer->orders()->save($order);
            // $order->customer_id = $customer->customer_id;
            $order = new Order();
            // $order->customer_id = $customer->customer_id;
            $order->date_placed = now();
            $order->date_shipped = now();
            // $order->shipvia = 1;
            $order->shipping = 10.0;
            $order->status = 'Processing';
            $order->save();
            $customer->orders()->save($order);
            // dd($order);
            foreach ($cart->items as $items) {
                $id = $items['item']['item_id'];
                // dd($id);
                // DB::table('orderline')->insert([
                //     'item_id' => $id,
                //     'orderinfo_id' => $order->orderinfo_id,
                //     'quantity' => $items['qty'],
                // ]);
                $order->items()->attach($id, ['quantity' => $items['qty']]);
                $stock = Stock::find($id);
                $stock->quantity = $stock->quantity - $items['qty'];
                $stock->save();
            }

            // dd($order);
        } catch (\Exception $e) {
            // dd($e);
            DB::rollback();
            // dd($order);
            return redirect()
                ->route('shoppingCart')
                ->with('error', $e->getMessage());
        }
        DB::commit();
        OrderCreated::dispatch($order, $customer, Auth::user()->email);
        Session::forget('cart');

        return redirect()
            ->route('getItems')
            ->with('success', 'Successfully Purchased Your Products!!!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'item_upload' => [
                'required',
                new ItemExcelRule($request->file('item_upload')),
            ],
        ]);

        // Excel::import(
        //     new ItemStockImport(),
        //     request()
        //         ->file('item_upload')
        //         ->store('temp')
        // );

        Excel::import(
            new ItemCustomerSheetImport(),
            request()
                ->file('item_upload')
                ->storeAs(
                    'files',
                    request()
                        ->file('item_upload')
                        ->getClientOriginalName()
                )
        );
        // Excel::import(new FirstSheetImport, request()->file('item_upload')->store('temp'));
        return redirect()
            ->back()
            ->with('success', 'Excel file Imported Successfully');
    }

    public function storeMedia(Request $request)
    {
        $path = storage_path("item/images");
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file = $request->file("file");
        $name = uniqid() . "_" . trim($file->getClientOriginalName());
        $file->move($path, $name);

        return response()->json([
            "name" => $name,
            "original_name" => $file->getClientOriginalName(),
        ]);
    }
    
}
