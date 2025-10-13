<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImagoLab - Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/loginstyle.css') }}">

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

    <div class="login-container">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-sparkles"></i>
            </div>
            <div class="logo-text">ImagoLab</div>
        </div>

        <h2 class="login-title">Welcome Back</h2>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required autofocus :value="old('email')">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="current-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="divider">
            <span>OR</span>
        </div>

        <a href="{{ route('register') }}" class="btn btn-outline">
            <i class="fas fa-user-plus"></i> Create Account
        </a>

        <a href="{{ route('selection') }}" class="btn btn-guest">
            <i class="fas fa-user-clock"></i> Continue as Guest
        </a>

        <div class="links">
            <a href="{{ route('password.request') }}" class="link">Forgot Password?</a>
        </div>

        <div class="features">
            <h3 class="features-title">Powerful Image Processing</h3>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cut"></i>
                    </div>
                    <h4 class="feature-title">AI Background Removal</h4>
                    <p class="feature-desc">Remove backgrounds with precision</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-expand-alt"></i>
                    </div>
                    <h4 class="feature-title">Super Resolution</h4>
                    <p class="feature-desc">Enhance image quality up to 8x</p>
                </div>
            </div>
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
