<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #1E1E1E;
            color: #ffffff;
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
            background-color: #333333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        footer {
            width: 100%;
            position: relative;
        }
        .form-container {
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

    <!-- Main -->
    <div class="container mt-5">
        <div class="form-container">
            <h1>Edit Product</h1>
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" value="{{ $product->price }}" required>
                </div>
                <div class="form-group">
                    <label for="size">Size:</label>
                    <select id="size" name="size" class="form-control" required>
                        <option value="kecil" {{ $product->size == 'kecil' ? 'selected' : '' }}>Kecil</option>
                        <option value="sedang" {{ $product->size == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="besar" {{ $product->size == 'besar' ? 'selected' : '' }}>Besar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="number" id="stock" name="stock" class="form-control" value="{{ $product->stock }}" required>
                </div>
                <div class="form-group">
                    <label for="image">Product Image:</label>
                    <input type="file" id="image" name="image" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>
        </div>
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
