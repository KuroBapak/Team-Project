<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <!-- Tombol untuk mengelola produk -->
    <a href="{{ route('admin.products') }}" style="display: inline-block; padding: 10px 20px; margin: 10px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Manage Products</a>

    <!-- Logout Button -->
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" style="padding: 10px 20px; background-color: red; color: white; border: none; border-radius: 5px;">Logout</button>
    </form>

    <hr>

    <h2>Orders Overview</h2>
    <table>
        <thead>
            <tr>
                <th>Buyer Name</th>
                <th>Room Number</th>
                <th>Total Purchase</th>
                <th>Payment Type</th>
                <th>Checkout Date/Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->buyer_name }}</td>
                <td>{{ $order->room_number }}</td>
                <td>Rp {{ number_format($order->total_amount, 2) }}</td>
                <td>{{ $order->payment_type }}</td>
                <td>{{ $order->created_at->format('d-m-Y H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
