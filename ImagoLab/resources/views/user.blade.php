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

    @if(!$isAdvanced)
    <style>
        #loader-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(10, 25, 47, 0.8); display: flex; justify-content: center; align-items: center; z-index: 1000; flex-direction: column; color: #fff; font-family: sans-serif; transition: opacity 0.3s ease; }
        #loader-overlay .fa-spinner { font-size: 3rem; margin-bottom: 1rem; }
        .content-hidden { opacity: 0; pointer-events: none; }
        .content-visible { opacity: 1; transition: opacity 0.5s ease; }
        #canvas-placeholder { width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; color: var(--border-color); }
        #canvas-placeholder i { font-size: 3rem; }
        #canvas-placeholder p { margin-top: 1rem; font-size: 1rem; }
        #main-canvas { max-width: 100%; max-height: 100%; }
        .parameter-section { display: none; } /* Hide all by default */
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
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
                    {{ Auth::user()->name }} <i class="fas fa-chevron-down" style="font-size:12px;"></i>
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
    <div id="loader-overlay"><i class="fas fa-spinner fa-spin"></i><p>Loading Interactive Editor...</p></div>
    @endif

    <div class="container {{ $isAdvanced ? '' : 'content-hidden' }}" id="main-content">
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
                    <div class="tool-category active" data-tool="transform"><i class="fas fa-expand-arrows-alt"></i><h3>Transform</h3></div>
                    <div class="tool-category" data-tool="color"><i class="fas fa-palette"></i><h3>Color</h3></div>
                    <div class="tool-category" data-tool="filters"><i class="fas fa-filter"></i><h3>Filters</h3></div>
                    <div class="tool-category" data-tool="edges"><i class="fas fa-paint-brush"></i><h3>Edges</h3></div>
                    <div class="tool-category" data-tool="morphology"><i class="fas fa-shapes"></i><h3>Morphology</h3></div>
                    <div class="tool-category" data-tool="frequency"><i class="fas fa-wave-square"></i><h3>Frequency</h3></div>
                </div>
                <div class="tool-submenu active" id="transform-menu"><div class="tool-submenu-item active" data-action="transform"><i class="fas fa-arrows-alt"></i> General</div></div>
                <div class="tool-submenu" id="color-menu"><div class="tool-submenu-item" data-action="color"><i class="fas fa-palette"></i> General</div><div class="tool-submenu-item" data-action="histogram_equalization"><i class="fas fa-chart-bar"></i> Equalize</div></div>
                <div class="tool-submenu" id="filters-menu"><div class="tool-submenu-item" data-action="filters"><i class="fas fa-filter"></i> General</div><div class="tool-submenu-item" data-action="sharpen"><i class="fas fa-sharp"></i> Sharpen</div><div class="tool-submenu-item" data-action="threshold"><i class="fas fa-level-up-alt"></i> Threshold</div></div>
                <div class="tool-submenu" id="edges-menu"><div class="tool-submenu-item" data-action="sobel_edge"><i class="fas fa-border-style"></i> Sobel</div><div class="tool-submenu-item" data-action="laplacian_edge"><i class="fas fa-border-all"></i> Laplacian</div><div class="tool-submenu-item" data-action="prewitt_edge"><i class="fas fa-border-none"></i> Prewitt</div></div>
                <div class="tool-submenu" id="morphology-menu"><div class="tool-submenu-item" data-action="morphology"><i class="fas fa-compress-arrows-alt"></i> Operations</div></div>
                <div class="tool-submenu" id="frequency-menu"><div class="tool-submenu-item" data-action="frequency"><i class="fas fa-filter"></i> Filters</div></div>
            </div>
            <div class="card">
                <form action="{{ route('imago.process') }}" method="POST" enctype="multipart/form-data" id="image-form">
                    @csrf
                    <input type="hidden" name="mode" id="mode-input" value="transform">
                    <div class="card-header"><i class="fas fa-sliders-h"></i><h2>Configure & Process</h2></div>
                    <div class="file-upload"><input type="file" name="image" id="image-upload" class="file-input" accept="image/*" required><label for="image-upload" class="file-label"><i class="fas fa-upload"></i> Upload Image</label></div>
                    @if($errors->any()||session('error'))<div class="info-box" style="background-color:#f8d7da;color:#721c24;border:1px solid #f5c6cb;margin:20px 0;">@if(session('error'))<p>{{session('error')}}</p>@endif @foreach($errors->all() as $error)<p>{{$error}}</p>@endforeach</div>@endif
                    <div class="comparison-view">
                        <div class="comparison-panel"><div class="comparison-title">ORIGINAL</div><div class="comparison-image" id="original-preview">@if(isset($originalUrl))<img src="{{$originalUrl}}" style="width: 100%; height: 100%; object-fit: contain;">@else<i class="fas fa-image"></i>@endif</div></div>
                        <div class="comparison-panel"><div class="comparison-title">PROCESSED</div><div class="comparison-image" id="processed-preview"><canvas id="main-canvas" style="display: none;"></canvas><div id="canvas-placeholder"><i class="fas fa-magic"></i><p>Your edits will appear here</p></div></div></div>
                    </div>

                    {{-- All Parameter Sections --}}
                    <div class="parameter-section" data-param-for="transform"><label>Rotation Angle</label><div class="slider-container"><input type="range" min="-180" max="180" value="0" class="slider" name="angle" id="rotation"><span class="slider-value">0°</span></div></div>
                    <div class="parameter-section" data-param-for="transform"><label>Scale</label><div class="slider-container"><input type="range" min="10" max="200" value="100" class="slider" name="scale_percent" id="scale_percent"><span class="slider-value">100%</span></div></div>
                    <div class="parameter-section" data-param-for="transform"><label>Flip Direction</label><select name="flip" id="flip-select" class="filter-select"><option value="99">None</option><option value="1">Horizontal</option><option value="0">Vertical</option><option value="-1">Both</option></select></div>
                    <div class="parameter-section" data-param-for="color"><label>Brightness</label><div class="slider-container"><input type="range" min="-100" max="100" value="0" class="slider" name="brightness" id="brightness"><span class="slider-value">0</span></div></div>
                    <div class="parameter-section" data-param-for="color"><label>Contrast</label><div class="slider-container"><input type="range" min="-100" max="100" value="0" class="slider" name="contrast" id="contrast"><span class="slider-value">0</span></div></div>
                    <div class="parameter-section" data-param-for="color"><label>Hue</label><div class="slider-container"><input type="range" min="-90" max="90" value="0" class="slider" name="hue" id="hue"><span class="slider-value">0</span></div></div>
                    <div class="parameter-section" data-param-for="color"><label>Saturation</label><div class="slider-container"><input type="range" min="-100" max="100" value="0" class="slider" name="saturation" id="saturation"><span class="slider-value">0</span></div></div>
                    <div class="parameter-section" data-param-for="color"><label>Gamma</label><div class="slider-container"><input type="range" min="0.1" max="3.0" step="0.1" value="1.0" class="slider" name="gamma" id="gamma"><span class="slider-value">1.0</span></div></div>
                    <div class="parameter-section" data-param-for="histogram_equalization"><p>No parameters needed for this effect.</p></div>
                    <div class="parameter-section" data-param-for="filters"><label>Gaussian Blur</label><div class="slider-container"><input type="range" min="1" max="41" step="2" value="1" class="slider" name="blur" id="gaussian_blur"><span class="slider-value">1</span></div></div>
                    <div class="parameter-section" data-param-for="filters"><label>Mean Blur</label><div class="slider-container"><input type="range" min="1" max="41" step="2" value="1" class="slider" name="mean_blur" id="mean_blur"><span class="slider-value">1</span></div></div>
                    <div class="parameter-section" data-param-for="filters"><label>Median Blur</label><div class="slider-container"><input type="range" min="3" max="41" step="2" value="3" class="slider" name="median_blur" id="median_blur"><span class="slider-value">3</span></div></div>
                    <div class="parameter-section" data-param-for="sharpen"><p>No parameters needed for this effect.</p></div>
                    <div class="parameter-section" data-param-for="threshold"><label>Threshold Value</label><div class="slider-container"><input type="range" min="0" max="255" value="128" class="slider" name="threshold_value" id="threshold_value"><span class="slider-value">128</span></div></div>
                    <div class="parameter-section" data-param-for="sobel_edge"><p>No parameters needed for this effect.</p></div>
                    <div class="parameter-section" data-param-for="laplacian_edge"><p>No parameters needed for this effect.</p></div>
                    <div class="parameter-section" data-param-for="prewitt_edge"><p>No parameters needed for this effect.</p></div>
                    <div class="parameter-section" data-param-for="morphology"><label>Operation</label><select name="morph_op" id="morph_op" class="filter-select"><option value="erosion">Erosion</option><option value="dilation">Dilation</option><option value="opening">Opening</option><option value="closing">Closing</option></select></div>
                    <div class="parameter-section" data-param-for="morphology"><label>Kernel Size</label><div class="slider-container"><input type="range" min="3" max="15" step="2" value="5" class="slider" name="morph_kernel" id="morph_kernel"><span class="slider-value">5</span></div></div>
                    <div class="parameter-section" data-param-for="frequency"><label>Filter Type</label><select name="freq_op" id="freq_op" class="filter-select"><option value="low_pass">Low-Pass</option><option value="high_pass">High-Pass</option></select></div>
                    <div class="parameter-section" data-param-for="frequency"><label>Radius</label><div class="slider-container"><input type="range" min="1" max="100" value="30" class="slider" name="fourier_radius" id="fourier_radius"><span class="slider-value">30</span></div></div>

                    <div class="btn-group">
                        <button type="button" id="undo-btn" class="btn btn-outline"><i class="fas fa-undo"></i> Undo</button>
                        <button type="button" id="redo-btn" class="btn btn-outline"><i class="fas fa-redo"></i> Redo</button>
                        <button type="button" id="reset-btn" class="btn btn-outline danger"><i class="fas fa-times-circle"></i> Reset</button>
                        <button type="button" id="process-btn" class="btn btn-primary"><i class="fas fa-save"></i> Finalize & Save</button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    @if(!$isAdvanced)
        <script async src="https://docs.opencv.org/4.9.0/opencv.js" onload="onOpenCvReady();"></script>
    @endif

    <script>
        'use strict';

