<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImagoLab Web UI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-magic"></i>
                <h2>Select Enhancement Feature</h2>
            </div>

            <div class="feature-selector">
                <div class="feature-option active" data-feature="background">
                    <i class="fas fa-cut"></i>
                    <h3>Background Removal</h3>
                    <p>AI segmentation for clean cutouts</p>
                </div>
                <div class="feature-option" data-feature="superres">
                    <i class="fas fa-expand-alt"></i>
                    <h3>Super Resolution</h3>
                    <p>AI upscaling for high quality</p>
                </div>
                <div class="feature-option" data-feature="playground">
                    <i class="fas fa-flask"></i>
                    <h3>Playground</h3>
                    <p>Test individual features</p>
                </div>
                <div class="feature-option" data-feature="showcase">
                    <i class="fas fa-th-large"></i>
                    <h3>Showcase</h3>
                    <p>Test all features together</p>
                </div>
            </div>

            <div class="mode-selector">
                <div class="mode-btn active">Local Processing</div>
                <div class="mode-btn">Server Processing</div>
            </div>

            <div class="info-box">
                <p><i class="fas fa-info-circle"></i> <strong>Local Mode:</strong> Your device processes the AI model. Requires training from provided code.</p>
                <p style="margin-top: 8px;"><i class="fas fa-server"></i> <strong>Server Mode:</strong> Requests processed on developer's laptop backend.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-sliders-h"></i>
                <h2>Configure Parameters</h2>
            </div>

            <div class="file-upload">
                <input type="file" id="image-upload" class="file-input" accept="image/*">
                <label for="image-upload" class="file-label">
                    <i class="fas fa-upload"></i> Upload Image
                </label>
                <div class="file-info">Supports JPG, PNG, WEBP (Max 10MB)</div>
            </div>

            <div class="image-preview">
                <i class="fas fa-image"></i> Image preview will appear here
            </div>

            <!-- Background Removal Parameters -->
            <div class="parameter-section feature-params active" id="background-params">
                <div class="parameter-group">
                    <label for="threshold">Segmentation Threshold</label>
                    <div class="slider-container">
                        <input type="range" min="0.1" max="0.9" step="0.1" value="0.5" class="slider" id="threshold">
                        <span class="slider-value">0.5</span>
                    </div>
                </div>
            </div>

            <!-- Super Resolution Parameters -->
            <div class="parameter-section feature-params" id="superres-params">
                <div class="parameter-group">
                    <label for="scale">Upscale Factor</label>
                    <div class="slider-container">
                        <input type="range" min="2" max="8" step="1" value="4" class="slider" id="scale">
                        <span class="slider-value">4x</span>
                    </div>
                </div>
            </div>

            <!-- Playground -->
            <div class="parameter-section feature-params" id="playground-params">
                <div class="parameter-group">
                    <label for="playground-param">Test Parameter</label>
                    <div class="slider-container">
                        <input type="range" min="0" max="100" step="1" value="50" class="slider" id="playground-param">
                        <span class="slider-value">50</span>
                    </div>
                </div>
            </div>

            <!-- Showcase -->
            <div class="parameter-section feature-params" id="showcase-params">
                <div class="parameter-group">
                    <label for="quality">Overall Quality</label>
                    <div class="slider-container">
                        <input type="range" min="1" max="10" step="1" value="7" class="slider" id="quality">
                        <span class="slider-value">7</span>
                    </div>
                </div>
            </div>

            <div class="status-indicator">
                <i class="fas fa-check-circle"></i>
                <span class="status-text">Ready for enhancement</span>
            </div>

            <div class="btn-group">
                <button class="btn btn-primary">
                    <i class="fas fa-bolt"></i> Enhance Image
                </button>
                <button class="btn btn-outline">
                    <i class="fas fa-sync"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <script>
        // === Sliders display values ===
        const sliders = document.querySelectorAll('.slider');
        sliders.forEach(slider => {
            const valueDisplay = slider.parentElement.querySelector('.slider-value');
            updateSliderValue(slider, valueDisplay);

            slider.addEventListener('input', () => {
                updateSliderValue(slider, valueDisplay);
            });
        });

        function updateSliderValue(slider, display) {
            let value = slider.value;
            if (slider.id === 'scale') {
                display.textContent = value + 'x';
            } else if (slider.id === 'denoise') {
                display.textContent = value + '%';
            } else {
                display.textContent = value;
            }
        }

        // === Feature option toggle ===
        const featureOptions = document.querySelectorAll('.feature-option');
        const featureParams = document.querySelectorAll('.feature-params');
        featureOptions.forEach(option => {
            option.addEventListener('click', () => {
                featureOptions.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
                const feature = option.getAttribute('data-feature');
                featureParams.forEach(params => {
                    params.classList.remove('active');
                    if (params.id === feature + '-params') {
                        params.classList.add('active');
                    }
                });
            });
        });

        // === Mode selector ===
        const modeBtns = document.querySelectorAll('.mode-btn');
        modeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modeBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });

        // === Preview original upload ===
        const fileInput = document.getElementById('image-upload');
        const imagePreview = document.querySelector('.image-preview');
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" style="max-width:100%; max-height:100%; border-radius:8px;">`;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // === Enhance Button Action ===
        document.querySelector('.btn-primary').addEventListener('click', async () => {
            if (!fileInput.files.length) {
                alert("Please upload an image first!");
                return;
            }
            const formData = new FormData();
            formData.append("image", fileInput.files[0]);
            formData.append("threshold", document.getElementById("threshold")?.value || 0.5);
            formData.append("scale", document.getElementById("scale")?.value || 4);

            try {
                const response = await fetch("/process-image", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error("Server error");
                const data = await response.json();

                // Replace preview with processed image
                imagePreview.innerHTML = `
                    <img src="${data.processed_url}" style="max-width:100%; max-height:100%; border-radius:8px;">
                `;
            } catch (err) {
                alert("Processing failed: " + err.message);
            }
        });
    </script>
</body>
</html>
