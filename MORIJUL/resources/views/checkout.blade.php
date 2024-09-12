<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    <form action="{{ route('order.store') }}" method="POST">
        @csrf
        <label for="buyer_name">Buyer Name:</label>
        <input type="text" name="buyer_name" required>

        <label for="room_number">Room Number:</label>
        <input type="text" name="room_number" required>

        <label for="payment_type">Payment Type:</label>
        <select name="payment_type" required>
            <option value="COD">Cash on Delivery</option>
            <option value="QRIS">QRIS</option>
        </select>

        <h2>Order Summary</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Stock Remaining</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['price'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ $item['price'] * $item['quantity'] }}</td>
                    <td>{{ $item['stock'] }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Subtotal:</td>
                    <td>{{ $subtotal }}</td>
                </tr>
            </tfoot>
        </table>

        <button type="submit">Place Order</button>
    </form>
</body>
</html>
