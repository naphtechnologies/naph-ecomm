@extends('backend.layouts.master')

@section('main-content')

    <div class="card">
        <h5 class="card-header">Mpesa Credentials</h5>

    </div>
        <div class="card-body">
            <form method="post" action="{{route('mpesa-auth-data')}}">
                @csrf
                <div class="form-group">
                    <label for="shortcode">Shortcode</label>
                    <input type="number" class="form-control" name="shortcode" id="shortcode" value="{{$shortcode}}" required>
                </div>
                <div class="form-group">
                    <label for="transactiontype">Consumer Key</label>
                    <input type="password" name="consumerkey" class="form-control" id="transactiontype" value="{{$consumerkey}}" required>
                </div>
                <div class="form-group">
                    <label for="shortcode">Consumer Secret</label>
                    <input type="password" name="consumersecret" class="form-control" id="shortcode" value="{{$consumersecret}}" required>
                </div>
                <div class="form-group">
                    <label for="passkey">Pass Key</label>
                    <input type="password" name="passkey" class="form-control" id="passkey" value="{{$passkey}}" required>
                </div>
                <div class="form-group">
                    <label for="callback">Callback Url</label>
                    <input type="url" class="form-control" name="callback" id="callback" value="{{$callback}}" required>
                </div>
                <div class="form-group">
                    <label for="transactiontype">Transaction Type</label>
                    <input type="text" name="transactiontype" class="form-control" id="transactiontype" value="{{$transactiontype}}" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
{{--            <form action="{{route('mpesa-simulate')}}" method="post">--}}
{{--                @csrf--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-4">--}}
{{--                        <input type="tel" name="phone" class="form-control" id="phone" placeholder="254">--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <input type="number" name="amount" class="form-control" id="amount" placeholder="Amount">--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <button class="btn btn-success">Submit</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
        </div>
    </div>

@endsection
