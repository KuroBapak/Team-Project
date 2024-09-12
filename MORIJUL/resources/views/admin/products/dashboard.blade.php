<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <!-- Tombol untuk mengelola produk -->
    <a href="{{ route('admin.products') }}" style="display: inline-block; padding: 10px 20px; margin: 10px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Manage Products</a>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
