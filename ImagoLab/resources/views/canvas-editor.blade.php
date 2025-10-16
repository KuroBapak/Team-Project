<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ImagoLab - Canvas Editor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/canvas-editor.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="canvas-mode-active">
    <canvas id="stars-canvas"></canvas>

    <div class="header">
        <div class="logo"><div class="logo-icon"><i class="fas fa-sparkles"></i></div><div class="logo-text">ImagoLab</div></div>
        <div class="nav-links">
            <a href="{{ route('selection') }}" class="nav-link">Editor Selection</a>
            <form method="POST" action="{{ route('tool.select') }}" style="display:inline;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="tool_type" value="basic">
                <button type="submit" class="nav-link-button">Basic Tools</button>
            </form>

            {{-- ✅ START: FIX FOR GUEST USERS --}}
            @auth
                <div class="profile-dropdown">
                    <button class="profile-toggle" id="profileToggle">{{ Auth::user()->name }} <i class="fas fa-chevron-down" style="font-size:12px;"></i></button>
                    <div class="dropdown-menu" id="profileMenu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fas fa-user"></i> Profile</a>
                        <a href="{{ route('history.index') }}" class="dropdown-item"><i class="fas fa-history"></i> History</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();this.closest('form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <span class="nav-link" style="cursor: default; color: #a0aec0;">Guest</span>
            @endauth
            {{-- ✅ END: FIX FOR GUEST USERS --}}

        </div>
    </div>

    <div class="canvas-editor-container">
        {{-- The rest of your file remains exactly the same --}}
        <div class="canvas-toolbar">
            <div class="tool-section">
                <div class="tool-section-header">File</div>
                <label for="image-upload" class="btn btn-primary full-width"><i class="fas fa-upload"></i> Upload Image</label>
                <input type="file" id="image-upload" style="display:none;">
                <button id="save-btn" class="btn btn-success full-width"><i class="fas fa-save"></i> Save & Download</button>
            </div>

            <div class="tool-section">
                <div class="tool-section-header">Tools</div>
                <button class="tool-btn active" data-tool="select"><i class="fas fa-mouse-pointer"></i> Select</button>
                <button class="tool-btn" data-tool="crop"><i class="fas fa-crop-alt"></i> Crop</button>
                <button class="tool-btn" data-tool="draw"><i class="fas fa-pencil-alt"></i> Draw</button>
                <button class="tool-btn" data-tool="shape"><i class="fas fa-shapes"></i> Shape</button>
                <button class="tool-btn" data-tool="text"><i class="fas fa-font"></i> Text</button>
            </div>

            <div id="tool-options">
                <div class="options-panel" data-tool="crop" style="display:block;">
                    <button id="apply-crop-btn" class="btn btn-primary">Apply Crop</button>
                    <button id="cancel-crop-btn" class="btn btn-outline">Cancel</button>
                </div>
                <div class="options-panel" data-tool="draw" style="display:none;">
                    <label for="draw-color">Color</label>
                    <input type="color" id="draw-color" value="#000000">
                    <label for="draw-width">Width</label>
                    <input type="range" id="draw-width" min="1" max="50" value="5">
                </div>
                <div class="options-panel" data-tool="shape" style="display:none;">
                    <button id="add-rect-btn" class="btn btn-outline">Rectangle</button>
                    <button id="add-circle-btn" class="btn btn-outline">Circle</button>
                </div>
                <div class="options-panel" data-tool="text" style="display:none;">
                    <button id="add-text-btn" class="btn btn-outline">Add Text</button>
                </div>
            </div>

            <div class="tool-section">
                <div class="tool-section-header">Properties</div>
                <div class="property-group">
                    <label for="object-color">Fill Color</label>
                    <input type="color" id="object-color" value="#64ffda">
                </div>
            </div>

            <div class="tool-section">
                <div class="tool-section-header">Actions</div>
                <div class="action-buttons">
                    <button id="undo-btn" class="btn btn-outline action-btn"><i class="fas fa-undo"></i> Undo</button>
                    <button id="redo-btn" class="btn btn-outline action-btn"><i class="fas fa-redo"></i> Redo</button>
                </div>
                <button id="reset-btn" class="btn btn-outline danger full-width"><i class="fas fa-times-circle"></i> Reset All</button>
                <button id="delete-btn" class="btn btn-danger full-width"><i class="fas fa-trash"></i> Delete Selected</button>
            </div>
        </div>

        <div class="canvas-workspace"><canvas id="canvas"></canvas></div>
    </div>

<script>
'use strict';
// ... (The rest of your script remains unchanged)
document.addEventListener('DOMContentLoaded', () => {
    // --- background stars (unchanged) ---
    const starsCanvas = document.getElementById('stars-canvas');
    if (starsCanvas) {
        const ctx = starsCanvas.getContext('2d');
        function resizeCanvasStars() { starsCanvas.width = window.innerWidth; starsCanvas.height = window.innerHeight; }
        window.addEventListener('resize', resizeCanvasStars);
        resizeCanvasStars();
        const stars = Array.from({ length: 110 }, () => ({ x: Math.random() * starsCanvas.width, y: Math.random() * starsCanvas.height, size: Math.random() * 1.7, speedX: (Math.random() - 0.5) * 0.14, speedY: (Math.random() - 0.5) * 0.14, baseOpacity: Math.random() * 0.55 + 0.2 }));
        function animateStars() {
            ctx.clearRect(0, 0, starsCanvas.width, starsCanvas.height);
            stars.forEach(star => {
                star.x += star.speedX; star.y += star.speedY;
                if (star.x < 0 || star.x > starsCanvas.width) star.speedX *= -1;
                if (star.y < 0 || star.y > starsCanvas.height) star.speedY *= -1;
                const opacity = star.baseOpacity + Math.sin(Date.now() * 0.001 + star.x * 0.01) * 0.18;
                ctx.fillStyle = `rgba(255,255,255,${Math.max(0.05, opacity)})`;
                ctx.beginPath(); ctx.arc(star.x, star.y, star.size, 0, Math.PI * 2); ctx.fill();
            });
            requestAnimationFrame(animateStars);
        }
        animateStars();
    }

    // profile dropdown
    const profileToggle = document.getElementById('profileToggle');
    if (profileToggle) {
        const profileMenu = document.getElementById('profileMenu');
        profileToggle.addEventListener('click', (e) => { e.stopPropagation(); profileMenu.classList.toggle('show'); });
        document.addEventListener('click', (e) => { if (!profileToggle.contains(e.target) && !profileMenu.contains(e.target)) profileMenu.classList.remove('show'); });
    }

    // initialize canvas editor after short delay
    if (document.querySelector('.canvas-workspace') && typeof fabric !== 'undefined') {
        setTimeout(initializeCanvasEditor, 100);
    }
});

function initializeCanvasEditor() {
    console.log("🎨 Initializing Canvas Editor (fixed undo/reset history)...");

    const canvas = new fabric.Canvas('canvas', { preserveObjectStacking: true });
    const workspace = document.querySelector('.canvas-workspace');

    // DOM elements
    const imageUpload = document.getElementById('image-upload');
    const toolBtns = document.querySelectorAll('.tool-btn');
    const optionsPanels = document.querySelectorAll('.options-panel');
    const drawColorInput = document.getElementById('draw-color');
    const drawWidthInput = document.getElementById('draw-width');
    const objectColorInput = document.getElementById('object-color');
    const undoBtn = document.getElementById('undo-btn');
    const redoBtn = document.getElementById('redo-btn');
    const resetBtn = document.getElementById('reset-btn');

    // state
    let history = [];
    let historyIndex = -1;
    let historyLock = false;
    let cropRect = null;

    // resize canvas to workspace
    function resizeCanvas() {
        const parent = workspace.getBoundingClientRect();
        const w = Math.max(400, Math.floor(parent.width - 20));
        const h = Math.max(300, Math.floor(parent.height - 20));
        canvas.setWidth(w);
        canvas.setHeight(h);
        canvas.renderAll();
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // initialize drawing brush
    canvas.freeDrawingBrush = new fabric.PencilBrush(canvas);
    canvas.freeDrawingBrush.width = parseInt(drawWidthInput?.value || 5, 10);
    canvas.freeDrawingBrush.color = drawColorInput?.value || '#000000';

    // helper: normalize color strings to hex when possible
    function colorStringToHex(color) {
        if (!color) return '#000000';
        color = color.toString().trim();
        if (color[0] === '#') {
            if (color.length === 4) {
                return '#' + color[1]+color[1] + color[2]+color[2] + color[3]+color[3];
            }
            return color;
        }
        const m = color.match(/rgba?\s*\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})/i);
        if (m) {
            const toHex = (n) => ('0' + parseInt(n,10).toString(16)).slice(-2);
            return '#' + toHex(m[1]) + toHex(m[2]) + toHex(m[3]);
        }
        return color;
    }

    // -------- history helpers (no blank initial snapshot) --------
    function isSnapshotEmpty(snapshot) {
        if (!snapshot) return true;
        const hasBg = snapshot.backgroundImage && Object.keys(snapshot.backgroundImage).length > 0;
        const hasObjects = Array.isArray(snapshot.objects) && snapshot.objects.length > 0;
        return !(hasBg || hasObjects);
    }
    function findFirstNonEmptyIndex() {
        for (let i = 0; i < history.length; i++) {
            if (!isSnapshotEmpty(history[i])) return i;
        }
        return history.length > 0 ? 0 : -1;
    }

    function pushHistory() {
        if (historyLock) return;
        // drop forward history if we've undone
        if (historyIndex < history.length - 1) history.splice(historyIndex + 1);

        let snapshot;
        try {
            snapshot = canvas.toJSON(['isCropRect']);
        } catch (err) {
            console.warn('pushHistory: toJSON failed', err);
            return;
        }

        // avoid pushing initial blank snapshot
        if (history.length === 0 && isSnapshotEmpty(snapshot)) {
            return;
        }

        history.push(snapshot);
        historyIndex = history.length - 1;

        // cap
        if (history.length > 40) {
            history.shift();
            historyIndex = history.length - 1;
        }
        updateHistoryButtons();
    }

    function updateHistoryButtons() {
        const firstIndex = findFirstNonEmptyIndex();
        // if no valid snapshots, disable all
        if (firstIndex === -1) {
            undoBtn.disabled = true;
            redoBtn.disabled = true;
            resetBtn.disabled = true;
            return;
        }
        undoBtn.disabled = historyIndex <= firstIndex;
        redoBtn.disabled = historyIndex >= history.length - 1 || historyIndex < 0;
        resetBtn.disabled = history.length === 0 || historyIndex <= firstIndex;
    }

    function undo() {
        const minIndex = findFirstNonEmptyIndex();
        if (minIndex === -1) return;
        if (historyIndex > minIndex) {
            historyLock = true;
            historyIndex--;
            canvas.loadFromJSON(history[historyIndex], () => {
                canvas.renderAll();
                cropRect = canvas.getObjects().find(o => o.isCropRect) || null;
                historyLock = false;
                updateHistoryButtons();
            });
        }
    }

    function redo() {
        if (historyIndex < history.length - 1) {
            historyLock = true;
            historyIndex++;
            canvas.loadFromJSON(history[historyIndex], () => {
                canvas.renderAll();
                cropRect = canvas.getObjects().find(o => o.isCropRect) || null;
                historyLock = false;
                updateHistoryButtons();
            });
        }
    }

    function reset() {
        const firstIndex = findFirstNonEmptyIndex();
        if (firstIndex === -1) {
            // nothing to reset to
            canvas.clear();
            history = [];
            historyIndex = -1;
            updateHistoryButtons();
            return;
        }
        historyLock = true;
        historyIndex = firstIndex;
        canvas.loadFromJSON(history[firstIndex], () => {
            canvas.renderAll();
            // keep only up to the new index (so reset becomes that first non-empty point)
            history.splice(firstIndex + 1);
            historyIndex = history.length - 1;
            historyLock = false;
            updateHistoryButtons();
        });
    }

    // wire history buttons
    undoBtn.addEventListener('click', undo);
    redoBtn.addEventListener('click', redo);
    resetBtn.addEventListener('click', reset);

    // -------- image upload (pushHistory after background is set) --------
    imageUpload.addEventListener('change', (e) => {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = (event) => {
                fabric.Image.fromURL(event.target.result, (img) => {
                    // Fit the image within canvas
                    const scale = Math.min(canvas.width / img.width, canvas.height / img.height);
                    img.set({ scaleX: scale, scaleY: scale, top: 0, left: 0, selectable: false });
                    // clear non-crop objects (if any) but preserve cropRect if exists
                    const objs = canvas.getObjects().slice();
                    objs.forEach(o => {
                        if (!o.isCropRect) canvas.remove(o);
                    });
                    canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                    // push snapshot with image
                    pushHistory();
                }, { crossOrigin: 'anonymous' });
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // history triggers from canvas events
    canvas.on('object:added', (e) => {
        if (historyLock) return;
        // ignore auto-creation of cropRect in some cases
        if (e.target && e.target.isCropRect) return;
        pushHistory();
    });
    canvas.on('object:modified', () => { if (!historyLock) pushHistory(); });
    canvas.on('path:created', () => { if (!historyLock) pushHistory(); });

    // -------- tool cleanup and selection --------
    function cleanupTools() {
        if (cropRect) { try { canvas.remove(cropRect); } catch (_) {} cropRect = null; }
        canvas.isDrawingMode = false;
        canvas.selection = true;
        canvas.getObjects().forEach(obj => obj.set({ selectable: true }));
        canvas.discardActiveObject();
        canvas.renderAll();
    }

    // tool buttons wiring
    toolBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            cleanupTools();
            toolBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const currentTool = btn.dataset.tool;

            if (currentTool === 'draw') {
                canvas.isDrawingMode = true;
                canvas.selection = false;
                canvas.getObjects().forEach(o => o.set({ selectable: false }));
            } else if (currentTool === 'crop') {
                cropRect = new fabric.Rect({
                    left: canvas.width * 0.15,
                    top: canvas.height * 0.15,
                    width: canvas.width * 0.7,
                    height: canvas.height * 0.6,
                    fill: 'rgba(0,0,0,0.25)',
                    stroke: '#64ffda',
                    strokeWidth: 2,
                    cornerStyle: 'circle',
                    hasRotatingPoint: false,
                    selectable: true,
                    isCropRect: true
                });
                canvas.add(cropRect);
                canvas.setActiveObject(cropRect);
                canvas.renderAll();
            } else if (currentTool === 'select') {
                canvas.selection = true;
                canvas.getObjects().forEach(o => o.set({ selectable: true }));
            }

            // show/hide options panels
            optionsPanels.forEach(p => { p.style.display = p.dataset.tool === currentTool ? 'block' : 'none'; });
        });
    });

    // -------- drawing controls --------
    if (drawColorInput) {
        drawColorInput.addEventListener('input', () => {
            if (!canvas.freeDrawingBrush) canvas.freeDrawingBrush = new fabric.PencilBrush(canvas);
            canvas.freeDrawingBrush.color = drawColorInput.value;
        });
    }
    if (drawWidthInput) {
        drawWidthInput.addEventListener('input', () => {
            if (!canvas.freeDrawingBrush) canvas.freeDrawingBrush = new fabric.PencilBrush(canvas);
            canvas.freeDrawingBrush.width = parseInt(drawWidthInput.value, 10) || 1;
        });
    }

    // -------- crop apply/cancel --------
    document.getElementById('apply-crop-btn').addEventListener('click', () => {
        if (!cropRect) return;
        const left = cropRect.left;
        const top = cropRect.top;
        const width = cropRect.getScaledWidth();
        const height = cropRect.getScaledHeight();

        const dataURL = canvas.toDataURL({
            left: left,
            top: top,
            width: width,
            height: height,
            format: 'png',
            multiplier: 1
        });

        fabric.Image.fromURL(dataURL, (img) => {
            cleanupTools();
            canvas.clear(); // remove everything
            const scale = Math.min(canvas.width / img.width, canvas.height / img.height);
            img.set({ scaleX: scale, scaleY: scale, left: 0, top: 0, selectable: false });
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
            pushHistory(); // snapshot after crop
        }, { crossOrigin: 'anonymous' });
    });

    document.getElementById('cancel-crop-btn').addEventListener('click', () => {
        cleanupTools();
        const selectBtn = document.querySelector('.tool-btn[data-tool="select"]');
        if (selectBtn) selectBtn.click();
    });

    // -------- add shapes and text --------
    document.getElementById('add-rect-btn').addEventListener('click', () => {
        const rect = new fabric.Rect({ left: 100, top: 100, fill: objectColorInput.value || '#64ffda', width: 120, height: 90, opacity: 1 });
        rect.set({ cornerStyle: 'circle' });
        canvas.add(rect);
        canvas.setActiveObject(rect);
        pushHistory();
    });

    document.getElementById('add-circle-btn').addEventListener('click', () => {
        const circle = new fabric.Circle({ left: 150, top: 150, radius: 50, fill: objectColorInput.value || '#64ffda', opacity: 1 });
        circle.set({ cornerStyle: 'circle' });
        canvas.add(circle);
        canvas.setActiveObject(circle);
        pushHistory();
    });

    document.getElementById('add-text-btn').addEventListener('click', () => {
        const text = new fabric.IText('Type here', { left: 200, top: 200, fill: objectColorInput.value || '#000000', fontSize: 28 });
        canvas.add(text);
        canvas.setActiveObject(text);
        pushHistory();
    });

    // delete selected objects
    document.getElementById('delete-btn').addEventListener('click', () => {
        const active = canvas.getActiveObjects();
        if (active.length) {
            active.forEach(o => canvas.remove(o));
            canvas.discardActiveObject();
            canvas.requestRenderAll();
            pushHistory();
        }
    });

    // -------------------- save/export image (fixed) --------------------
    document.getElementById("save-btn").addEventListener("click", function () {
        // 1️⃣ Get canvas data
        const canvasData = canvas.toDataURL("image/png");

        // 2️⃣ Auto download first
        const link = document.createElement("a");
        link.href = canvasData;
        link.download = `ImagoLab_Canvas_${Date.now()}.png`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // 3️⃣ Send to backend to save in history
        fetch("/process-image", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify({
                canvas_data: canvasData,
                mode: "canvas" // backend can check this
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log("✅ Canvas auto-downloaded and saved to history:", data.processedUrl);
            } else {
                console.error("❌ Failed to save canvas:", data);
            }
        })
        .catch(err => console.error("⚠️ Error saving canvas:", err));
    });

    // -------- object color property (fill) --------
    objectColorInput.addEventListener('input', () => {
        const newColor = objectColorInput.value;
        const active = canvas.getActiveObjects();
        if (active.length) {
            active.forEach(obj => {
                if (obj.type === 'image') return;
                obj.set('fill', newColor);
            });
            canvas.requestRenderAll();
        }
    });
    objectColorInput.addEventListener('change', () => { pushHistory(); });

    // keep color input in-sync with active selection
    function updateColorInputFromSelection() {
        const activeObj = canvas.getActiveObject();
        if (!activeObj) return;
        let target = activeObj;
        if (activeObj.type === 'activeSelection' && activeObj.getObjects().length) {
            target = activeObj.getObjects()[0];
        }
        const fill = target && target.fill ? colorStringToHex(target.fill) : null;
        if (fill) objectColorInput.value = fill;
    }
    canvas.on('selection:created', updateColorInputFromSelection);
    canvas.on('selection:updated', updateColorInputFromSelection);

    // initial state: do NOT push a blank snapshot here.
    // snapshots will be pushed when user uploads an image or adds/draws an object.

    // initialize default active tool (crop as in your markup)
    const activeToolBtn = document.querySelector('.tool-btn.active');
    if (activeToolBtn) activeToolBtn.click();
    updateHistoryButtons();
}
</script>

</body>
</html>