document.addEventListener('DOMContentLoaded', () => {
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

    // --- Generic Slider value text update logic ---
    document.querySelectorAll('.slider').forEach(slider => {
        const display = slider.parentElement.querySelector('.slider-value');
        if (display) {
            const updateSliderDisplay = () => { let value = slider.value; if (slider.id === 'rotation') display.textContent = `${value}°`; else if (slider.id === 'scale_percent') display.textContent = `${value}%`; else if (slider.id === 'gamma') display.textContent = parseFloat(value).toFixed(1); else display.textContent = value; };
            updateSliderDisplay();
            slider.addEventListener('input', updateSliderDisplay);
        }
    });

    // --- Advanced AI View Logic ---
    const featureOptions = document.querySelectorAll('.feature-option');
    if (featureOptions.length > 0) {
        const modeInput = document.getElementById('mode-input');
        const imageUpload = document.getElementById('image-upload');
        const originalPreview = document.getElementById('original-preview');
        featureOptions.forEach(option => {
            option.addEventListener('click', () => {
                if (option.style.cursor === 'not-allowed') return;
                featureOptions.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
                const feature = option.getAttribute('data-feature');
                modeInput.value = feature;
                document.querySelectorAll('.parameter-section.feature-params').forEach(p => p.classList.remove('active'));
                const targetParams = document.getElementById(`${feature}-params`);
                if (targetParams) targetParams.classList.add('active');
            });
        });
        imageUpload.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) { originalPreview.innerHTML = `<img src="${e.target.result}" alt="Original" style="width: 100%; height: 100%; object-fit: contain;">`; }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});


