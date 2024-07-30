@extends('master')

@section('content')
    <div class="container mt-5">
        <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 2000)">
            @if(Session::has('success'))
                <div class="alert alert-success bg-green-300">
                    {{ Session::get('success') }}
                    @php
                        Session::forget('success');
                    @endphp
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-12 p-3">
                <h2>Shopping Cart</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('cart', []) as $id => $details)
                            <tr>
                                <td>{{ $details['name'] }}</td>
                                <td>
                                    <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="number" name="quantity" value="{{ $details['quantity'] }}" class="form-control d-inline" style="width: 70px;"min="1">
                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                </td>
                                <td>Rs {{ $details['price'] }}</td>
                                <td>Rs {{ $details['quantity'] * $details['price'] }}</td>
                                <td>
                                    <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <h3>Total Rs: {{ array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, session('cart', []))) }}</h3>
                
                <form action="{{ route('cart.checkout') }}" method="POST" class="d-inline">
                    @csrf
                    <a href="{{ route('cart.checkout') }}" class="btn btn-success">Proceed to Checkout</a>
                </form>
            </div>
        </div>
    </div>
@endsection
