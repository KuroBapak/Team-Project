<!DOCTYPE html>
<html>
<head>
    <title>Checkout Page</title>
</head>
<body>
    <h2>Checkout</h2>

    <!-- Tampilkan daftar barang dari cart -->
    <h3>Items in Cart</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ $item['price'] }}</td>
                    <td>{{ $item['price'] * $item['quantity'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tampilkan subtotal -->
    <h4>Subtotal: {{ $subtotal }}</h4>

    <hr>

    <!-- Form input untuk nama pembeli dan nomor kamar -->
    <form action="{{ route('order.store') }}" method="POST">
        @csrf

        <div>
            <label for="buyer_name">Buyer Name:</label>
            <input type="text" name="buyer_name" required>
        </div>

        <div>
            <label for="room_number">Room Number:</label>
            <input type="text" name="room_number" required>
        </div>

        <div>
            <label for="payment_type">Payment Type:</label>
            <select name="payment_type" required>
                <option value="COD">Cash on Delivery</option>
                <option value="QRIS">QRIS</option>
            </select>
        </div>

        <button type="submit">Submit Order</button>
    </form>
</body>
</html>
