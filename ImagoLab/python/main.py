import io
import os
import uuid
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

# FIXED: This is the correct path to Laravel's standard public storage folder.
OUTPUT_DIR = os.path.join(os.path.dirname(__file__), "..", "storage", "app", "public", "processed")

# Make sure the directory exists
os.makedirs(OUTPUT_DIR, exist_ok=True)


@app.post("/process-image")
async def process_image(file: UploadFile = File(...), mode: str = Form(...)):
    try:
        contents = await file.read()
        input_image = Image.open(io.BytesIO(contents))

        # Generate a unique filename to prevent overwriting
        unique_id = uuid.uuid4().hex[:8]
        timestamp = int(time.time())
        filename = f"{timestamp}_{unique_id}.png"
        output_path = os.path.join(OUTPUT_DIR, filename)

        output_image = None

        # --- Conditional Logic Based on Mode ---
        if mode == "removebg":
            output_image = remove(input_image)
        elif mode == "grayscale":
            output_image = input_image.convert("L")
        else:
            raise HTTPException(status_code=400, detail="Invalid processing mode specified.")

        # Save the processed image
        output_image.save(output_path, "PNG")

        # FIXED: Return the CLEAN relative path. It must NOT have a "storage/" prefix.
        return {"url": f"processed/{filename}"}

    except Exception as e:
        traceback.print_exc() # Log the full error to your console for debugging
        raise HTTPException(status_code=500, detail=f"An internal error occurred: {str(e)}")
