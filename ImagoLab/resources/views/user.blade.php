@php
    $isAdvanced = session('tool_type') === 'advanced';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImagoLab - {{ $isAdvanced ? 'Advanced AI' : 'Basic Tools' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @if($isAdvanced)
        <link rel="stylesheet" href="{{ asset('css/advanced-ai.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/basic-toolsstyle.css') }}">
    @endif
</head>
<body>
    <canvas id="stars-canvas"></canvas>

    <div class="header">
        <div class="logo">
            <div class="logo-icon"><i class="fas fa-sparkles"></i></div>
            <div class="logo-text">ImagoLab</div>
        </div>
        <div class="nav-links">
            <a href="{{ route('selection') }}" class="nav-link">Editor Selection</a>
            <form method="POST" action="{{ route('tool.select') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="tool_type" value="{{ $isAdvanced ? 'basic' : 'advanced' }}">
                <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer; font-size: inherit;">
                    {{ $isAdvanced ? 'Basic Tools' : 'Advanced AI' }}
                </button>
            </form>
            <div class="profile-dropdown">
                <button class="profile-toggle" id="profileToggle">
                    Profile <i class="fas fa-chevron-down" style="font-size:12px;"></i>
                </button>
                <div class="dropdown-menu" id="profileMenu">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fas fa-user"></i> Profile</a>
                    <a href="{{ route('history.index') }}" class="dropdown-item"><i class="fas fa-history"></i> History</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">@csrf<a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();this.closest('form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @if($isAdvanced)
            {{-- ADVANCED AI EDITOR --}}
            <div class="card">
                <div class="card-header"><i class="fas fa-robot"></i><h2>Advanced AI Features</h2></div>
                <div class="feature-selector">
                    <div class="feature-option active" data-feature="removebg"><i class="fas fa-cut"></i><h3>Background Removal</h3><p>AI segmentation for clean cutouts</p></div>
                    <div class="feature-option" data-feature="superres"><i class="fas fa-expand-alt"></i><h3>Super Resolution</h3><p>AI upscaling for high quality</p></div>
                    <div class="feature-option" data-feature="playground" style="opacity: 0.5; cursor: not-allowed;"><i class="fas fa-flask"></i><h3>Playground</h3><p>(Coming Soon)</p></div>
                    <div class="feature-option" data-feature="showcase" style="opacity: 0.5; cursor: not-allowed;"><i class="fas fa-th-large"></i><h3>Showcase</h3><p>(Coming Soon)</p></div>
                </div>
                <div class="presets-section"><div class="presets-header"><h3>AI Presets</h3></div><div class="presets"><div class="preset active">Professional</div><div class="preset">Creative</div><div class="preset">Minimalist</div><div class="preset">Vibrant</div></div></div>
                <div class="mode-selector"><div class="mode-btn active">Server Processing</div><div class="mode-btn" style="opacity: 0.5; cursor: not-allowed;">Local Processing</div></div>
                <div class="info-box"><p><i class="fas fa-server"></i> All requests are processed on our secure backend.</p></div>
            </div>
            <div class="card">
                <form action="{{ route('imago.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="mode" id="mode-input" value="removebg">
                    <div class="card-header"><i class="fas fa-sliders-h"></i><h2>Configure & Process</h2></div>
                    <div class="file-upload">
                        <input type="file" name="image" id="image-upload" class="file-input" accept="image/*" required>
                        <label for="image-upload" class="file-label"><i class="fas fa-upload"></i> Upload Image</label>
                        <div class="file-info">Supports JPG, PNG, WEBP (Max 10MB)</div>
                    </div>
                    @if ($errors->any() || session('error'))<div class="info-box" style="background-color:#f8d7da;color:#721c24;border:1px solid #f5c6cb;margin:20px 0;">@if(session('error'))<p>{{session('error')}}</p>@endif @foreach($errors->all() as $error)<p>{{$error}}</p>@endforeach</div>@endif
                    <div class="comparison-view">
                        <div class="comparison-panel"><div class="comparison-title">ORIGINAL</div><div class="comparison-image" id="original-preview">@if(isset($originalUrl))<img src="{{$originalUrl}}" style="width: 100%; height: 100%; object-fit: contain;">@else<i class="fas fa-image"></i>@endif</div></div>
                        <div class="comparison-panel"><div class="comparison-title">ENHANCED</div><div class="comparison-image" id="enhanced-preview">@if(isset($processedUrl))<img src="{{$processedUrl}}" style="width: 100%; height: 100%; object-fit: contain;">@else<i class="fas fa-magic"></i>@endif</div></div>
                    </div>
                    <div class="parameter-section feature-params active" id="removebg-params"><div class="parameter-group"><label>Segmentation Threshold</label><div class="slider-container"><input type="range" class="slider" disabled><span class="slider-value">0.5</span></div></div></div>
                    <div class="parameter-section feature-params" id="superres-params"><div class="parameter-group"><label>Upscale Factor</label><div class="slider-container"><input type="range" class="slider" disabled><span class="slider-value">4x</span></div></div></div>
                    <div class="status-indicator"><i class="fas fa-check-circle"></i><span class="status-text">Ready for enhancement</span></div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-bolt"></i> Enhance Image</button>
                        @if(isset($processedUrl))<a href="{{$processedUrl}}" download="processed_image.png" class="btn btn-outline" style="text-decoration:none;"><i class="fas fa-download"></i> Download</a>@endif
                    </div>
                </form>
            </div>
        @else
            {{-- BASIC TOOLS EDITOR --}}
            <div class="card">
                <div class="card-header"><i class="fas fa-tools"></i><h2>Basic Image Editing Tools</h2></div>
                <div class="tool-selector">
                    <div class="tool-category active" data-tool="enhance"><i class="fas fa-sun"></i><h3>Enhance</h3><p>Grayscale</p></div>
                    <div class="tool-category" data-tool="transform" style="opacity:0.5;cursor:not-allowed;"><i class="fas fa-expand-arrows-alt"></i><h3>Transform</h3><p>(Coming Soon)</p></div>
                    <div class="tool-category" data-tool="color" style="opacity:0.5;cursor:not-allowed;"><i class="fas fa-palette"></i><h3>Color</h3><p>(Coming Soon)</p></div>
                </div>
                <div class="tool-submenu active" id="enhance-menu"><div class="tool-submenu-item active" data-action="grayscale"><i class="fas fa-moon"></i> Grayscale</div></div>
                <div class="presets-section"><div class="presets-header"><h3>Quick Presets</h3></div><div class="presets"><div class="preset active">Default</div></div></div>
                <div class="mode-selector"><div class="mode-btn active">Server Processing</div></div>
                <div class="info-box"><p><i class="fas fa-server"></i> All operations are processed on our secure backend.</p></div>
            </div>
            <div class="card">
                <form action="{{ route('imago.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="mode" id="mode-input" value="grayscale">
                    <div class="card-header"><i class="fas fa-sliders-h"></i><h2>Configure & Process</h2></div>
                    <div class="file-upload">
                        <input type="file" name="image" id="image-upload" class="file-input" accept="image/*" required>
                        <label for="image-upload" class="file-label"><i class="fas fa-upload"></i> Upload Image</label>
                        <div class="file-info">Supports JPG, PNG, WEBP (Max 10MB)</div>
                    </div>
                    @if($errors->any()||session('error'))<div class="info-box" style="background-color:#f8d7da;color:#721c24;border:1px solid #f5c6cb;margin:20px 0;">@if(session('error'))<p>{{session('error')}}</p>@endif @foreach($errors->all() as $error)<p>{{$error}}</p>@endforeach</div>@endif
                    <div class="comparison-view">
                        <div class="comparison-panel"><div class="comparison-title">ORIGINAL</div><div class="comparison-image" id="original-preview">@if(isset($originalUrl))<img src="{{$originalUrl}}" style="width: 100%; height: 100%; object-fit: contain;">@else<i class="fas fa-image"></i>@endif</div></div>
                        <div class="comparison-panel"><div class="comparison-title">PROCESSED</div><div class="comparison-image" id="processed-preview">@if(isset($processedUrl))<img src="{{$processedUrl}}" style="width: 100%; height: 100%; object-fit: contain;">@else<i class="fas fa-magic"></i>@endif</div></div>
                    </div>
                    <div class="parameter-section feature-params active" id="enhance-params"><div class="parameter-group"><label>Grayscale Conversion</label><p style="color:var(--gray);font-size:14px;">This tool converts your image to black and white. No parameters needed.</p></div></div>
                    <div class="status-indicator"><i class="fas fa-check-circle"></i><span class="status-text">Ready for processing</span></div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-bolt"></i> Apply Changes</button>
                        @if(isset($processedUrl))<a href="{{$processedUrl}}" download="processed_image.png" class="btn btn-outline" style="text-decoration:none;"><i class="fas fa-download"></i> Download</a>@endif
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script>
        'use strict';
        // Star background animation
        const canvas = document.getElementById('stars-canvas');
        const ctx = canvas.getContext('2d');
        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
        const stars = [];
        const numStars = 110;
        class Star {
            constructor() { this.x = Math.random() * canvas.width; this.y = Math.random() * canvas.height; this.size = Math.random() * 1.7; this.speedX = (Math.random() - 0.5) * 0.14; this.speedY = (Math.random() - 0.5) * 0.14; this.baseOpacity = Math.random() * 0.55 + 0.2; }
            update() { this.x += this.speedX; this.y += this.speedY; if (this.x < 0 || this.x > canvas.width) this.speedX = -this.speedX; if (this.y < 0 || this.y > canvas.height) this.speedY = -this.speedY; }
            draw() { const opacity = this.baseOpacity + Math.sin(Date.now() * 0.001 + this.x * 0.01) * 0.18; ctx.fillStyle = `rgba(255, 255, 255, ${Math.max(0.1, opacity)})`; ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill(); }
        }
        for (let i = 0; i < numStars; i++) { stars.push(new Star()); }
        function animateStars() { ctx.clearRect(0, 0, canvas.width, canvas.height); stars.forEach(star => { star.update(); star.draw(); }); requestAnimationFrame(animateStars); }
        animateStars();

        // Profile Dropdown Logic
        const profileToggle = document.getElementById('profileToggle');
        const profileMenu = document.getElementById('profileMenu');
        if(profileToggle) {
            profileToggle.addEventListener('click', function(e) { e.stopPropagation(); profileMenu.classList.toggle('show'); });
        }
        document.addEventListener('click', function(e) { if (profileToggle && !profileToggle.contains(e.target) && !profileMenu.contains(e.target)) { profileMenu.classList.remove('show'); } });

        // Feature Selector Logic
        const featureOptions = document.querySelectorAll('.feature-option');
        if (featureOptions.length > 0) {
            const modeInput = document.getElementById('mode-input');
            const allParams = document.querySelectorAll('.feature-params');
            featureOptions.forEach(option => {
                option.addEventListener('click', () => {
                    if (option.style.cursor === 'not-allowed') return;
                    featureOptions.forEach(opt => opt.classList.remove('active'));
                    option.classList.add('active');
                    const feature = option.getAttribute('data-feature');
                    modeInput.value = feature;
                    allParams.forEach(p => p.classList.remove('active'));
                    const targetParams = document.getElementById(feature + '-params');
                    if (targetParams) targetParams.classList.add('active');
                });
            });
        }

        // Image Preview Logic
        const fileInput = document.getElementById('image-upload');
        const originalPreview = document.getElementById('original-preview');
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    originalPreview.innerHTML = `<img src="${e.target.result}" alt="Original" style="width: 100%; height: 100%; object-fit: contain;">`;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>
