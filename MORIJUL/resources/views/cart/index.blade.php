<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #433F3F;;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #f8f9fa;
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

        h1 {
            color: #ffffff;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            color: #f8f9fa;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #3a3b3c;
        }

        td {
            background-color: #48494a;
        }

        button {
            border: none;
            color: rgb(255, 255, 255);
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }



        .continue a {
            color: #ffffff;
            text-decoration: none;
        }

        a:hover {
            color: #d69696;
        }

        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-black ">
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

<!-- Your Cart View -->
<div class="container mt-5 text-white" style="background-color: #201F1F;">
    <h1>Your Cart</h1>
    <table>
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
                    <td>
                        {{ $item['quantity'] }}
                    </td>
                    <td>{{ $item['price'] }} IDR</td>
                    <td>
                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                        <form action="{{ route('cart.update', ['id' => $item['id'], 'action' => 'increase']) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">+</button>
                        </form>
                        <form action="{{ route('cart.update', ['id' => $item['id'], 'action' => 'decrease']) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">-</button>
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

    <form action="{{ route('checkout') }}" method="GET">
        <button type="submit" class="btn btn-primary mt-3">Proceed to Checkout</button>
    </form>
    <div class="continue mt-2">
        <button class="btn btn-primary">
        <a href="{{ route('products') }}">Continue Shopping</a>
        </button>
    </div>
</div>




    <footer class="text-center text-white mt-5 bg-black">
        <div class="container bg-black">
            <p>&copy; 2024 Morijul. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
