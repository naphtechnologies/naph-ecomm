<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class FlutterwaveController extends Controller
{
    /**
     * Initialize Rave payment process
     * @return void
     */
    public function initialize()
    {
        //This generates a payment reference
        $reference = Flutterwave::generateReference();
        $cart = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->get()->toArray();
        $proddata = [];


        // return $cart;
        $proddata['items'] = array_map(function ($item) use ($cart) {
            $name = Product::where('id', $item['product_id'])->pluck('title');
            return [
                'name' => $name,
                'price' => $item['price'],
                'desc' => 'Thank you for using paypal',
                'qty' => $item['quantity']
            ];
        }, $cart);

        $total = 0;
        $prodname = '';
        foreach ($proddata['items'] as $item) {
            $total += $item['price'] * $item['qty'];
            $prodname = $item['name'];
        }
        $exchangeRate = 3752;

        // Assuming $total contains the amount in USD
        $totalInUSD = $total;

        // Convert USD to KSH and cast it as an integer to remove the decimal
        $totalInUgx = (int)($totalInUSD * $exchangeRate);

        // Update $data['total'] with the converted amount
        $proddata['total'] = $totalInUgx;
        $currentDate = date('jS F');
        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => $totalInUgx,
            'email' => auth()->user()->email,
            'tx_ref' => $reference,
            'currency' => "UGX",
            'redirect_url' => route('callback'),
            'customer' => [
                'email' => auth()->user()->email,
                "phone_number" => auth()->user()->contact_number,
                "name" => auth()->user()->name
            ],


            "customizations" => [
                "title" => $prodname,
                "description" => $currentDate
            ]
        ];

        $payment = Flutterwave::initializePayment($data);

        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return;
        }

        return redirect($payment['data']['link']);
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback()
    {

        $status = request()->status;

        //if payment is successful
        if ($status ==  'successful') {

            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

            dd($data);
        }
        elseif ($status ==  'cancelled'){
            //Put desired action/code after transaction has been cancelled here
        }
        else{
            //Put desired action/code after transaction has failed here
        }
//        TODO: Put success page after payment is successfully

    }
}
