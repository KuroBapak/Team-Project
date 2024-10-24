<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #433F3F;
            color: #ffffff;
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
            background-color: #333333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        footer {
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .checkout-form {
            max-width: 600px;
            margin: auto;
            background: #555555;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            margin-bottom: 20px;
        }
        label {
            margin-top: 10px;
        }
        button {
            margin-top: 20px;
        }
        a {
            margin-top: 10px;
            display: block;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table-dark {
            background-color: #333333;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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

    <!-- Main -->
    <div class="container mt-5 text-white" style="background-color: #201F1F;">
        <h1>Checkout</h1>
        <form action="{{ route('order.store') }}" method="POST" onsubmit="return confirm('Are you sure the name and room number are correct?');">
            @csrf
            <div class="form-group">
                <label for="buyer_name" class="mb-2">Buyer Name:</label>
                <input type="text" name="buyer_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="room_number" class="mb-2">Room Number:</label>
                <input type="text" name="room_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="payment_type" class="mb-2">Payment Type:</label>
                <select name="payment_type" class="form-control" required>
                    <option value="COD">Cash on Delivery</option>
                    <option value="QRIS">QRIS</option>
                </select>
            </div>
            <h2 class="mt-5">Order Summary</h2>
            <div class="table-responsive">
                <table class="table table-dark">
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
            </div>
            <button type="submit" class="btn btn-primary">Place Order</button>
            <a href="{{ route('cart.index') }}" class="btn btn-link">View Cart</a>
        </form>
    </div>

    <!-- Footer -->
    <footer class="text-center text-white mt-5 bg-black">
        <div class="container bg-black">
            <p>&copy; 2024 Morijul. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
