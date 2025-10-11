<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ImagoLab - AI Image Processor</title>

    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; margin: 0; color: #333; background-color: #f4f6f9; }
        .container { max-width: 800px; margin: 20px auto; padding: 0 20px; }
        nav { padding: 15px 20px; background: #ffffff; text-align: right; border-bottom: 1px solid #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.04); }
        nav a { margin-left: 15px; text-decoration: none; color: #007bff; font-weight: 600; }
        .form-section { background: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-section input[type="file"] { margin-bottom: 15px; }
        .form-section button { padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.2s; }
        .form-section button:hover { background-color: #0056b3; }
        .image-container { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 30px; text-align: center; }
        .image-box { border: 1px solid #dee2e6; padding: 15px; border-radius: 8px; flex: 1; min-width: 300px; background: #ffffff; }
        .error { color: #dc3545; font-weight: bold; background-color: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>

    <nav>
        @auth
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('history.index') }}">My History</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                    Logout
                </a>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </nav>

    <div class="container">
        <h1 style="text-align: center; margin-bottom: 20px;">Upload Image</h1>

        <div class="form-section">
            @if(session('error'))
                <p class="error">{{ session('error') }}</p>
            @endif

            <form action="{{ route('imago.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="image" required>
                <hr style="margin: 15px 0;">

                <strong>Processing Mode:</strong><br>
                <input type="radio" id="removebg" name="mode" value="removebg" checked>
                <label for="removebg">Remove Background</label><br>
                <input type="radio" id="grayscale" name="mode" value="grayscale">
                <label for="grayscale">Convert to Grayscale</label><br>
                <hr style="margin: 15px 0;">

                <button type="submit">Process Image</button>
            </form>
        </div>

        @if(isset($originalUrl) && isset($processedUrl))
            <div class="image-container">
                <div class="image-box">
                    <h3>Original</h3>
                    <img src="{{ $originalUrl }}" alt="Original Image" style="max-width: 100%; border-radius: 5px;">
                </div>
                <div class="image-box">
                    <h3>Processed</h3>
                    <img src="{{ $processedUrl }}" alt="Processed Image" style="max-width: 100%; border-radius: 5px;">
                        <a href="{{ $processedUrl }}" download="processed_image.png" class="inline-block mt-4 px-4 py-2 bg-green-600 text-white font-semibold text-xs uppercase rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Download Result
                        </a>
                </div>
            </div>
        @else
            <p style="text-align: center; margin-top: 30px; color: #6c757d;">Your processed image will appear here.</p>
        @endif
    </div>

</body>
</html>
