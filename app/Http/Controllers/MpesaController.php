<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\MpesaCredentials;
use App\Models\Order;
use App\Models\Payments;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\MessageConverter;
use Flugg\Responder\Contracts\Responder;

class MpesaController extends Controller
{
    public $consumerKey = 'O1UxHCeJsosOdsCCiAMATPhz3GeKjALY';
    public $consumerSecret = 'MwFTGEAfUI07zeV0';
    public $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';


    public $PartyA; // This is your phone number,
    public $AccountReference, $TransactionDesc, $Amount, $Timestamp, $Password, $headers, $access_token_url, $initiate_url, $json, $user;
    public $source, $app;
    public $stkCallbackResponse;

    public $CallBackURL = 'https://b28b-197-237-1-63.ngrok-free.app/api/callback';
    public $response = array();
    public $access_token;
    public $TransactionType = 'CustomerPayBillOnline';
    private $BusinessShortCode;

    public function __construct()
    {
        $credentials = MpesaCredentials::find(1);
        if ($credentials != null) {
            $this->consumerKey = $credentials->consumerkey; //Fill with your app Consumer Key
            $this->consumerSecret = $credentials->consumersecret; // Fill with your app Secret
            $this->BusinessShortCode = $credentials->shortcode;
            $this->Passkey = $credentials->passkey;
            $this->CallBackURL = $credentials->callback;
            $this->TransactionType = $credentials->transactiontype;
        }
    }

    public function datapreview()
    {
        $data = [
            'consumerkey' => $this->consumerKey,
            'consumersecret' => $this->consumerSecret,
            'shortcode' => $this->BusinessShortCode,
            'passkey' => $this->Passkey,
            'callback' => $this->CallBackURL,
            'transactiontype' => $this->TransactionType,
        ];
//        dd($data);
        return view('mpesa.mpesa', $data);
    }


    //simulate
    public function simulate(Request $mrequest, $mpesa_phone)
    {

        //        Handling the conversion rate realtime

        $exchangeRateUrl = "https://v6.exchangerate-api.com/v6/4d93e89277f14832f3a3f71e/pair/USD/KES";
        $ch = curl_init($exchangeRateUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $exchangeRateResponse = curl_exec($ch);
        curl_close($ch);

        $exchangeRateData = json_decode($exchangeRateResponse, true);

        $cart = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->get()->toArray();
        $data = [];


        // return $cart;
        $data['items'] = array_map(function ($item) use ($cart) {
            $name = Product::where('id', $item['product_id'])->pluck('title');
            return [
                'name' => $name,
                'price' => $item['price'],
                'desc' => 'Thank you for using paypal',
                'qty' => $item['quantity']
            ];
        }, $cart);


        $total = 0;
        foreach ($data['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }
        // Assuming $total contains the amount in USD
        $totalInUSD = $total;

//        dd($exchangeRateData);

        if (isset($exchangeRateData['result']) && $exchangeRateData['result'] === 'success') {
            $conversionRate = intval($exchangeRateData['conversion_rate']);

//            dd($conversionRate);


            $totalInKsh = $totalInUSD * $conversionRate;

            $totalInKsh = intval($totalInKsh);

            $data['totalInKsh'] = $totalInKsh;

//            dd($totalInKsh);

            $this->Amount = $totalInKsh;
            $this->AccountReference = 'Demo reference';
            $this->TransactionDesc = "Franciscan Agencies";
            $this->PartyA = $mpesa_phone;
            $this->user = "demo";
            $this->AddData();
            $this->RequestPayment();
            $data['response'] = $this->json;


            Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => session()->get('id')]);

            return redirect()->back()->with('message', 'Transaction Initiated Successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to retrieve exchange rate and transact');
        }

    }


    public function addcredentials(Request $request)
    {
        $credentials = MpesaCredentials::find(1);
        if ($credentials != null) {
            $credentials->consumerkey = $request->consumerkey; //Fill with your app Consumer Key
            $credentials->consumersecret = $request->consumersecret; // Fill with your app Secret
            $credentials->shortcode = $request->shortcode;
            $credentials->passkey = $request->passkey;
            $credentials->callback = $request->callback;
            $credentials->transactiontype = $request->transactiontype;
            $credentials->update();
        } else {
            $credentials = new MpesaCredentials();
            $credentials->consumerkey = $request->consumerkey; //Fill with your app Consumer Key
            $credentials->consumersecret = $request->consumersecret; // Fill with your app Secret
            $credentials->shortcode = $request->shortcode;
            $credentials->passkey = $request->passkey;
            $credentials->callback = $request->callback;
            $credentials->transactiontype = $request->transactiontype;
            $credentials->save();
        }

        return redirect()->back()->with('message', 'Data Saved Successfully');
    }

    public function mpesasettings()
    {
        $data = [
            'consumerkey' => $this->consumerKey,
            'consumersecret' => $this->consumerSecret,
            'shortcode' => $this->BusinessShortCode,
            'passkey' => $this->Passkey,
            'callback' => $this->CallBackURL,
            'transactiontype' => $this->TransactionType,
        ];
        return view('backend.mpesa.mpesa', $data);
    }

    public function AccessToken()
    {
        date_default_timezone_set('Africa/Nairobi');
        $headers = ['Content-Type:application/json; charset=utf8'];

        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_USERPWD, $this->consumerKey . ':' . $this->consumerSecret);
        $results = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($results);
        $this->access_token = $result->access_token;
        curl_close($curl);
    }

