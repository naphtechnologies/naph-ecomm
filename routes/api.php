<?php

use App\Http\Controllers\MpesaController;
use App\Http\Controllers\PaymentController;
use App\Models\RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/callback', function () {
    $data = file_get_contents('php://input');
    $mrequest = new RequestModel();
    $mrequest->request = $data;
    $mrequest->save();
    $mpesa = new MpesaController();
    $mpesa->stkCallbackResponse = $data;
    $mpesa->updateStatus();
    return $data;
});

Route::get('/payment/callback', [PaymentController::class, 'paystackCallback']);

