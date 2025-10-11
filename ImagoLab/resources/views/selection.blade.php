<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImagoLab - Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboardstyle.css') }}">
</head>
<body>
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
        @else
            <div class="nav-links">
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="nav-link">Register</a>
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
                                <li><i class="fas fa-check-circle"></i> Color Adjustments (Coming Soon)</li>
                                <li><i class="fas fa-check-circle"></i> Crop, Resize, Rotate (Coming Soon)</li>
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
                                <li><i class="fas fa-check-circle"></i> Super Resolution AI (Coming Soon)</li>
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
</body>
</html>
