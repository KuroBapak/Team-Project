<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImagoLab - Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboardstyle.css') }}">

    {{-- 1. ADDED: CSS to position the canvas as a background --}}
    <style>
        #stars-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* This places it behind all other content */
        }
    </style>
</head>
<body>
    {{-- 2. ADDED: The canvas element for the stars --}}
    <canvas id="stars-canvas"></canvas>

    <div class="header">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-sparkles"></i>
            </div>
            <div class="logo-text">ImagoLab</div>
        </div>

        @auth
            <div class="user-menu">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <a href="{{ route('history.index') }}" class="nav-link" style="margin-left: 20px;">History</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="nav-link">
                        Logout
                    </a>
                </form>
            </div>
        @endauth
    </div>

    <div class="container">
        <h1 class="dashboard-title">Choose Your Editing Mode</h1>
        <p class="dashboard-subtitle">Select the mode that best fits your image editing needs</p>

        <div class="mode-selector">

            <form method="POST" action="{{ route('tool.select') }}" class="mode-card-form">
                @csrf
                <input type="hidden" name="tool_type" value="basic">
                <button type="submit" class="mode-card-button">
                    <div class="mode-card">
                        <div class="mode-header">
                            <div class="mode-icon"><i class="fas fa-tools"></i></div>
                            <div class="mode-info">
                                <h3>Basic Image Tools</h3>
                                <p>Comprehensive set of fundamental image editing capabilities</p>
                            </div>
                        </div>
                        <div class="mode-features">
                            <ul class="feature-list">
                                <li><i class="fas fa-check-circle"></i> Convert to Grayscale</li>
                                <li><i class="fas fa-check-circle"></i> Color Adjustments</li>
                                <li><i class="fas fa-check-circle"></i> Crop, Resize, Rotate</li>
                            </ul>
                        </div>
                        <div class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i> Start Editing
                        </div>
                    </div>
                </button>
            </form>

<form method="POST" action="{{ route('tool.select') }}" class="mode-card-form">
    @csrf
    <input type="hidden" name="tool_type" value="canvas">
    <button type="submit" class="mode-card-button">
        <div class="mode-card">
            <div class="mode-header">
                <div class="mode-icon"><i class="fas fa-drafting-compass"></i></div>
                <div class="mode-info">
                    <h3>Interactive Canvas</h3>
                    <p>For cropping, drawing, and precise transformations.</p>
                </div>
            </div>
            <div class="mode-features">
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Precision Cropping</li>
                    <li><i class="fas fa-check-circle"></i> Drawing & Annotations</li>
                    <li><i class="fas fa-check-circle"></i> Shape & Text Tools</li>
                </ul>
            </div>
            <div class="btn btn-primary">
                <i class="fas fa-arrow-right"></i> Open Canvas
            </div>
        </div>
    </button>
</form>

            <form method="POST" action="{{ route('tool.select') }}" class="mode-card-form">
                @csrf
                <input type="hidden" name="tool_type" value="advanced">
                <button type="submit" class="mode-card-button">
                    <div class="mode-card">
                        <div class="mode-header">
                            <div class="mode-icon"><i class="fas fa-robot"></i></div>
                            <div class="mode-info">
                                <h3>Advanced AI Features</h3>
                                <p>Powerful AI-driven image enhancement and processing</p>
                            </div>
                        </div>
                        <div class="mode-features">
                            <ul class="feature-list">
                                <li><i class="fas fa-check-circle"></i> Background Removal with AI</li>
                                <li><i class="fas fa-check-circle"></i> Super Resolution AI</li>
                                <li><i class="fas fa-check-circle"></i> AI Denoising (Coming Soon)</li>
                            </ul>
                        </div>
                        <div class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i> Start Enhancing
                        </div>
                    </div>
                </button>
            </form>
        </div>

        <div class="footer">
            <p>© 2025 ImagoLab. All rights reserved.</p>
        </div>
    </div>

    {{-- 3. ADDED: The JavaScript for the animation --}}
    <script>
        'use strict';
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
            const numStars = 150;
            class Star {
                constructor() { this.x = Math.random() * canvas.width; this.y = Math.random() * canvas.height; this.size = Math.random() * 1.7; this.speedX = (Math.random() - 0.5) * 0.14; this.speedY = (Math.random() - 0.5) * 0.14; this.baseOpacity = Math.random() * 0.55 + 0.2; }
                update() { this.x += this.speedX; this.y += this.speedY; if (this.x < 0 || this.x > canvas.width) this.speedX = -this.speedX; if (this.y < 0 || this.y > canvas.height) this.speedY = -this.speedY; }
                draw() { const opacity = this.baseOpacity + Math.sin(Date.now() * 0.001 + this.x * 0.01) * 0.18; ctx.fillStyle = `rgba(255, 255, 255, ${Math.max(0.1, opacity)})`; ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill(); }
            }
            for (let i = 0; i < numStars; i++) { stars.push(new Star()); }
            function animateStars() { ctx.clearRect(0, 0, canvas.width, canvas.height); stars.forEach(star => { star.update(); star.draw(); }); requestAnimationFrame(animateStars); }
            animateStars();
        }
    </script>
</body>
</html>
