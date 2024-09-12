<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
</head>
<body>
    <h1>Your Cart</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($cartItems))
                @foreach ($cartItems as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ $item['price'] }}</td>
                    <td>
                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                            @csrf
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">Your cart is empty.</td>
                </tr>
            @endif
        </tbody>
    </table>
    <a href="{{ route('products') }}">Continue Shopping</a>
    <form action="{{ route('checkout') }}" method="GET">
        <button type="submit">Proceed to Checkout</button>
    </form>



</body>
</html>
