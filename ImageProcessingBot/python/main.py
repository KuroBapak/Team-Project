import io
import os
import uuid # For generating unique filenames
import time
import traceback
from fastapi import FastAPI, UploadFile, File, Form, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from PIL import Image
from rembg import remove

app = FastAPI()

# Allow requests from your Laravel frontend
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # In production, change this to your Laravel app's domain
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Define the output directory relative to this script's location
# ../public/processed will correctly place files in Laravel's public folder
OUTPUT_DIR = os.path.join(os.path.dirname(__file__), "..", "public", "processed")

# Make sure the directory exists
os.makedirs(OUTPUT_DIR, exist_ok=True)


@app.post("/process-image")
async def process_image(file: UploadFile = File(...), mode: str = Form(...)):
    """
    A single endpoint to process an image based on the selected mode.
    - Reads an uploaded image file.
    - Reads a 'mode' from the form data ('removebg' or 'grayscale').
    - Applies the corresponding processing.
    - Saves the result with a unique filename in Laravel's public directory.
    - Returns a web-accessible URL.
    """
    try:
        contents = await file.read()
        input_image = Image.open(io.BytesIO(contents))

        # Generate a unique filename to prevent overwriting
        # e.g., 1678886400_a1b2c3d4.png
        unique_id = uuid.uuid4().hex[:8]
        timestamp = int(time.time())
        filename = f"{timestamp}_{unique_id}.png"
        output_path = os.path.join(OUTPUT_DIR, filename)

        output_image = None

        # --- Conditional Logic Based on Mode ---
        if mode == "removebg":
            # rembg expects an image with an alpha channel for transparency
            output_image = remove(input_image)
        elif mode == "grayscale":
            # .convert("L") creates a grayscale image
            output_image = input_image.convert("L")
        else:
            raise HTTPException(status_code=400, detail="Invalid processing mode specified.")

        # Save the processed image
        output_image.save(output_path, "PNG")

        # Return a URL relative to Laravel's public directory
        # e.g., "processed/1678886400_a1b2c3d4.png"
        return {"url": f"processed/{filename}"}

    except Exception as e:
        traceback.print_exc() # Log the full error to your console for debugging
        raise HTTPException(status_code=500, detail=f"An internal error occurred: {str(e)}")
