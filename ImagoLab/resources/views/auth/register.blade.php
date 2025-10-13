<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImagoLab - Register</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/registerstyle.css') }}">

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

    <div class="register-container">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-sparkles"></i>
            </div>
            <div class="logo-text">ImagoLab</div>
        </div>

        <h2 class="register-title">Create Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required autofocus value="{{ old('name') }}">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required value="{{ old('email') }}">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="terms">
                <input type="checkbox" id="terms" required>
                <label for="terms">I agree to the <a href="#" class="link">Terms of Service</a> and <a href="#" class="link">Privacy Policy</a></label>
            </div><br>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="divider">
            <span>OR</span>
        </div>

        <a href="{{ route('login') }}" class="btn btn-outline">
            <i class="fas fa-sign-in-alt"></i> Already have an account? Sign In
        </a>
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
