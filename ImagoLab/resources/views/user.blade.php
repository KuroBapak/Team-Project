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

    {{-- Styles for our loading overlay and canvas placeholder --}}
    @if(!$isAdvanced)
    <style>
        #loader-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(10, 25, 47, 0.8); display: flex;
            justify-content: center; align-items: center; z-index: 1000;
            flex-direction: column; color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            transition: opacity 0.3s ease;
        }
        #loader-overlay .fa-spinner { font-size: 3rem; margin-bottom: 1rem; }
        #loader-overlay p { font-size: 1.2rem; }
        .content-hidden { opacity: 0; pointer-events: none; }
        .content-visible { opacity: 1; transition: opacity 0.5s ease; }

        #canvas-placeholder {
            width: 100%; height: 100%;
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            color: var(--border-color);
        }
        #canvas-placeholder i { font-size: 3rem; }
        #canvas-placeholder p { margin-top: 1rem; font-size: 1rem; }

        /* FIX: Ensure canvas size is not overly constrained by CSS */
        #main-canvas {
            max-width: 100%;
            max-height: 100%;
            /* object-fit is for img, not canvas, but max-width/height are key */
        }
    </style>
    @endif
</head>
<body>
    <canvas id="stars-canvas"></canvas>

    {{-- The entire HTML structure of your page remains the same --}}
    {{-- ... your full header ... --}}
    {{-- ... your full container with both advanced and basic editor HTML ... --}}
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

    @if(!$isAdvanced)
    <div id="loader-overlay">
        <i class="fas fa-spinner fa-spin"></i>
        <p>Loading Interactive Editor...</p>
    </div>
    @endif

    <div class="container {{ $isAdvanced ? '' : 'content-hidden' }}" id="main-content">
        @if($isAdvanced)
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
                {{-- FIX: Add ID to form for JS targeting --}}
                <form action="{{ route('imago.process') }}" method="POST" enctype="multipart/form-data" id="advanced-image-form">
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
                <form action="{{ route('imago.process') }}" method="POST" enctype="multipart/form-data" id="image-form">
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
                        <div class="comparison-panel"><div class="comparison-title">PROCESSED</div>
                            <div class="comparison-image" id="processed-preview">
                                <canvas id="main-canvas" style="display: none;"></canvas>
                                <div id="canvas-placeholder">
                                    <i class="fas fa-magic"></i>
                                    <p>Your edits will appear here</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="parameter-section feature-params active" id="transform-params">
                        <div class="parameter-group" data-param-for="rotate"><label for="rotation">Rotation Angle</label><div class="slider-container"><input type="range" min="-180" max="180" step="1" value="0" class="slider" name="angle" id="rotation"><span class="slider-value">0°</span></div></div>
                        <div class="parameter-group" data-param-for="resize" style="display: none;"><label for="scale_percent">Scale</label><div class="slider-container"><input type="range" min="10" max="200" step="5" value="100" class="slider" name="scale_percent" id="scale_percent"><span class="slider-value">100%</span></div></div>
                        <div class="parameter-group" data-param-for="flip" style="display: none;"><label>Flip Direction</label><select name="flip" id="flip-select" class="filter-select" style="width:100%;padding:8px;border-radius:8px;border:1px solid #ccc;background-color:white;color:black;"><option value="99">None</option><option value="1">Horizontal</option><option value="0">Vertical</option><option value="-1">Both</option></select></div>
                    </div>
                    <div class="parameter-section feature-params" id="color-params" style="display: none;">
                        <div class="parameter-group" data-param-for="brightness_contrast"><label for="brightness">Brightness</label><div class="slider-container"><input type="range" min="-100" max="100" step="1" value="0" class="slider" name="brightness" id="brightness"><span class="slider-value">0</span></div></div>
                        <div class="parameter-group" data-param-for="brightness_contrast"><label for="contrast">Contrast</label><div class="slider-container"><input type="range" min="-100" max="100" step="1" value="0" class="slider" name="contrast" id="contrast"><span class="slider-value">0</span></div></div>
                        <div class="parameter-group" data-param-for="saturation" style="display:none;"><label for="saturation">Saturation</label><div class="slider-container"><input type="range" min="-100" max="100" step="1" value="0" class="slider" name="saturation" id="saturation"><span class="slider-value">0</span></div></div>
                    </div>
                    <div class="parameter-section feature-params" id="filter-params" style="display: none;">
                        <div class="parameter-group" data-param-for="grayscale" style="display:none;"><p style="color:var(--gray);font-size:14px;">This is now a toggle. Use the button below.</p><button type="button" class="btn btn-outline" id="grayscale-btn" style="width:100%">Toggle Grayscale</button></div>
                        <div class="parameter-group" data-param-for="blur" style="display:none;"><label for="blur">Blur Kernel Size</label><div class="slider-container"><input type="range" min="1" max="21" step="2" value="1" class="slider" name="blur" id="blur"><span class="slider-value">1</span></div></div>
                        <div class="parameter-group" data-param-for="sharpen" style="display:none;"><p style="color:var(--gray);font-size:14px;">No parameters needed.</p></div>
                        <div class="parameter-group" data-param-for="sobel_edge" style="display:none;"><p style="color:var(--gray);font-size:14px;">No parameters needed.</p></div>
                    </div>
                    <div class="parameter-section feature-params" id="morphology-params" style="display: none;">
                        <input type="hidden" name="morph_op" id="morph-op-input" value="erosion">
                        <div class="parameter-group"><label for="morph_kernel">Kernel Size</label><div class="slider-container"><input type="range" min="3" max="15" step="2" value="5" class="slider" name="morph_kernel" id="morph_kernel"><span class="slider-value">5</span></div></div>
                    </div>
                    <div class="parameter-section feature-params" id="enhance-params" style="display: none;">
                        <div class="parameter-group" data-param-for="gamma"><label for="gamma">Gamma Value</label><div class="slider-container"><input type="range" min="0.1" max="3.0" step="0.1" value="1.0" class="slider" name="gamma" id="gamma"><span class="slider-value">1.0</span></div></div>
                        <div class="parameter-group" data-param-for="threshold" style="display: none;"><label for="threshold_value">Threshold Value</label><div class="slider-container"><input type="range" min="0" max="255" step="1" value="128" class="slider" name="threshold_value" id="threshold_value"><span class="slider-value">128</span></div><div class="toggle-container"><span class="toggle-label">Adaptive Threshold</span><label class="toggle-switch"><input type="checkbox" name="adaptive_threshold" id="adaptive-checkbox"><span class="slider-toggle"></span></label></div></div>
                        <div class="parameter-group" data-param-for="histogram_equalization" style="display:none;"><p style="color:var(--gray);font-size:14px;">No parameters needed.</p></div>
                    </div>

                    <div class="status-indicator"><i class="fas fa-check-circle"></i><span class="status-text">Ready for processing</span></div>
                    <div class="btn-group">
                        <button type="button" id="process-btn" class="btn btn-primary"><i class="fas fa-save"></i> Finalize & Save</button>
                        @if(isset($processedUrl))<a href="{{$processedUrl}}" download="processed_image.png" class="btn btn-outline" style="text-decoration:none;"><i class="fas fa-download"></i> Download</a>@endif
                    </div>
                </div>
            </form>
        @endif
    </div>


    {{-- All the logic is now in this one script block --}}
    @if(!$isAdvanced)
        <script async src="https://docs.opencv.org/4.9.0/opencv.js" onload="onOpenCvReady();"></script>
    @endif

    <script>
