<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* CSS */
        body {
            background-color: #433F3F;
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
        }

        footer {
            position: relative;
            bottom: 0;
            width: 100%;
        }

        /* Custom CSS for Banner */
        .banner img {
            width: 80%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container-fluid">
            <a class="navbar-brand mx-auto position-absolute start-50 translate-middle-x" href="#">
                <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhQBSDfQSDbKDjU52wMhLUVyR839TVlgR61XxDh3LIdAdYrjtYFsybWu167sDtGDj8TDyUzWLp23vBV4lmM-bP9wXVs2JiOi9E_efIwsuNJSa4Slrmf3cWt-yBfUDJkFd0XDuQjIpZe561Cz_Wofm6M0XpdXbcwhuRgaq6CwerhIAWdnSG6QIe5ZWonSo8M/s320/image_2024-09-09_130643787-removebg-preview%20(2).png"
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

    <!-- Banner Content -->
    <div class="container-fluid banner mt-5 mb-3" style="display: flex; justify-content: center;">
        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjag8ZsXLiRizVA8JGpauo0f5d1vaVryWWeBx_5UZyQBWbufyXDI6fYQNO3eAMUUfYccNJr7RfFV8ubgAVXhm2ThNPoHjCoS8NrfT9ecvP8J9jK9LzkEvKX3r8Q82vFQmDSy3eYnhYa4YJ5mHA82fFMjz6UZNK0eub-n517ZJ4zZC-6t17C-ONLC1qNQ_1C/s320/WhatsApp%20Image%202024-10-24%20at%2013.54.04.jpeg"
         alt="banner">
    </div>

    <!-- Main Content-->
    <div class="container mt-5 text-white" style="background-color: #201F1F;">
        <h1>Products</h1>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Size</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="100"></td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->size }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit">Add to Cart</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('cart.index') }}">View Cart</a>
    </div>

    <!-- footer -->
    <footer class="text-center text-white mt-5 bg-black">
        <div class="container bg-black">
            <p>&copy; 2024 Morijul. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
