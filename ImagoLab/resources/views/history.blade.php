<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImagoLab - History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/historystyle.css') }}">
</head>
<body>
    <div class="header">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-sparkles"></i>
            </div>
            <div class="logo-text">ImagoLab</div>
        </div>
        <div class="nav-links">
            <a href="{{ route('selection') }}" class="nav-link">Selection</a>
            <a href="{{ route('profile.edit') }}" class="nav-link">Profile</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="nav-link">
                    Logout
                </a>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Editing History</h1>
            <div class="controls">
                <form action="{{ route('history.clearAll') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear ALL history? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline">
                        <i class="fas fa-trash-alt"></i> Clear All
                    </button>
                </form>
                </div>
        </div>

        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-value">{{ $totalEdits }}</div>
                <div class="stat-label">Total Edits</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $basicEdits }}</div>
                <div class="stat-label">Basic Tools</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $advancedEdits }}</div>
                <div class="stat-label">AI Enhancements</div>
            </div>
            </div>

        <form method="GET" action="{{ route('history.index') }}" class="filters">
            <div class="filter-group">
                <label class="filter-label">Tool Type</label>
                <select name="tool_type" class="filter-select" onchange="this.form.submit()">
                    <option value="all" @selected(request('tool_type', 'all') == 'all')>All Tools</option>
                    <option value="basic" @selected(request('tool_type') == 'basic')>Basic Tools</option>
                    <option value="advanced" @selected(request('tool_type') == 'advanced')>Advanced AI</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Date Range</label>
                <select name="date_range" class="filter-select" onchange="this.form.submit()">
                    <option value="all" @selected(request('date_range', 'all') == 'all')>All Time</option>
                    <option value="today" @selected(request('date_range') == 'today')>Today</option>
                    <option value="week" @selected(request('date_range') == 'week')>Last 7 Days</option>
                    <option value="month" @selected(request('date_range') == 'month')>Last 30 Days</option>
                    <option value="year" @selected(request('date_range') == 'year')>This Year</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Sort By</label>
                <select name="sort_by" class="filter-select" onchange="this.form.submit()">
                    <option value="newest" @selected(request('sort_by', 'newest') == 'newest')>Newest First</option>
                    <option value="oldest" @selected(request('sort_by') == 'oldest')>Oldest First</option>
                </select>
            </div>
        </form>

        <div class="history-grid">
            @forelse ($images as $image)
                <div class="history-card">
                    <img src="{{ Storage::url($image->processed_path) }}" alt="Processed Image" class="history-image">
                    <div class="history-content">
                        <div class="history-header">
                            <h3 class="history-title">{{ Str::of($image->original_path)->basename()->limit(25) }}</h3>
                            <div class="history-date">{{ $image->created_at->format('M d, Y, g:i A') }}</div>
                        </div>
                        <div class="history-tools">
                            <span class="tool-tag">{{ ucfirst($image->tool_type) }}</span>
                        </div>
                        <div class="history-actions">
                            <a href="{{ route('history.download', $image) }}" class="action-btn download">
                                <i class="fas fa-download"></i> Download
                            </a>
                            <form action="{{ route('history.destroy', $image) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-icon"><i class="fas fa-history"></i></div>
                    <h2 class="empty-title">No Editing History Found</h2>
                    <p class="empty-text">No results match your current filters. Try adjusting them or start editing!</p>
                    <a href="{{ route('selection') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Start Editing</a>
                </div>
            @endforelse
        </div>

        <div class="pagination">
            {{ $images->links() }}
        </div>
    </div>
</body>
</html>