'use strict';

// --- Star background animation ---
const starsCanvas = document.getElementById('stars-canvas');
if (starsCanvas) {
    const ctx = starsCanvas.getContext('2d');
    function resizeCanvas() { starsCanvas.width = window.innerWidth; starsCanvas.height = window.innerHeight; }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
    const stars = Array.from({ length: 110 }, () => ({ x: Math.random() * starsCanvas.width, y: Math.random() * starsCanvas.height, size: Math.random() * 1.7, speedX: (Math.random() - 0.5) * 0.14, speedY: (Math.random() - 0.5) * 0.14, baseOpacity: Math.random() * 0.55 + 0.2 }));
    function animateStars() { ctx.clearRect(0, 0, starsCanvas.width, starsCanvas.height); stars.forEach(star => { star.x += star.speedX; star.y += star.speedY; if (star.x < 0 || star.x > starsCanvas.width) star.speedX *= -1; if (star.y < 0 || star.y > starsCanvas.height) star.speedY *= -1; const opacity = star.baseOpacity + Math.sin(Date.now() * 0.001 + star.x * 0.01) * 0.18; ctx.fillStyle = `rgba(255, 255, 255, ${Math.max(0.1, opacity)})`; ctx.beginPath(); ctx.arc(star.x, star.y, star.size, 0, Math.PI * 2); ctx.fill(); }); requestAnimationFrame(animateStars); }
    animateStars();
}

// --- Profile Dropdown Logic ---
const profileToggle = document.getElementById('profileToggle');
if (profileToggle) {
    const profileMenu = document.getElementById('profileMenu');
    profileToggle.addEventListener('click', (e) => { e.stopPropagation(); profileMenu.classList.toggle('show'); });
    document.addEventListener('click', (e) => { if (!profileToggle.contains(e.target) && !profileMenu.contains(e.target)) profileMenu.classList.remove('show'); });
}

