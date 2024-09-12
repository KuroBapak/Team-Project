<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
</head>
<body>
    <h1>Manage Orders</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Buyer Name</th>
                <th>Room Number</th>
                <th>Payment Type</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->buyer_name }}</td>
                <td>{{ $order->room_number }}</td>
                <td>{{ $order->payment_type }}</td>
                <td>{{ $order->payment_status }}</td>
                <td>
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="payment_status">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
