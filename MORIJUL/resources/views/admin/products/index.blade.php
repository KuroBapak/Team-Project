<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Products</title>
</head>
<body>
    <h1>Manage Products</h1>

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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->size }}</td>
                <td><img src="{{ asset('storage/' . $product->image) }}" width="100"></td>
                <td>
                    <form action="{{ route('admin.products.delete', $product->id) }}" method="POST">
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
