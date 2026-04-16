@extends('buyer.layouts.parent')

@section('title', 'Payment | Buyer')

@section('content')

<main class="page-content">
    <div class="container">
        <div class="page-header">
            <h1 class="page-header__title">Payment for Order #{{ $order->id }}</h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card__wrapper">
                        <div class="card__header">
                            <h3 class="card__header-title">Enter Payment Details</h3>
                        </div>
                        <div class="card__body">
                            <form action="/pay/{{ $order->id }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="card_name">Card Name</label>
                                    <input type="text" id="card_name" name="card_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="card_number">Card Number</label>
                                    <input type="text" id="card_number" name="card_number" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="text" id="amount" name="amount" class="form-control" value="{{ $order->amount }}" readonly>
                                </div>
                                <button type="submit" class="btn btn-primary">Pay Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection