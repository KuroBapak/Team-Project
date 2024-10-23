<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #424344;
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
            position: relative;
            bottom: 0;
            width: 100%;
        }

        @media (max-width: 768px) {
            .table img {
                width: 50px;
            }
            .form-control {
                font-size: 14px;
            }
            h1, h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-black ">
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
                        <a class="nav-link" href="#">
                            <i class="fas fa-shopping-cart" style="font-size: 1.5em;"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-info-circle" style="font-size: 1.5em;"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 bg-dark text-white">
        <h1 class="text-center">Manage Products</h1>

        <div class="d-flex justify-content-between align-items-center my-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Manage Dashboard</a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>

        <h2>Add New Product</h2>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" required>
            </div>

            <div class="form-group mb-3">
                <label for="size">Size:</label>
                <select id="size" name="size" class="form-select" required>
                    <option value="kecil">Kecil</option>
                    <option value="sedang">Sedang</option>
                    <option value="besar">Besar</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="image">Product Image:</label>
                <input type="file" id="image" name="image" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success btn-custom">Add Product</button>
        </form>

        <h2 class="mt-5">Product List</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
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
                        <td><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"></td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->size }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="margin: 10px 0px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