    public function AddData()
    {

        $this->Timestamp = date('YmdHis');
        $this->Password = base64_encode($this->BusinessShortCode . $this->Passkey . $this->Timestamp);

        $this->headers = ['Content-Type:application/json; charset=utf8'];
        $this->initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    }

    public function RequestPayment()
    {
        $this->AccessToken();

        # header for stk push
        $stkheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $this->access_token];

        # initiating the transaction
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->initiate_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header

        $curl_post_data = array(
            //Fill in the request parameters with valid values
            'BusinessShortCode' => $this->BusinessShortCode,
            'Password' => $this->Password,
            'Timestamp' => $this->Timestamp,
            'TransactionType' => $this->TransactionType,
            'Amount' => $this->Amount,
            'PartyA' => $this->PartyA,
            'PartyB' => $this->BusinessShortCode,
            'PhoneNumber' => $this->PartyA,
            'CallBackURL' => $this->CallBackURL,
            'AccountReference' => $this->AccountReference,
            'TransactionDesc' => $this->TransactionDesc
        );

        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);

        $this->json = $curl_response;
//        dd($this->json);
        curl_close($curl);
        $this->FeedData();
    }

    private function FeedData()
    {
        $data = json_decode($this->json, true);
        $i = 0;
        $MerchantRequestID = "";
        $CheckoutRequestID = "";
        $ResponseCode = "";
        $ResponseDescription = "";
        $CustomerMessage = "";
        foreach ($data as $value) {
            $bl = $i++;
            switch ($bl) {
                case 0;
                    $MerchantRequestID = $value;
                    break;
                case 1;
                    $CheckoutRequestID = $value;
                    break;
                case 2;
                    $ResponseCode = $value;
                    break;
                case 3;
                    $ResponseDescription = $value;
                    break;
                case 4;
                    $CustomerMessage = $value;
                    break;
                default:
                    break;
            }
        }
        $payment = new Payments();
        $payment->payment = $this->user;
        $payment->MerchantRequestID = $MerchantRequestID;
        $payment->CheckoutRequestID = $CheckoutRequestID;
        $payment->ResponseCodeRe = $ResponseCode;
        $payment->ResponseDescription = $ResponseDescription;
        $payment->CustomerMessage = $CustomerMessage;
        $payment->amount = $this->Amount;
        $payment->phone = $this->PartyA;
        $payment->save();

        $response = array("Please Accept When Propted");
//        $response = request()->session()->flash('success', 'Please enter your pin to complete the transaction.');
        return responder()->success($response);
    }


    public function updateStatus() {
        $value = json_decode($this->stkCallbackResponse, true);
        $order_num = session()->get('id');
        $userId = auth()->user()->id;

        if ($value["Body"]["stkCallback"]["ResultCode"] == "0") {
            $MerchantRequestID = $value["Body"]["stkCallback"]["MerchantRequestID"];
            $CheckoutRequestID = $value["Body"]["stkCallback"]["CheckoutRequestID"];
            $ResponseCode = $value["Body"]["stkCallback"]["ResultCode"];
            $desc  = $value["Body"]["stkCallback"]["ResultDesc"];
            $confirm = $value["Body"]["stkCallback"]["CallbackMetadata"]["Item"][1]["Value"];

            $payment = Payments::where('MerchantRequestID', $MerchantRequestID)->where('CheckoutRequestID', $CheckoutRequestID)->first();


            if ($payment != null) {
                $payment->ResponseCodeCo = $ResponseCode;
                $payment->description = $desc;
                $payment->confirm = $confirm;
                $payment->update();

                Order::where('id', $order_num)
                    ->where('user_id', $userId)
                    ->update(['payment_status' => 'paid']);
            }
        }
    }


    public function confirm($id)
    {
        $payment = Payments::where('user', $id)->last();
        if ($payment == null) {
            return responder()->error();
        } else {
            return responder()->success($payment);
        }
    }
}
