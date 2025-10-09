@if(isset($originalUrl) && isset($processedUrl))
        <div class="image-container">
            <div class="image-box">
                <h3>Original</h3>
                <img src="{{ $originalUrl }}" alt="Original Image" style="max-width: 100%; border-radius: 5px;">
            </div>
            <div class="image-box">
                <h3>Processed</h3>
                <img src="{{ $processedUrl }}" alt="Processed Image" style="max-width: 100%; border-radius: 5px;">

                <a href="{{ $processedUrl }}" download="processed_image.png" style="display: inline-block; margin-top: 15px; padding: 8px 16px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">
                    Download Result
                </a>
            </div>
        </div>
    @else
        @endif
