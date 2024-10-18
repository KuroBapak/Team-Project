<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    <form action="{{ route('order.store') }}" method="POST">
        @csrf
        <label for="buyer_name">Name:</label>
        <input type="text" id="buyer_name" name="buyer_name" required><br><br>

        <label for="room_number">Room Number:</label>
        <input type="text" id="room_number" name="room_number" required><br><br>

        <label for="payment_type">Payment Method:</label>
        <select id="payment_type" name="payment_type" required>
            <option value="COD">Cash on Delivery</option>
            <option value="QRIS">QRIS</option>
        </select><br><br>

        <button type="submit">Place Order</button>

    </form>
</body>
</html>