// --- Slider value text update logic ---
document.querySelectorAll('.slider').forEach(slider => {
    const display = slider.parentElement.querySelector('.slider-value');
    if (display) {
        const updateSliderDisplay = () => { let value = slider.value; if (slider.id === 'rotation') display.textContent = `${value}°`; else if (slider.id === 'scale_percent') display.textContent = `${value}%`; else if (slider.id === 'gamma') display.textContent = parseFloat(value).toFixed(1); else display.textContent = value; };
        updateSliderDisplay();
        slider.addEventListener('input', updateSliderDisplay);
    }
});

// --- Main Editor Logic (Runs after OpenCV.js is loaded for Basic Tools) ---
function onOpenCvReady() {
    cv.onRuntimeInitialized = () => {
        console.log("✅ OpenCV.js is ready.");
        document.getElementById('loader-overlay').style.display = 'none';
        document.getElementById('main-content').classList.remove('content-hidden');

        // --- Basic Tools: Element Selectors ---
        const imageUpload = document.getElementById('image-upload');
        const originalPreview = document.getElementById('original-preview');
        const canvas = document.getElementById('main-canvas');
        const canvasPlaceholder = document.getElementById('canvas-placeholder');
        const imageForm = document.getElementById('image-form');
        const processBtn = document.getElementById('process-btn');
        const modeInput = document.getElementById('mode-input');
        const controls = document.querySelectorAll('.slider, #flip-select, #adaptive-checkbox, #grayscale-btn');

        // --- Basic Tools: State Variables ---
        let originalImageMat = null;
        let isImageLoaded = false;
        let currentGrayscale = false;
        let originalFile = null;

        // --- Basic Tools: Image Loader ---
        imageUpload.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                originalFile = e.target.files[0];
                const reader = new FileReader();
                reader.onload = (event) => {
                    originalPreview.innerHTML = `<img src="${event.target.result}" alt="Original" style="width: 100%; height: 100%; object-fit: contain;">`;
                    const imgElement = new Image();
                    imgElement.src = event.target.result;
                    imgElement.onload = () => {
                        canvasPlaceholder.style.display = 'none';
                        canvas.style.display = 'block';
                        if (originalImageMat) originalImageMat.delete();
                        originalImageMat = cv.imread(imgElement);
                        isImageLoaded = true;
                        applyAllChanges();
                    }
                }
                reader.readAsDataURL(originalFile);
            }
        });

        // --- Basic Tools: Add Event Listeners to all controls ---
        controls.forEach(control => {
            const eventType = control.tagName === 'BUTTON' ? 'click' : 'input';
            control.addEventListener(eventType, () => {
                if (control.id === 'grayscale-btn') currentGrayscale = !currentGrayscale;
                requestAnimationFrame(applyAllChanges);
            });
        });

        // --- Basic Tools: Real-time Processing Engine ---
        function applyAllChanges() {
            if (!isImageLoaded) return;

            let src = originalImageMat.clone();

            // --- Apply effects in a specific order ---
            // 1. Resize
            const scalePercent = parseInt(document.getElementById('scale_percent').value);
            if (scalePercent !== 100) {
                let dsize = new cv.Size(src.cols * scalePercent / 100, src.rows * scalePercent / 100);
                cv.resize(src, src, dsize, 0, 0, cv.INTER_AREA);
            }

            // 2. Grayscale
            if (currentGrayscale) cv.cvtColor(src, src, cv.COLOR_RGBA2GRAY, 0);

            // 3. Brightness & Contrast (Note: Grayscale images need to be converted back for this)
            if (currentGrayscale) cv.cvtColor(src, src, cv.COLOR_GRAY2RGBA, 0);
            const brightness = parseInt(document.getElementById('brightness').value);
            const contrast = parseFloat(document.getElementById('contrast').value);
            if (brightness !== 0 || contrast !== 0) {
                const alpha = 1 + (contrast / 100.0);
                cv.convertScaleAbs(src, src, alpha, brightness);
            }

            // 4. Rotation
            const angle = parseInt(document.getElementById('rotation').value);
            if (angle !== 0) {
                let dsize = new cv.Size(src.cols, src.rows);
                let center = new cv.Point(src.cols / 2, src.rows / 2);
                let M = cv.getRotationMatrix2D(center, angle, 1);
                cv.warpAffine(src, src, M, dsize, cv.INTER_LINEAR, cv.BORDER_CONSTANT, new cv.Scalar());
                M.delete();
            }

            // 5. Flip
            const flipCode = parseInt(document.getElementById('flip-select').value);
            if (flipCode !== 99) cv.flip(src, src, flipCode);

            // --- Display final result on canvas ---
            canvas.width = src.cols;
            canvas.height = src.rows;
            cv.imshow('main-canvas', src);
            src.delete();
        }

        // --- Basic Tools: Finalize, Download & Save Button Logic ---
        processBtn.addEventListener('click', () => {
            if (!isImageLoaded) { alert('Please upload an image first!'); return; }

            processBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            processBtn.disabled = true;

            // 1. Convert canvas to a Blob for downloading and saving
            canvas.toBlob((blob) => {
                // 2. Trigger Download
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `ImagoLab_${originalFile.name}`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(link.href); // Clean up memory

                // 3. Save to History
                const formData = new FormData();
                formData.append('image', new File([blob], originalFile.name, { type: originalFile.type }));
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('mode', 'resize');
                formData.append('scale_percent', '100');

                fetch(imageForm.action, { method: 'POST', body: formData })
                    .then(response => {
                        if(response.ok) window.location.href = "{{ route('tool.select') }}";
                        else throw new Error('Server responded with an error.');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred during save. See console for details.');
                        processBtn.innerHTML = '<i class="fas fa-save"></i> Finalize & Save';
                        processBtn.disabled = false;
                    });
            }, originalFile.type);
        });

        // --- Basic Tools: UI Wiring for menus, tabs, etc. ---
        const toolCategories = document.querySelectorAll('.tool-category');
        const toolSubmenus = document.querySelectorAll('.tool-submenu');
        const toolSubmenuItems = document.querySelectorAll('.tool-submenu-item');

        toolCategories.forEach(category => {
            category.addEventListener('click', () => {
                if (category.style.cursor === 'not-allowed') return;
                toolCategories.forEach(cat => cat.classList.remove('active'));
                category.classList.add('active');
                const tool = category.getAttribute('data-tool');
                toolSubmenus.forEach(menu => {
                    menu.style.display = menu.id === `${tool}-menu` ? 'flex' : 'none';
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

                if(action === 'morphology') {
                    const morphOpInput = document.getElementById('morph-op-input');
                    if(morphOpInput) morphOpInput.value = item.getAttribute('data-op');
                }

                const parentMenuId = item.closest('.tool-submenu').id;
                const toolName = parentMenuId.replace('-menu', '');
                document.querySelectorAll('.parameter-section.feature-params').forEach(params => {
                    params.style.display = 'none';
                });
                const activeParamSection = document.getElementById(`${toolName}-params`);
                if (activeParamSection) {
                    activeParamSection.style.display = 'block';
                    activeParamSection.querySelectorAll('.parameter-group').forEach(paramGroup => {
                        const paramFor = paramGroup.getAttribute('data-param-for');
                        paramGroup.style.display = (paramFor === action || !paramFor) ? 'block' : 'none';
                    });
                }

                requestAnimationFrame(applyAllChanges);
            });
        });

        if (document.querySelector('.tool-category.active')) {
            document.querySelector('.tool-category.active').click();
        }
    };
}

