<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImagoLab - Free Image Background Remover</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/indexstyle.css') }}">
</head>
<body>
    <div class="header">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-sparkles"></i>
            </div>
            <div class="logo-text">ImagoLab</div>
        </div>

        @auth
            <div class="nav-links">
                <a href="{{ route('selection') }}" class="nav-link">Selection</a>
                <a href="{{ route('history.index') }}" class="nav-link">History</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="nav-link">
                        Logout
                    </a>
                </form>
            </div>
        @else
            <div class="nav-links">
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="nav-link" style="text-decoration: none;">Register</a>
            </div>
        @endauth
    </div>

    <div class="hero-section">
        <h1 class="hero-title">Free Image Background Remover</h1>
        <p class="hero-subtitle">Easily remove the background from images with our AI-powered tool. Continue editing your image to quickly change the background, add graphics, and more.</p>
    </div>

    <a href="{{ route('login') }}" class="upload-container-link">
        <div class="upload-container">
            <div class="upload-header">
                <div class="upload-icon"><i class="fas fa-image"></i></div>
                <h2 class="upload-title">Remove the Background</h2>
            </div>
            <div class="upload-area">
                <i class="fas fa-cloud-upload-alt"></i>
                <div class="upload-text">Drag and drop an image</div>
                <div class="upload-subtext">or browse to upload</div>
            </div>
            <div class="upload-subtext" style="margin-bottom: 20px; text-align: center;">
                File must be JPEG, JPG, PNG or WebP and up to 10MB
            </div>
            <div class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Your Photo
            </div>
        </div>
    </a>

    <div class="steps-section">
        <h2 class="steps-title">How to Remove the Background of a Picture</h2>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3 class="step-title">Select</h3>
                <p class="step-description">For best results, choose an image where the subject has clear edges with nothing overlapping.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3 class="step-title">Remove</h3>
                <p class="step-description">Upload your image to automatically remove the background in an instant.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3 class="step-title">Continue Editing</h3>
                <p class="step-description">Download your new image as a PNG file with a transparent background to save, share, or keep editing.</p>
            </div>
        </div>
    </div>

    <div class="features-section">
        <h2 class="features-title">Create Transparent Cutout Backgrounds for Your Photos</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-cut"></i></div>
                <h3 class="feature-title">Background Removal</h3>
                <p class="feature-description">Highlight the subject of your photo and create a clear background, so you can place your new image into a variety of new designs and destinations.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-expand-alt"></i></div>
                <h3 class="feature-title">Super Resolution</h3>
                <p class="feature-description">Enhance image quality and resolution through AI upscaling to make your photos look stunning at any size.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-palette"></i></div>
                <h3 class="feature-title">Color Adjustments</h3>
                <p class="feature-description">Adjust brightness, contrast, saturation, and hue to perfect your images with professional-grade tools.</p>
            </div>
        </div>
    </div>

    <div class="testimonials-section">
        </div>

    <div class="cta-section">
        <h2 class="cta-title">Remove the Background from Your Picture and Download Instantly</h2>
        <p class="cta-subtitle">Take the background out of a picture faster than ever. It's as easy as selecting your image, uploading it to our free photo background remover, and your image will be ready to download and share in an instant.</p>
        <div class="cta-buttons">
            <a href="{{ route('selection') }}" class="btn btn-primary"><i class="fas fa-upload"></i> Start Removing Backgrounds</a>
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline"><i class="fas fa-sign-in-alt"></i> Already Have an Account?</a>
            @endguest
        </div>
    </div>

    <div class="footer">
        <div class="footer-links">
            <a href="#" class="footer-link">Terms of Use</a>
            <a href="#" class="footer-link">Privacy Policy</a>
            <a href="#" class="footer-link">Contact Us</a>
            <a href="#" class="footer-link">About</a>
        </div>
        <p>© 2025 ImagoLab. All rights reserved.</p>
    </div>

    <style>
        /* This style makes the entire upload box a clickable link */
        .upload-container-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }
    </style>
</body>
</html>
