<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Admin Dashboard</h1>

        <!-- Tombol untuk mengelola produk -->
        <a href="{{ route('admin.products') }}" class="btn btn-primary mb-3">Manage Products</a>

        <form action="{{ route('logout') }}" method="POST" style="display: inline-block;">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>

        <!-- Table to display orders -->
        <h2>Orders</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Buyer Name</th>
                    <th>Room Number</th>
                    <th>Total Purchase</th>
                    <th>Payment Type</th>
                    <th>Payment Status</th>
                    <th>Checkout Date & Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->buyer_name }}</td>
                    <td>{{ $order->room_number }}</td>
                    <td>{{ $order->total_amount }}</td>
                    <td>{{ $order->payment_type }}</td>
                    <td>{{ $order->payment_status }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i:s') }}</td>
                    <td>
                        <!-- Button to update payment status -->
                        <form action="{{ route('admin.updatePaymentStatus', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Mark as Done</button>
                        </form>

                        <!-- Button to delete order -->
                        <form action="{{ route('admin.deleteOrder', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
