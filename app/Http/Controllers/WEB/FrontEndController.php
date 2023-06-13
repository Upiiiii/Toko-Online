<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class FrontEndController extends Controller
{
    public function index()
    {
        $nameCategory = Category::latest()->get();

        $product = Product::with(['galleries'])->latest()->get();

        return view('pages.frontend.index', compact('nameCategory', 'product'));
    }

    public function detailProduct($slug)
    {
        $nameCategory = Category::latest()->get();

        $product = Product::with(['galleries'])->where('slug', $slug)->firstOrFail();

        $category = Category::where('id', $product->category_id)->firstOrFail();

        $recomendation = Product::with(['galleries'])->inRandomOrder()->limit(4)->get();

        return view('pages.frontend.detail', compact('product','nameCategory', 'recomendation', 'category'));
    }

    public function detailCategory($slug)
    {
        $nameCategory = Category::latest()->get();

        $category = Category::where('slug', $slug)->firstOrFail();

        $product = Product::with(['galleries'])->where('category_id', $category->id)->latest()->get();

        return view('pages.frontend.detail_category', compact('nameCategory', 'category', 'product'));
    }

    public function cart(Request $request)
    {
        $nameCategory = Category::latest()->get();

        $cart = Cart::with(['product.galleries'])->where('users_id', Auth::user()->id)->get();

        return view('pages.frontend.cart', compact('nameCategory', 'cart'));
    }

    public function cartAdd(Request $request, $id)
    {
    
        Cart::create([
            'users_id' => Auth::user()->id,
            'products_id' => $id
        ]);

        return redirect('cart');

    }

    public function cartDelete($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return redirect('cart');
    }

    public function checkout(CheckoutRequest $request)
    {
        $data = $request->all();

        //return $request->all();

        $cart = Cart::with(['product'])->where('users_id', Auth::user()->id)->get();

        $data['users_id'] = Auth::user()->id;
        $data['total_price'] = $cart->sum('product.price');

        $transaction = Transaction::create($data);

        foreach($cart as $row)
        {
            $item[] = TransactionItem::create([
                'users_id' => Auth::user()->id,
                'products_id' => $row->products_id,
                'transactions_id' => $transaction->id
            ]);
        }
        //delete cart ketika selesai membuat transaksi
        Cart::where('users_id', Auth::user()->id)->delete();

        //Configuration Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //buat array untuk dikirim ke midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => 'ML-' . $transaction->id,
                'gross_amount' => (int) $transaction->total_price
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email
            ],
            'enabled_payments' => [
                'gopay', 'bank_transfer'
            ],
            'vtweb' => []
        ];

        try {
            // Get Snap Payment Page URL
            $paymentUrl = \Midtrans\Snap::createTransaction($midtrans)->redirect_url;
            
            $transaction->payment_url = $paymentUrl;
            $transaction->save();
          }
          catch (Exception $e) {
            echo $e->getMessage();
          }
    }
}
