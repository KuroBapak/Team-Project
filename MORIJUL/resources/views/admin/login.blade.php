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
            position: absolute;
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

        /* Styling for login form */
        .login-container {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .login-form input {
            margin-bottom: 15px;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
        }

        .login-form label {
            margin-bottom: 5px;
            display: block;
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

    <div class="container mt-5 text-white" style="background-color: #201F1F;">
        <div class="row login-container">
            <div class="col-6">
                <img src="{{ url('asset/pu.jpeg')}}" class="img-fluid rounded" style="height: 460px; width: 100%;" alt="...">
            </div>
            <div class="col-6 d-flex align-items-center">
                <div class="container-fluid login-form">
                    <h2>Login Admin</h2>
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Login</button>
                    </form>
                </div>
            </div>
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
