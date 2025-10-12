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
            {{-- ADVANCED AI EDITOR (Your Correct Code - Unchanged) --}}
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
            {{-- ================================================================= --}}
            {{-- BASIC TOOLS EDITOR (Your Design + All Features Implemented)     --}}
            {{-- ================================================================= --}}
            <div class="card">
                <div class="card-header"><i class="fas fa-tools"></i><h2>Basic Image Editing Tools</h2></div>
                <div class="tool-selector">
                    <div class="tool-category active" data-tool="transform"><i class="fas fa-expand-arrows-alt"></i><h3>Transform</h3><p>Resize, rotate, flip</p></div>
                    <div class="tool-category" data-tool="color"><i class="fas fa-palette"></i><h3>Color</h3><p>Brightness, contrast, saturation</p></div>
                    <div class="tool-category" data-tool="filter"><i class="fas fa-filter"></i><h3>Filters</h3><p>Grayscale, Blur, Sharpen</p></div>
                    <div class="tool-category" data-tool="morphology"><i class="fas fa-shapes"></i><h3>Morphology</h3><p>Erosion, dilation</p></div>
                    <div class="tool-category" data-tool="enhance"><i class="fas fa-sun"></i><h3>Enhance</h3><p>Gamma, threshold, histograms</p></div>
                </div>
                <div class="tool-submenu active" id="transform-menu">
                    <div class="tool-submenu-item active" data-action="rotate"><i class="fas fa-undo"></i> Rotate</div>
                    <div class="tool-submenu-item" data-action="resize"><i class="fas fa-expand"></i> Resize</div>
                    <div class="tool-submenu-item" data-action="flip"><i class="fas fa-sync-alt"></i> Flip</div>
                </div>
                <div class="tool-submenu" id="color-menu">
                    <div class="tool-submenu-item" data-action="brightness_contrast"><i class="fas fa-adjust"></i> Brightness & Contrast</div>
                    <div class="tool-submenu-item" data-action="saturation"><i class="fas fa-tint"></i> Saturation</div>
                </div>
                <div class="tool-submenu" id="filter-menu">
                    <div class="tool-submenu-item" data-action="grayscale"><i class="fas fa-moon"></i> Grayscale</div>
                    <div class="tool-submenu-item" data-action="blur"><i class="fas fa-blur"></i> Gaussian Blur</div>
                    <div class="tool-submenu-item" data-action="sharpen"><i class="fas fa-sharp"></i> Sharpen</div>
                    <div class="tool-submenu-item" data-action="sobel_edge"><i class="fas fa-angle-double-right"></i> Sobel Edge</div>
                </div>
                <div class="tool-submenu" id="morphology-menu">
                    <div class="tool-submenu-item" data-action="morphology" data-op="erosion"><i class="fas fa-compress-arrows-alt"></i> Erosion</div>
                    <div class="tool-submenu-item" data-action="morphology" data-op="dilation"><i class="fas fa-expand-arrows-alt"></i> Dilation</div>
                </div>
                <div class="tool-submenu" id="enhance-menu">
                    <div class="tool-submenu-item" data-action="gamma"><i class="fas fa-bolt"></i> Gamma Correction</div>
                    <div class="tool-submenu-item" data-action="threshold"><i class="fas fa-level-up-alt"></i> Thresholding</div>
                    <div class="tool-submenu-item" data-action="histogram_equalization"><i class="fas fa-chart-bar"></i> Histogram Equalization</div>
                </div>
            </div>
            <div class="card">
                <form action="{{ route('imago.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="mode" id="mode-input" value="rotate">
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

                    <div class="parameter-section feature-params active" id="transform-params">
                        <div class="parameter-group" data-param-for="rotate"><label for="rotation">Rotation Angle</label><div class="slider-container"><input type="range" min="-180" max="180" step="1" value="0" class="slider" name="angle" id="rotation"><span class="slider-value">0°</span></div></div>
                        <div class="parameter-group" data-param-for="resize" style="display: none;"><label for="scale_percent">Scale</label><div class="slider-container"><input type="range" min="10" max="200" step="5" value="100" class="slider" name="scale_percent" id="scale_percent"><span class="slider-value">100%</span></div></div>
                        <div class="parameter-group" data-param-for="flip" style="display: none;"><label>Flip Direction</label><select name="flip" class="filter-select" style="width:100%;padding:8px;border-radius:8px;border:1px solid #ccc;background-color:white;color:black;"><option value="1">Horizontal</option><option value="0">Vertical</option><option value="-1">Both</option></select></div>
                    </div>
                    <div class="parameter-section feature-params" id="color-params" style="display: none;">
                        <div class="parameter-group"><label for="brightness">Brightness</label><div class="slider-container"><input type="range" min="-100" max="100" step="1" value="0" class="slider" name="brightness" id="brightness"><span class="slider-value">0</span></div></div>
                        <div class="parameter-group"><label for="contrast">Contrast</label><div class="slider-container"><input type="range" min="-100" max="100" step="1" value="0" class="slider" name="contrast" id="contrast"><span class="slider-value">0</span></div></div>
                        <div class="parameter-group" data-param-for="saturation" style="display:none;"><label for="saturation">Saturation</label><div class="slider-container"><input type="range" min="-100" max="100" step="1" value="0" class="slider" name="saturation" id="saturation"><span class="slider-value">0</span></div></div>
                    </div>
                    <div class="parameter-section feature-params" id="filter-params" style="display: none;">
                        <div class="parameter-group" data-param-for="grayscale"><p style="color:var(--gray);font-size:14px;">No parameters needed for Grayscale.</p></div>
                        <div class="parameter-group" data-param-for="blur" style="display:none;"><label for="blur">Blur Kernel Size</label><div class="slider-container"><input type="range" min="1" max="21" step="2" value="1" class="slider" name="blur" id="blur"><span class="slider-value">1</span></div></div>
                        <div class="parameter-group" data-param-for="sharpen" style="display:none;"><p style="color:var(--gray);font-size:14px;">No parameters needed for Sharpen.</p></div>
                        <div class="parameter-group" data-param-for="sobel_edge" style="display:none;"><p style="color:var(--gray);font-size:14px;">No parameters needed for Sobel Edge Detection.</p></div>
                    </div>
                    <div class="parameter-section feature-params" id="morphology-params" style="display: none;">
                        <input type="hidden" name="morph_op" id="morph-op-input" value="erosion">
                        <div class="parameter-group"><label for="morph_kernel">Kernel Size</label><div class="slider-container"><input type="range" min="3" max="15" step="2" value="5" class="slider" name="morph_kernel" id="morph_kernel"><span class="slider-value">5</span></div></div>
                    </div>
                    <div class="parameter-section feature-params" id="enhance-params" style="display: none;">
                        <div class="parameter-group" data-param-for="gamma"><label for="gamma">Gamma Value</label><div class="slider-container"><input type="range" min="0.1" max="3.0" step="0.1" value="1.0" class="slider" name="gamma" id="gamma"><span class="slider-value">1.0</span></div></div>
                        <div class="parameter-group" data-param-for="threshold" style="display: none;"><label for="threshold_value">Threshold Value</label><div class="slider-container"><input type="range" min="0" max="255" step="1" value="128" class="slider" name="threshold_value" id="threshold_value"><span class="slider-value">128</span></div><div class="toggle-container"><span class="toggle-label">Adaptive Threshold</span><label class="toggle-switch"><input type="checkbox" name="adaptive_threshold"><span class="slider-toggle"></span></label></div></div>
                        <div class="parameter-group" data-param-for="histogram_equalization" style="display:none;"><p style="color:var(--gray);font-size:14px;">No parameters needed for Histogram Equalization.</p></div>
                    </div>

                    <div class="status-indicator"><i class="fas fa-check-circle"></i><span class="status-text">Ready for processing</span></div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-bolt"></i> Apply Changes</button>
                        @if(isset($processedUrl))<a href="{{$processedUrl}}" download="processed_image.png" class="btn btn-outline" style="text-decoration:none;"><i class="fas fa-download"></i> Download</a>@endif
                    </div>
                </div>
            </form>
        @endif
    </div>

