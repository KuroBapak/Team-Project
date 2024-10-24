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

        /* Style for the alternative content on small screens */
        .small-screen-banner {
            display: none;
            text-align: center;
            background-color: #201F1F;
            color: white;
            padding: 20px;
            border-radius: 10px;
        }

        /* Media queries for responsive design */
        @media (max-width: 768px) {
            .carousel-inner img {
                height: 300px;
                object-fit: cover;
            }
        }

        @media (max-width: 576px) {
            /* Hide carousel on very small screens */
            .carousel {
                display: none;
            }

            /* Show alternative content on small screens */
            .small-screen-banner {
                display: block;
            }
        }

        /* CSS for Product Cards */
        .product-card {
            padding: 10px; /* Add padding around each product card */
        }

        /* Media query for smaller screens */
        @media (max-width: 576px) {
            .product-card {
                margin-bottom: 15px; /* More space below each card on small screens */
            }
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
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="width: 85%; max-height: 500px;">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{url('asset/banner.jpeg')}}" class="d-block w-100" alt="..." style="height: 500px; object-fit: cover;">
                </div>
                <div class="carousel-item">
                    <img src="{{url('asset/banner.jpeg')}}" class="d-block w-100" alt="..." style="height: 500px; object-fit: cover;">
                </div>
                <div class="carousel-item">
                    <img src="{{url('asset/banner.jpeg')}}" class="d-block w-100" alt="..." style="height: 500px; object-fit: cover;">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Small screen alternative content -->
    <div class="small-screen-banner">
        <h3>Welcome to Our Store!</h3>
        <p>Explore our products below. Enjoy shopping!</p>
    </div>

    <!-- Main Content -->
    <div class="container mt-5 text-white" style="background-color: #201F1F;">
        <h1 class="text-center mb-3">Our Products</h1>

        <!-- Product Rows -->
        <div class="row" id="product-list">
            @foreach ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 product-card" style="padding: 10px;"> <!-- Add padding here -->
                <div class="card h-100 text-white" style="background-color: #3e3e3e">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover; padding:10px;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->price }} IDR</p>
                        <p class="card-text">Size: {{ $product->size }}</p>
                        <p class="card-text">Stock: {{ $product->stock }}</p>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination Numbers -->
        <div class="d-flex justify-content-center mt-4">
            <button class="btn btn-secondary" id="backToFirst" onclick="goToPage(1)"><< First Page</button>
            <div id="pagination-numbers" class="mx-3"></div>
        </div>
    </div>

    <!-- footer -->
    <footer class="text-center text-white mt-5 bg-black">
        <div class="container bg-black">
            <p>&copy; 2024 Morijul. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript for Pagination -->
    <script>
        const productsPerPage = 8;
        let currentPage = 1;
        const productCards = document.querySelectorAll('.product-card');
        const totalPages = Math.ceil(productCards.length / productsPerPage);
        const paginationNumbers = document.getElementById('pagination-numbers');

        // Function to generate page numbers
        function createPageNumbers() {
            paginationNumbers.innerHTML = ''; // Clear previous page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('button');
                pageButton.classList.add('btn', 'btn-light', 'mx-1');
                pageButton.textContent = i;
                pageButton.setAttribute('onclick', `goToPage(${i})`);
                pageButton.setAttribute('id', `page-${i}`);
                paginationNumbers.appendChild(pageButton);
            }
        }

        // Function to show the products for the current page
        function showPage(page) {
            productCards.forEach((card, index) => {
                card.style.display = (index >= (page - 1) * productsPerPage && index < page * productsPerPage) ? 'block' : 'none';
            });

            // Highlight the active page number
            document.querySelectorAll('#pagination-numbers button').forEach(btn => {
                btn.classList.remove('btn-warning');
                btn.classList.add('btn-light');
            });
            document.getElementById(`page-${page}`).classList.remove('btn-light');
            document.getElementById(`page-${page}`).classList.add('btn-warning');

            // Update current page
            currentPage = page;
        }

        // Function to go to a specific page
        function goToPage(page) {
            showPage(page);
        }

        // Show the first page initially and generate page numbers
        createPageNumbers();
        showPage(currentPage);
    </script>
</body>
</html>
