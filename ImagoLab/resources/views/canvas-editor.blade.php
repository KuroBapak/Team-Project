<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImagoLab - Canvas Editor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/canvas-editor.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
</head>
<body class="canvas-mode-active">
    <canvas id="stars-canvas"></canvas>

    <div class="header">
        <div class="logo"><div class="logo-icon"><i class="fas fa-sparkles"></i></div><div class="logo-text">ImagoLab</div></div>
        <div class="nav-links">
            <a href="{{ route('selection') }}" class="nav-link">Editor Selection</a>
            <form method="POST" action="{{ route('tool.select') }}" style="display: inline;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="tool_type" value="advanced"><button type="submit" class="nav-link-button">Advanced AI</button></form>
            <div class="profile-dropdown">
                <button class="profile-toggle" id="profileToggle">{{ Auth::user()->name }} <i class="fas fa-chevron-down" style="font-size:12px;"></i></button>
                <div class="dropdown-menu" id="profileMenu">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fas fa-user"></i> Profile</a>
                    <a href="{{ route('history.index') }}" class="dropdown-item"><i class="fas fa-history"></i> History</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">@csrf<a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();this.closest('form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></form>
                </div>
            </div>
        </div>
    </div>

    <div class="canvas-editor-container">
        <div class="canvas-toolbar">
            <div class="tool-section"><div class="tool-section-header">File</div><label for="image-upload" class="btn btn-primary full-width"><i class="fas fa-upload"></i> Upload Image</label><input type="file" id="image-upload" style="display:none;"><button id="save-btn" class="btn btn-success full-width"><i class="fas fa-save"></i> Save & Download</button></div>
            <div class="tool-section"><div class="tool-section-header">Tools</div><button class="tool-btn" data-tool="select"><i class="fas fa-mouse-pointer"></i> Select</button><button class="tool-btn active" data-tool="crop"><i class="fas fa-crop-alt"></i> Crop</button><button class="tool-btn" data-tool="draw"><i class="fas fa-pencil-alt"></i> Draw</button><button class="tool-btn" data-tool="shape"><i class="fas fa-shapes"></i> Shape</button><button class="tool-btn" data-tool="text"><i class="fas fa-font"></i> Text</button></div>
            <div id="tool-options">
                <div class="options-panel" data-tool="crop" style="display: block;"><button id="apply-crop-btn" class="btn btn-primary">Apply Crop</button><button id="cancel-crop-btn" class="btn btn-outline">Cancel</button></div>
                <div class="options-panel" data-tool="draw"><label for="draw-color">Color</label><input type="color" id="draw-color" value="#ff0000"><label for="draw-width">Width</label><input type="range" id="draw-width" min="1" max="50" value="5"></div>
                <div class="options-panel" data-tool="shape"><button id="add-rect-btn" class="btn btn-outline">Rectangle</button><button id="add-circle-btn" class="btn btn-outline">Circle</button></div>
                <div class="options-panel" data-tool="text"><button id="add-text-btn" class="btn btn-outline">Add Text</button></div>
            </div>
             <div class="tool-section"><div class="tool-section-header">Actions</div><button id="delete-btn" class="btn btn-danger full-width"><i class="fas fa-trash"></i> Delete Selected</button></div>
        </div>
        <div class="canvas-workspace"><canvas id="canvas"></canvas></div>
    </div>

    <script>
        'use strict';

document.addEventListener('DOMContentLoaded', () => {
    // --- SHARED LOGIC FOR THIS VIEW ---
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
    const profileToggle = document.getElementById('profileToggle');
    if (profileToggle) {
        const profileMenu = document.getElementById('profileMenu');
        profileToggle.addEventListener('click', (e) => { e.stopPropagation(); profileMenu.classList.toggle('show'); });
        document.addEventListener('click', (e) => { if (!profileToggle.contains(e.target) && !profileMenu.contains(e.target)) profileMenu.classList.remove('show'); });
    }

    // --- CANVAS EDITOR LOGIC ---
    if (document.querySelector('.canvas-workspace') && typeof fabric !== 'undefined') {
        // Use a small timeout to ensure the CSS has been fully applied by the browser
        setTimeout(initializeCanvasEditor, 100);
    }
});