<script>
    'use strict';
    // --- Star background animation ---
    const canvas = document.getElementById('stars-canvas');
    if (canvas) {
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
    }

    // --- Profile Dropdown Logic ---
    const profileToggle = document.getElementById('profileToggle');
    const profileMenu = document.getElementById('profileMenu');
    if (profileToggle) {
        profileToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            profileMenu.classList.toggle('show');
        });
    }
    document.addEventListener('click', function (e) {
        if (profileToggle && !profileToggle.contains(e.target) && !profileMenu.contains(e.target)) {
            profileMenu.classList.remove('show');
        }
    });

    // --- Image Preview Logic ---
    const fileInput = document.getElementById('image-upload');
    const originalPreview = document.getElementById('original-preview');
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    originalPreview.innerHTML = `<img src="${e.target.result}" alt="Original" style="width: 100%; height: 100%; object-fit: contain;">`;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // --- Slider value update logic ---
    document.querySelectorAll('.slider').forEach(slider => {
        const display = slider.parentElement.querySelector('.slider-value');
        if (display) {
            const updateSliderDisplay = () => {
                let value = slider.value;
                if (slider.id === 'rotation' || slider.id === 'hue') display.textContent = value + '°';
                else if (slider.id === 'scale_percent' || slider.id === 'crop-width' || slider.id === 'crop-height' || slider.id === 'denoise' || slider.id === 'opacity') display.textContent = value + '%';
                else if (slider.id === 'gamma') display.textContent = parseFloat(value).toFixed(1);
                else if (slider.id === 'kernel-size') display.textContent = value + 'x' + value;
                else if (slider.id === 'scale') display.textContent = value + 'x';
                else display.textContent = value;
            };
            updateSliderDisplay();
            slider.addEventListener('input', updateSliderDisplay);
        }
    });

    // --- UI Interaction Logic ---
    const modeInput = document.getElementById('mode-input');

    // Logic for Advanced AI feature selector
    const featureOptions = document.querySelectorAll('.feature-option');
    if (featureOptions.length > 0) {
        const allParams = document.querySelectorAll('.parameter-section.feature-params');
        featureOptions.forEach(option => {
            option.addEventListener('click', () => {
                if (option.getAttribute('data-feature') === 'playground' || option.getAttribute('data-feature') === 'showcase') return;

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

    // Logic for Basic Tools selector
    const toolCategories = document.querySelectorAll('.tool-category');
    const toolSubmenus = document.querySelectorAll('.tool-submenu');
    const toolSubmenuItems = document.querySelectorAll('.tool-submenu-item');
    if (toolCategories.length > 0) {
        toolCategories.forEach(category => {
            category.addEventListener('click', () => {
                if (category.style.cursor === 'not-allowed') return;
                toolCategories.forEach(cat => cat.classList.remove('active'));
                category.classList.add('active');
                const tool = category.getAttribute('data-tool');
                toolSubmenus.forEach(menu => {
                    menu.style.display = menu.id === tool + '-menu' ? 'flex' : 'none';
                });
                const firstToolInSubmenu = document.querySelector(`#${tool}-menu .tool-submenu-item`);
                if(firstToolInSubmenu) firstToolInSubmenu.click();
            });
        });

        toolSubmenuItems.forEach(item => {
            item.addEventListener('click', () => {
                item.parentElement.querySelectorAll('.tool-submenu-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                const action = item.getAttribute('data-action');
                modeInput.value = action;

                // Special case for morphology to pass the specific operation
                if(action === 'morphology') {
                    const morphOpInput = document.getElementById('morph-op-input');
                    if(morphOpInput) morphOpInput.value = item.getAttribute('data-op');
                }

                const parentMenuId = item.closest('.tool-submenu').id;
                const toolName = parentMenuId.replace('-menu', '');
                document.querySelectorAll('.parameter-section.feature-params').forEach(params => {
                    params.style.display = 'none';
                });
                const activeParamSection = document.getElementById(toolName + '-params');
                if (activeParamSection) {
                    activeParamSection.style.display = 'block';
                    activeParamSection.querySelectorAll('.parameter-group').forEach(paramGroup => {
                        paramGroup.style.display = paramGroup.getAttribute('data-param-for') === action || !paramGroup.hasAttribute('data-param-for') ? 'block' : 'none';
                    });
                }
            });
        });

        // Initialize view on page load if we are in the basic tools editor
        if (document.querySelector('.tool-category.active')) {
            document.querySelector('.tool-category.active').click();
        }
    }
</script>
</body>
</html>
