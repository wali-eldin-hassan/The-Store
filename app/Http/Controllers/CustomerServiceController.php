<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CustomerServiceStoreRequest;
use App\Http\Requests\UpdateCustomerServiceRequest;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\User;
use Illuminate\Http\Request;

class CustomerServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::latest()->paginate(100);

        return inertia()->render('Dashboard/customer-services/index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        $categories = Category::with('subcategories')->get();
        $products->map(function ($product) {
            $product['image'] = $product->image;
            $product['selected'] = false;
            $product['quantity'] = 0;
            return $product;
        });

        return inertia()->render('Dashboard/customer-services/create', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerServiceStoreRequest $request)
    {
        $request->validated();

        $order = Order::create([
            'customer_phone' => $request->customer_phone,
            'customer_alt_phone' => $request->customer_alt_phone,
            'customer_address' => $request->customer_address,
            'discount' => $request->discount,
            'note' => $request->note,
            'user_id' => auth()->id(),
        ]);

        $order->setOrderDetails($request->orderDetails);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'تم أضافة الطلب'
        ]);

        return redirect()->route('customer.services.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {

        $orderDetails = OrderDetail::where('order_id', $order->id)->get();

        $orderDetails->map(function ($orderDetail) {
            $orderDetail['image'] = $orderDetail->product->image;
            return $orderDetail;
        });

        return inertia()->render('Dashboard/deliveries/show', [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $products = Product::all();
        $categories = Category::with('subcategories')->get();
        $orderDetails = OrderDetail::where('order_id', $order->id)->get();

        $orderDetails->map(function ($orderDetail) {
            $orderDetail['image'] = $orderDetail->product->image;
            $orderDetail['selected'] = true;
            return $orderDetail;
        });
        return inertia()->render('Dashboard/customer-services/edit', [
            'products' => $products,
            'categories' => $categories,
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerServiceRequest $request, Order $order)
    {
        $request->validated();

        $order->update([
            'customer_phone' => $request->customer_phone,
            'customer_alt_phone' => $request->customer_alt_phone,
            'customer_address' => $request->customer_address,
            'discount' => $request->discount,
            'note' => $request->note,
            'user_id' => auth()->id(),
        ]);
        
        session()->flash('toast', [
            'type' => 'success',
            'message' => 'تم تعديل الطلب بنجاح'
        ]);

        return redirect()->route('customer.services.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        session()->flash('toast', [
            'type' => 'success',
            'message' => 'تم حذف الطلب'
        ]);
        return redirect()->route('customer.service');
    }
}
