<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImagoLab - Profile Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/profilestyle.css') }}">

    {{-- Self-contained styles for layout and dropdown visibility to prevent errors --}}
    <style>
        #stars-canvas {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
        }
        .header {
            position: fixed; top: 0; left: 0; width: 100%; display: flex;
            justify-content: space-between; align-items: center; padding: 0 40px;
            height: 80px; background-color: #0a192f; border-bottom: 1px solid #233554;
            z-index: 1000; box-sizing: border-box;
        }
        .logo {
            display: flex; align-items: center; gap: 10px; font-size: 1.5rem;
            font-weight: 700; color: #fff;
        }
        .logo-icon { color: #64ffda; }
        .nav-links { display: flex; align-items: center; gap: 25px; }
        .nav-link { color: #ccd6f6; text-decoration: none; font-size: 1rem; transition: color 0.2s ease; }
        .nav-link:hover { color: #64ffda; }
        .profile-dropdown { position: relative; display: inline-block; }
        .profile-toggle {
            background: none; border: none; color: #ccd6f6; font-family: inherit;
            font-size: 1rem; cursor: pointer; display: flex; align-items: center; gap: 8px;
        }
        .profile-toggle:hover { color: #64ffda; }
        .dropdown-menu {
            display: none; position: absolute; right: 0; top: 140%; background-color: #112240;
            min-width: 180px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1;
            border-radius: 8px; border: 1px solid #233554; padding: 8px 0; overflow: hidden;
        }
        .dropdown-menu.show { display: block; } /* This makes the dropdown visible */
        .dropdown-menu a {
            color: #ccd6f6; padding: 10px 15px; text-decoration: none; display: flex;
            align-items: center; gap: 10px; font-size: 0.95rem;
        }
        .dropdown-menu a:hover { background-color: #233554; color: #64ffda; }
        .dropdown-divider { height: 1px; margin: 8px 0; background-color: #233554; }
    </style>
</head>
<body>
    <canvas id="stars-canvas"></canvas>

    <div class="header">
        <div class="logo">
            <div class="logo-icon"><i class="fas fa-sparkles"></i></div>
            <div class="logo-text">ImagoLab</div>
        </div>
        <div class="nav-links">
            <a href="{{ route('editor') }}" class="nav-link">Editor</a>
            <div class="profile-dropdown">
                <button class="profile-toggle" id="profileToggle">
                    {{ Auth::user()->name }} <i class="fas fa-chevron-down" style="font-size:12px;"></i>
                </button>
                <div class="dropdown-menu" id="profileMenu">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fas fa-user"></i> Profile</a>
                    <a href="{{ route('history.index') }}" class="dropdown-item"><i class="fas fa-history"></i> History</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="dropdown-item"
                           onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title">Profile Settings</h1>

        <div class="profile-card">
            <h2 class="card-title">Profile Information</h2>
            <p class="card-subtitle">Update your account's profile information and email address.</p>
            <form method="post" action="{{ route('profile.update') }}" class="card-form">
                @csrf
                @method('patch')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
                    @error('name')<span class="error-message">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email')<span class="error-message">{{ $message }}</span>@enderror
                </div>
                <div class="form-actions">
                     @if (session('status') === 'profile-updated')
                        <p class="success-message">Saved.</p>
                    @endif
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

        <div class="profile-card">
            <h2 class="card-title">Update Password</h2>
            <p class="card-subtitle">Ensure your account is using a long, random password to stay secure.</p>
            <form method="post" action="{{ route('password.update') }}" class="card-form">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                     @error('current_password', 'updatePassword')<span class="error-message">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
                     @error('password', 'updatePassword')<span class="error-message">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                     @error('password_confirmation', 'updatePassword')<span class="error-message">{{ $message }}</span>@enderror
                </div>
                <div class="form-actions">
                     @if (session('status') === 'password-updated')
                        <p class="success-message">Saved.</p>
                    @endif
                    <button type="submit" class="btn btn-primary">Save Password</button>
                </div>
            </form>
        </div>

        <div class="profile-card danger-zone">
            <h2 class="card-title">Delete Account</h2>
            <p class="card-subtitle">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            <form method="post" action="{{ route('profile.destroy') }}" class="card-form" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                 @csrf
                 @method('delete')
                 <div class="form-actions">
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                 </div>
            </form>
        </div>
    </div>

    <script>
        'use strict';
        const canvas = document.getElementById('stars-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            function resizeCanvas() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();
            const stars = Array.from({ length: 150 }, () => ({ x: Math.random() * canvas.width, y: Math.random() * canvas.height, size: Math.random() * 1.7, speedX: (Math.random() - 0.5) * 0.14, speedY: (Math.random() - 0.5) * 0.14, baseOpacity: Math.random() * 0.55 + 0.2 }));
            function animateStars() { ctx.clearRect(0, 0, canvas.width, canvas.height); stars.forEach(star => { star.x += star.speedX; star.y += star.speedY; if (star.x < 0 || star.x > canvas.width) star.speedX *= -1; if (star.y < 0 || star.y > canvas.height) star.speedY *= -1; const opacity = star.baseOpacity + Math.sin(Date.now() * 0.001 + star.x * 0.01) * 0.18; ctx.fillStyle = `rgba(255, 255, 255, ${Math.max(0.1, opacity)})`; ctx.beginPath(); ctx.arc(star.x, star.y, star.size, 0, Math.PI * 2); ctx.fill(); }); requestAnimationFrame(animateStars); }
            animateStars();
        }

        @auth
            const profileToggle = document.getElementById('profileToggle');
            if (profileToggle) {
                const profileMenu = document.getElementById('profileMenu');
                profileToggle.addEventListener('click', (e) => { e.stopPropagation(); profileMenu.classList.toggle('show'); });
                document.addEventListener('click', (e) => { if (!profileToggle.contains(e.target) && !profileMenu.contains(e.target)) profileMenu.classList.remove('show'); });
            }
        @endauth
    </script>
</body>
</html>
