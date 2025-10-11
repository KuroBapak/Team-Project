<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Your Tool - ImagoLab</title>
    <style>/* Add some basic styling for the selection page */</style>
</head>
<body>
    <h1>Choose Your Editor</h1>
    <p>Select which set of tools you would like to use for this session.</p>

    <div style="margin-top: 20px;">
        <h2>Basic Tools</h2>
        <form action="{{ route('tool.select') }}" method="POST">
            @csrf
            <input type="hidden" name="tool_type" value="basic">
            <button type="submit">Select Basic Tools</button>
        </form>
        <p>Includes Grayscale and other simple filters.</p>
    </div>

    <hr style="margin: 20px 0;">

    <div style="margin-top: 20px;">
        <h2>Advanced AI</h2>
        <form action="{{ route('tool.select') }}" method="POST">
            @csrf
            <input type="hidden" name="tool_type" value="advanced">
            <button type="submit">Select Advanced AI</button>
        </form>
        <p>Includes AI Background Removal and Super Resolution.</p>
    </div>
</body>
</html>