// --- Advanced AI: UI Logic (This will only run if the page loads in advanced mode) ---
const featureOptions = document.querySelectorAll('.feature-option');
if (featureOptions.length > 0) {
    const advancedForm = document.getElementById('advanced-image-form');
    const imageUpload = document.getElementById('image-upload');
    const originalPreview = document.getElementById('original-preview');

    // Show original image preview on upload
    imageUpload.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                originalPreview.innerHTML = `<img src="${e.target.result}" alt="Original" style="width: 100%; height: 100%; object-fit: contain;">`;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Add check for no image on submit
    advancedForm.addEventListener('submit', function (e) {
        if (imageUpload.files.length === 0) {
            e.preventDefault(); // Stop the form submission
            alert('Please upload an image before enhancing.');
        }
    });

    // Logic for switching between AI features like removebg/superres
    const modeInput = document.getElementById('mode-input');
    const allParams = document.querySelectorAll('.parameter-section.feature-params');
    featureOptions.forEach(option => {
        option.addEventListener('click', () => {
            if (option.style.cursor === 'not-allowed') return;
            featureOptions.forEach(opt => opt.classList.remove('active'));
            option.classList.add('active');
            const feature = option.getAttribute('data-feature');
            modeInput.value = feature;
            allParams.forEach(p => p.classList.remove('active'));
            const targetParams = document.getElementById(`${feature}-params`);
            if (targetParams) targetParams.classList.add('active');
        });
    });
}
    </script>
</body>
</html>
