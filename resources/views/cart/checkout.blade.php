@extends('master')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 p-3">
                <h2>Checkout</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $id => $details)
                            <tr>
                                <td>{{ $details['name'] }}</td>
                                <td>{{ $details['quantity'] }}</td>
                                <td>Rs {{ $details['price'] }}</td>
                                <td>Rs {{ $details['quantity'] * $details['price'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <h3>Subtotal: Rs {{ $subtotal }}</h3>
                <h3>GST (18%): Rs {{ $gst }}</h3>
                <h3>Total with GST: Rs {{ $totalWithGst }}</h3>

                <form action="{{ route('cart.checkout.submit') }}" method="POST">
                    @csrf

                    <!-- Payment Method Selection -->
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label><br>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                            <label class="form-check-label" for="cod">
                                Cash on Delivery (COD)
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Place Order</button>
                </form>
            </div>
        </div>
    </div>
@endsection