function initializeCanvasEditor() {
    console.log("🎨 Initializing Canvas Editor with full features...");
    const canvas = new fabric.Canvas('canvas');
    const workspace = document.querySelector('.canvas-workspace');

    function resizeCanvas() {
        const parent = workspace.getBoundingClientRect();
        if (parent.width > 0 && parent.height > 0) {
            canvas.setWidth(parent.width - 20);
            canvas.setHeight(parent.height - 20);
            canvas.renderAll();
        }
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    const imageUpload = document.getElementById('image-upload');
    imageUpload.addEventListener('change', (e) => {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = (event) => {
                fabric.Image.fromURL(event.target.result, (img) => {
                    // Fit the image within the canvas dimensions
                    const scale = Math.min(canvas.width / img.width, canvas.height / img.height);
                    img.set({
                        scaleX: scale,
                        scaleY: scale,
                        top: 0,
                        left: 0
                    });
                    canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                });
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // --- State and Tool Management ---
    const toolBtns = document.querySelectorAll('.tool-btn');
    const optionsPanels = document.querySelectorAll('.options-panel');
    let currentTool = 'crop';
    let cropRect = null;

    function cleanupTools() {
        // Remove crop rectangle if it exists
        if (cropRect) {
            canvas.remove(cropRect);
            cropRect = null;
        }
        canvas.isDrawingMode = false;
        canvas.selection = true; // Allow object selection by default
        canvas.getObjects().forEach(obj => obj.set({ selectable: true }));
        canvas.renderAll();
    }

    toolBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            cleanupTools(); // Clean up previous tool's artifacts

            toolBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentTool = btn.dataset.tool;

            if (currentTool === 'draw') {
                canvas.isDrawingMode = true;
            } else if (currentTool === 'crop') {
                // Create a new crop rectangle
                cropRect = new fabric.Rect({
                    fill: 'rgba(0,0,0,0.5)',
                    stroke: '#64ffda',
                    strokeWidth: 2,
                    width: canvas.width * 0.5,
                    height: canvas.height * 0.5,
                    left: canvas.width * 0.25,
                    top: canvas.height * 0.25,
                    hasRotatingPoint: false,
                });
                canvas.add(cropRect);
                canvas.setActiveObject(cropRect);
                canvas.renderAll();
            }

            optionsPanels.forEach(p => { p.style.display = p.dataset.tool === currentTool ? 'block' : 'none'; });
        });
    });

    // --- Tool Implementations ---
    const drawColor = document.getElementById('draw-color');
    const drawWidth = document.getElementById('draw-width');
    drawColor.addEventListener('input', () => canvas.freeDrawingBrush.color = drawColor.value);
    drawWidth.addEventListener('input', () => canvas.freeDrawingBrush.width = parseInt(drawWidth.value, 10));

    document.getElementById('add-rect-btn').addEventListener('click', () => { const rect = new fabric.Rect({ left: 100, top: 100, fill: '#64ffda', width: 60, height: 70, opacity: 0.8 }); canvas.add(rect); });
    document.getElementById('add-circle-btn').addEventListener('click', () => { const circle = new fabric.Circle({ left: 150, top: 150, radius: 50, fill: '#ff79c6', opacity: 0.8 }); canvas.add(circle); });
    document.getElementById('add-text-btn').addEventListener('click', () => { const text = new fabric.IText('Type here', { left: 200, top: 200, fill: '#ccd6f6', fontSize: 24 }); canvas.add(text); });

    // Crop button logic
    document.getElementById('apply-crop-btn').addEventListener('click', () => {
        if (cropRect) {
            const bgImage = canvas.backgroundImage;
            if (!bgImage) return;

            // Calculate crop area relative to the background image
            const cropZone = {
                left: cropRect.left - bgImage.left,
                top: cropRect.top - bgImage.top,
                width: cropRect.getScaledWidth(),
                height: cropRect.getScaledHeight()
            };

            // Create a new cropped image from the data URL
            const croppedDataUrl = bgImage.toDataURL(cropZone);
            fabric.Image.fromURL(croppedDataUrl, (newImg) => {
                cleanupTools();
                canvas.clear();
                const scale = Math.min(canvas.width / newImg.width, canvas.height / newImg.height);
                newImg.set({ scaleX: scale, scaleY: scale });
                canvas.setBackgroundImage(newImg, canvas.renderAll.bind(canvas));
            });
        }
    });
    document.getElementById('cancel-crop-btn').addEventListener('click', cleanupTools);

    // --- General Actions ---
    document.getElementById('delete-btn').addEventListener('click', () => { canvas.getActiveObjects().forEach(obj => canvas.remove(obj)); canvas.discardActiveObject().renderAll(); });
    document.getElementById('save-btn').addEventListener('click', () => { const dataURL = canvas.toDataURL({ format: 'png' }); const link = document.createElement('a'); link.download = 'imagolab_canvas_edit.png'; link.href = dataURL; document.body.appendChild(link); link.click(); document.body.removeChild(link); });

    // Initialize with the default active tool
    document.querySelector('.tool-btn.active').click();
}
    </script>
</body>
</html>
