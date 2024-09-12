<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="{{ $product->name }}" required><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" value="{{ $product->price }}" required><br><br>

        <label for="size">Size:</label>
        <select id="size" name="size" required>
            <option value="kecil" {{ $product->size == 'kecil' ? 'selected' : '' }}>Kecil</option>
            <option value="sedang" {{ $product->size == 'sedang' ? 'selected' : '' }}>Sedang</option>
            <option value="besar" {{ $product->size == 'besar' ? 'selected' : '' }}>Besar</option>
        </select><br><br>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" value="{{ $product->stock }}" required><br><br>

        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image"><br><br>

        <button type="submit">Update Product</button>
    </form>
</body>
</html>
