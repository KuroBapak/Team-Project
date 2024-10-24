<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #433F3F;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        nav {
            top: 0;
            position: relative;
            width: 100%;
        }

        .navbar-toggler {
            border: none;
            outline: none;
        }

        .custom-toggler-icon {
            width: 30px;
            height: 3px;
            background-color: #ffffff;
            display: block;
            margin: 5px 0;
            transition: 0.3s ease-in-out;
        }

        .navbar-toggler:hover .custom-toggler-icon {
            background-color: #ff9800;
        }

        .navbar-collapse {
            transition: height 0.5s ease-in-out;
        }

        .container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            flex-grow: 1; /* Membuat container utama fleksibel agar mengisi ruang */
        }

        .table {
            background-color: #fff8f8; /* Warna abu-abu untuk tabel */
        }

        .table th, .table td {
            background-color: #555555; /* Warna abu-abu terang untuk isi tabel */
            color: #ffffff; /* Warna teks */
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-custom {
            margin-top: 10px;
        }

        .table img {
            width: 100px;
        }

        footer {
            width: 100%;
            align-self: flex-end; /* Memastikan footer berada di bagian bawah */
        }

        /* Styling for buttons alignment */
        .btn-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-container form, .btn-container a {
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container-fluid">
            <a class="navbar-brand mx-auto position-absolute start-50 translate-middle-x" href="{{ route('admin.dashboard') }}">
                <img src="{{ url('asset/logo.png') }}"
                alt="logoweb" style="height: 150px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart" style="font-size: 1.5em;"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products') }}">
                            <i class="fas fa-home" style="font-size: 1.5em;"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 text-white" style="background-color: #201F1F;">
        <h1 class="mb-5">Admin Dashboard</h1>
        <!-- Table to display orders -->
        <h2>Orders</h2>
        <div class="table-responsive"> <!-- Tambahkan div untuk membuat tabel responsif -->
            <table class="table">
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

        <!-- Buttons section with proper alignment -->
        <div class="btn-container mt-3">
            <a href="{{ route('admin.products') }}" class="btn btn-primary">Manage Products</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>

    <footer class="text-center text-white mt-5 bg-black">
        <div class="container bg-black">
            <p>&copy; 2024 Morijul. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
