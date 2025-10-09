<!DOCTYPE html>
<html>
<head>
    <title>AI Image Processor</title>
    <style>
        /* Basic styling for better UX */
        body { font-family: sans-serif; max-width: 600px; margin: 40px auto; }
        .image-container { margin-top: 20px; border: 2px dashed #ccc; padding: 10px; min-height: 300px; display: flex; align-items: center; justify-content: center; }
        .status { margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Upload an Image for AI Processing</h2>

    {{-- The id="image-form" is used by our JavaScript --}}
    <form id="image-form" action="{{ route('imago.process') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" required>
        <hr>
        <strong>Processing Mode:</strong><br>
        <input type="radio" id="removebg" name="mode" value="removebg" checked>
        <label for="removebg">Remove Background</label><br>
        <input type="radio" id="grayscale" name="mode" value="grayscale">
        <label for="grayscale">Convert to Grayscale</label><br>
        <hr>
        <button type="submit">Process Image</button>
    </form>

    <div id="status" class="status"></div>

    <div class="image-container">
        {{-- The id="image-preview" lets our JavaScript update the image --}}
        <img id="image-preview" src="" alt="Image preview will appear here" width="400" style="display: none;">
        <span id="preview-text">Image preview will appear here</span>
    </div>

<script>
    // Get references to our HTML elements
    const form = document.getElementById('image-form');
    const imagePreview = document.getElementById('image-preview');
    const previewText = document.getElementById('preview-text');
    const statusDiv = document.getElementById('status');

    // Listen for the form's submit event
    form.addEventListener('submit', async (event) => {
        // 1. Prevent the default browser behavior (full page reload)
        event.preventDefault();

        // 2. Show a loading message to the user
        statusDiv.textContent = 'Processing... Please wait.';
        imagePreview.style.display = 'none'; // Hide previous image
        previewText.style.display = 'block';

        // 3. Create a FormData object from the form
        const formData = new FormData(form);

        try {
            // 4. Send the data to the Laravel backend using fetch
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    // We need this header to get JSON errors back properly
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            // 5. Handle the response from the server
            if (!response.ok) {
                // If the server returned an error (e.g., 4xx, 5xx)
                statusDiv.textContent = `Error: ${data.message || 'An unknown error occurred.'}`;
            } else {
                // On success, update the image source
                imagePreview.src = data.processed_url;
                imagePreview.style.display = 'block'; // Show the image element
                previewText.style.display = 'none';   // Hide the placeholder text
                statusDiv.textContent = 'Processing complete!';
            }

        } catch (error) {
            // Handle network errors
            statusDiv.textContent = 'A network error occurred. Please try again.';
            console.error('Fetch error:', error);
        }
    });
</script>

</body>
</html>
