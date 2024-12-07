<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\User;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use DB;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaymentController extends Controller
{
    public function triggerPayment($order_id, $total_amount)
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->where('order_id', null)->get()->toArray();

        $data = [];
        $data['items'] = array_map(function ($item) {
            $name = Product::where('id', $item['product_id'])->pluck('title')->first();
            return [
                'name' => $name,
                'price' => $item['price'],
                'desc' => 'Thank you for using Paystack',
                'qty' => $item['quantity']
            ];
        }, $cart);

        $total = 0;
        foreach ($data['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        if (session('coupon')) {
            $total -= session('coupon')['value'];
        }

        Cart::where('user_id', $user->id)->where('order_id', null)->update(['order_id' => session()->get('id')]);

        $paystackData = [
            "amount" => $total_amount * 100,
            "reference" => Paystack::genTranxRef(),
            "email" => $user->email,
            "currency" => "KES",
            "channels" => ['card', 'mobile_money'],
            "metadata" => [
                "user_id" => $user->id,
                "order_id"=> $order_id,
            ],
        ];

//        dd($paystackData);

        return Paystack::getAuthorizationUrl($paystackData)->redirectNow();
    }

    public function paystackCallback()
    {
        $transactionDetails = Paystack::getPaymentData();
        $status = $transactionDetails['data']['status'];
        $userId = $transactionDetails['data']['metadata']['user_id'];
        $order_num = $transactionDetails['data']['metadata']['order_id'];
        $user = User::find($userId);

//        dd($order_num);
        if ($status == "success") {


            // Update the payment_status in the Order table
            Order::where('id', $order_num)
                ->where('user_id', $userId)
                ->update(['payment_status' => 'paid']);

            Cart::where('user_id', $userId)->delete();

            session()->flash('success', 'Payment was successful. Your order has been confirmed.');

            return redirect()->route('home');
        } elseif ($status ==  'cancelled'){

            Order::where('id', $order_num)
                ->where('user_id', $userId)
                ->update(['payment_status' => 'unpaid']);

            session()->flash('error', 'Payment was canceled!');

            return redirect()->route('home');
        } else{
            session()->flash('error', 'Payment failed!');

            // Redirect back to the home page
            return redirect()->route('home');
        }
    }

}
