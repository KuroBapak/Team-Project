<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Products</title>
</head>
<body>
    <h1>Manage Products</h1>
    <a href="{{ route('admin.dashboard') }}" style="display: inline-block; padding: 10px 20px; margin: 10px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Manage Dashboard</a>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <h2>Add New Product</h2>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required><br><br>

        <label for="size">Size:</label>
        <select id="size" name="size" required>
            <option value="kecil">Kecil</option>
            <option value="sedang">Sedang</option>
            <option value="besar">Besar</option>
        </select><br><br>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" required><br><br>

        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image" required><br><br>

        <button type="submit">Add Product</button>
    </form>

    <h2>Product List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Size</th>
                <th>Image</th>
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
                    <!-- Button untuk edit produk -->
                    <a href="{{ route('admin.products.edit', $product->id) }}">Edit</a>
                    <!-- Form untuk delete produk -->
                    <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