// =====================================================================
// == BASIC TOOLS EDITOR LOGIC (OPTIMIZED FOR PERFORMANCE)
// =====================================================================
function onOpenCvReady() {
    cv.onRuntimeInitialized = () => {
        console.log("✅ OpenCV.js is ready with high-performance engine.");
        document.getElementById('loader-overlay').style.display = 'none';
        document.getElementById('main-content').classList.remove('content-hidden');

        // --- Selectors ---
        const imageUpload = document.getElementById('image-upload');
        const originalPreview = document.getElementById('original-preview');
        const canvas = document.getElementById('main-canvas');
        const processBtn = document.getElementById('process-btn');
        const modeInput = document.getElementById('mode-input');
        const allControls = document.querySelectorAll('.slider, .filter-select');
        const undoBtn = document.getElementById('undo-btn');
        const redoBtn = document.getElementById('redo-btn');
        const resetBtn = document.getElementById('reset-btn');

        // --- State Variables ---
        let originalImageMat = null;
        let isImageLoaded = false;
        let originalFile = null;
        let history = [];
        let historyIndex = -1;
        let debounceTimer;

        function debounce(func, delay) { clearTimeout(debounceTimer); debounceTimer = setTimeout(func, delay); }

        // --- SIMPLIFIED & FAST History Management ---
        function pushHistory() {
            if (historyIndex < history.length - 1) {
                const toDelete = history.splice(historyIndex + 1);
                toDelete.forEach(mat => mat.delete());
            }
            const mat = cv.imread(canvas);
            history.push(mat);
            historyIndex++;
            if (history.length > 30) {
                history.shift().delete();
                historyIndex--;
            }
            updateHistoryButtons();
        }

        function undo() {
            if (historyIndex > 0) {
                historyIndex--;
                const prevState = history[historyIndex];
                canvas.width = prevState.cols; canvas.height = prevState.rows;
                cv.imshow('main-canvas', prevState);
            }
            updateHistoryButtons();
        }

        function redo() {
            if (historyIndex < history.length - 1) {
                historyIndex++;
                const nextState = history[historyIndex];
                canvas.width = nextState.cols; canvas.height = nextState.rows;
                cv.imshow('main-canvas', nextState);
            }
            updateHistoryButtons();
        }

        function reset() {
            if (!isImageLoaded) return;
            allControls.forEach(control => {
                if (control.tagName === 'SELECT') {
                    control.value = control.querySelector('option').value;
                } else if (control.type === 'range') {
                    control.value = control.defaultValue;
                }
                control.dispatchEvent(new Event('input'));
            });
        }

        function updateHistoryButtons() {
            undoBtn.disabled = historyIndex <= 0;
            redoBtn.disabled = historyIndex >= history.length - 1;
            resetBtn.disabled = historyIndex <= 0;
        }

        undoBtn.addEventListener('click', undo);
        redoBtn.addEventListener('click', redo);
        resetBtn.addEventListener('click', reset);

        imageUpload.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                originalFile = e.target.files[0];
                const reader = new FileReader();
                reader.onload = (event) => {
                    originalPreview.innerHTML = `<img src="${event.target.result}" alt="Original" style="width: 100%; height: 100%; object-fit: contain;">`;
                    const imgElement = new Image();
                    imgElement.src = event.target.result;
                    imgElement.onload = () => {
                        document.getElementById('canvas-placeholder').style.display = 'none'; canvas.style.display = 'block';
                        if (originalImageMat) originalImageMat.delete();
                        originalImageMat = cv.imread(imgElement);
                        isImageLoaded = true;
                        history.forEach(mat => mat.delete());
                        history = []; historyIndex = -1;
                        applyAllChanges();
                        pushHistory(); // Save initial state
                    }
                }
                reader.readAsDataURL(originalFile);
            }
        });

        allControls.forEach(control => {
            control.addEventListener('input', () => {
                requestAnimationFrame(applyAllChanges);
                debounce(pushHistory, 500); // Only save history after user pauses
            });
        });

        // --- High-Performance Stacking Render Engine ---
        function applyAllChanges() {
            if (!isImageLoaded) return;
            let src = originalImageMat.clone();
            const activeTool = modeInput.value;

            const scalePercent = parseInt(document.getElementById('scale_percent').value); if (scalePercent !== 100) { let dsize = new cv.Size(Math.round(src.cols * scalePercent / 100), Math.round(src.rows * scalePercent / 100)); cv.resize(src, src, dsize, 0, 0, cv.INTER_AREA); }
            const angle = parseInt(document.getElementById('rotation').value); if (angle !== 0) { let dsize = new cv.Size(src.cols, src.rows); let center = new cv.Point(src.cols / 2, src.rows / 2); let M = cv.getRotationMatrix2D(center, angle, 1); cv.warpAffine(src, src, M, dsize, cv.INTER_LINEAR, cv.BORDER_CONSTANT, new cv.Scalar()); M.delete(); }
            const flipCode = parseInt(document.getElementById('flip-select').value); if (flipCode !== 99) cv.flip(src, src, flipCode);
            const brightness = parseInt(document.getElementById('brightness').value); const contrast = parseFloat(document.getElementById('contrast').value); if (brightness !== 0 || contrast !== 0) { const alpha = 1 + (contrast / 100.0); cv.convertScaleAbs(src, src, alpha, brightness); }
            const hue = parseInt(document.getElementById('hue').value); const saturation = parseInt(document.getElementById('saturation').value);
            if (hue !== 0 || saturation !== 0) {
                let hsv = new cv.Mat(); cv.cvtColor(src, hsv, cv.COLOR_RGBA2RGB); cv.cvtColor(hsv, hsv, cv.COLOR_RGB2HSV);
                let hsvPlanes = new cv.MatVector(); cv.split(hsv, hsvPlanes);
                let H = hsvPlanes.get(0); let S = hsvPlanes.get(1);
                for (let i = 0; i < H.rows; i++) { for (let j = 0; j < H.cols; j++) { H.data[i * H.cols + j] = (H.data[i * H.cols + j] + hue + 180) % 180; } }
                let satMat = new cv.Mat(src.rows, src.cols, cv.CV_8U, new cv.Scalar(saturation)); cv.add(S, satMat, S); satMat.delete();
                cv.merge(hsvPlanes, hsv);
                cv.cvtColor(hsv, src, cv.COLOR_HSV2RGB); cv.cvtColor(src, src, cv.COLOR_RGB2RGBA);
                hsv.delete(); hsvPlanes.delete(); H.delete(); S.delete();
            }
            const gammaValue = parseFloat(document.getElementById('gamma').value); if (gammaValue !== 1.0) { const lookUpTable = new cv.Mat(1, 256, cv.CV_8U); for (let i = 0; i < 256; i++) { lookUpTable.data[i] = Math.pow(i / 255.0, gammaValue) * 255.0; } cv.LUT(src, lookUpTable, src); lookUpTable.delete(); }
            if (activeTool === 'contrast_stretching') { cv.normalize(src, src, 0, 255, cv.NORM_MINMAX); }
            let ksize_gauss = parseInt(document.getElementById('gaussian_blur').value); if (ksize_gauss > 1 && ksize_gauss % 2 !== 0) cv.GaussianBlur(src, src, new cv.Size(ksize_gauss, ksize_gauss), 0, 0, cv.BORDER_DEFAULT);
            let ksize_mean = parseInt(document.getElementById('mean_blur').value); if (ksize_mean > 1) cv.blur(src, src, new cv.Size(ksize_mean, ksize_mean));
            let ksize_median = parseInt(document.getElementById('median_blur').value); if (ksize_median > 1 && ksize_median % 2 !== 0) cv.medianBlur(src, src, ksize_median);
            if (activeTool === 'sharpen') { let kernel = cv.matFromArray(3, 3, cv.CV_32F, [-1, -1, -1, -1, 9, -1, -1, -1, -1]); cv.filter2D(src, src, cv.CV_8U, kernel); kernel.delete(); }
            if (activeTool === 'morphology') { const morphOp = document.getElementById('morph_op').value; const morphKsize = parseInt(document.getElementById('morph_kernel').value); if (morphKsize > 1) { let M = cv.Mat.ones(morphKsize, morphKsize, cv.CV_8U); if (morphOp === 'erosion') cv.erode(src, src, M); else if (morphOp === 'dilation') cv.dilate(src, src, M); else if (morphOp === 'opening') cv.morphologyEx(src, src, cv.MORPH_OPEN, M); else if (morphOp === 'closing') cv.morphologyEx(src, src, cv.MORPH_CLOSE, M); M.delete(); } }
            if (activeTool === 'histogram_equalization' || activeTool === 'threshold' || activeTool.includes('_edge')) { cv.cvtColor(src, src, cv.COLOR_RGBA2GRAY, 0); if (activeTool === 'histogram_equalization') { cv.equalizeHist(src, src); } else if (activeTool === 'threshold') { const value = parseInt(document.getElementById('threshold_value').value); cv.threshold(src, src, value, 255, cv.THRESH_BINARY); } else if (activeTool === 'sobel_edge') { cv.Sobel(src, src, cv.CV_8U, 1, 1); } else if (activeTool === 'laplacian_edge') { cv.Laplacian(src, src, cv.CV_8U, 1, 1, 0, cv.BORDER_DEFAULT); } else if (activeTool === 'prewitt_edge') { let kernelX = cv.matFromArray(3, 3, cv.CV_32F, [-1, 0, 1, -1, 0, 1, -1, 0, 1]); let kernelY = cv.matFromArray(3, 3, cv.CV_32F, [-1, -1, -1, 0, 0, 0, 1, 1, 1]); let dstX = new cv.Mat(); let dstY = new cv.Mat(); cv.filter2D(src, dstX, cv.CV_32F, kernelX); cv.filter2D(src, dstY, cv.CV_32F, kernelY); cv.convertScaleAbs(dstX, dstX); cv.convertScaleAbs(dstY, dstY); cv.addWeighted(dstX, 0.5, dstY, 0.5, 0, src); kernelX.delete(); kernelY.delete(); dstX.delete(); dstY.delete(); } }

            canvas.width = src.cols; canvas.height = src.rows;
            cv.imshow('main-canvas', src);
            src.delete();
        }

        // --- FINAL SAVE BUTTON LOGIC (USING FETCH) ---
        processBtn.addEventListener('click', () => {
            if (!isImageLoaded) { alert('Please upload an image first!'); return; }
            processBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            processBtn.disabled = true;

            canvas.toBlob((blob) => {
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `ImagoLab_${originalFile.name}`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(link.href);

                const formData = new FormData();
                formData.append('image', new File([blob], originalFile.name, { type: originalFile.type }));
                formData.append('mode', 'resize');
                formData.append('scale_percent', '100');
                formData.append('brightness', '0');
                formData.append('contrast', '0');
                formData.append('angle', '0');
                formData.append('flip', '99');
                formData.append('saturation', '0');
                formData.append('blur', '1');
                formData.append('mean_blur', '1');
                formData.append('median_blur', '1');
                formData.append('morph_op', 'erosion');
                formData.append('morph_kernel', '5');
                formData.append('gamma', '1.0');
                formData.append('threshold_value', '128');
                formData.append('adaptive_threshold', 'false');
                formData.append('hue', '0');
                formData.append('fourier_radius', '30');

                fetch(document.getElementById('image-form').action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Server validation failed.');
                    });
                })
                .then(data => {
                    console.log('Success:', data);
                    processBtn.innerHTML = '<i class="fas fa-check-circle"></i> Saved!';
                    setTimeout(() => {
                        processBtn.innerHTML = '<i class="fas fa-save"></i> Finalize & Save';
                        processBtn.disabled = false;
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during save: ' + error.message);
                    processBtn.innerHTML = '<i class="fas fa-save"></i> Finalize & Save';
                    processBtn.disabled = false;
                });
            }, originalFile.type);
        });

        const toolCategories = document.querySelectorAll('.tool-category');
        const toolSubmenus = document.querySelectorAll('.tool-submenu');
        const toolSubmenuItems = document.querySelectorAll('.tool-submenu-item');
        const parameterSections = document.querySelectorAll('.parameter-section');
        toolCategories.forEach(category => {
            category.addEventListener('click', () => {
                toolCategories.forEach(c => c.classList.remove('active')); category.classList.add('active'); const tool = category.getAttribute('data-tool');
                toolSubmenus.forEach(menu => menu.style.display = 'none'); document.getElementById(`${tool}-menu`).style.display = 'flex';
                document.querySelector(`#${tool}-menu .tool-submenu-item`).click();
            });
        });
        toolSubmenuItems.forEach(item => {
            item.addEventListener('click', () => {
                item.parentElement.querySelectorAll('.tool-submenu-item').forEach(i => i.classList.remove('active')); item.classList.add('active');
                const action = item.getAttribute('data-action'); modeInput.value = action;
                parameterSections.forEach(p => p.style.display = 'none');
                document.querySelectorAll(`[data-param-for="${action}"]`).forEach(p => p.style.display = 'block');
                applyAllChanges();
                pushHistory();
            });
        });

        document.querySelector('.tool-category.active').click();
        updateHistoryButtons();
    };
}
    </script>
</body>
</html>
