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

# --- ADDED SECURITY ---
app.add_middleware(
    CORSMiddleware,
    # When you go live on a real domain, change "*" to your actual domain.
    # For example: allow_origins=["http://www.imagolab.com", "https://www.imagolab.com"],
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)
# --- END OF SECURITY ---

OUTPUT_DIR = os.path.join(os.path.dirname(__file__), "..", "storage", "app", "public", "processed")

os.makedirs(OUTPUT_DIR, exist_ok=True)

@app.post("/process-image")
async def process_image(file: UploadFile = File(...), mode: str = Form(...)):
    try:
        contents = await file.read()
        input_image = Image.open(io.BytesIO(contents))

        unique_id = uuid.uuid4().hex[:8]
        timestamp = int(time.time())
        filename = f"{timestamp}_{unique_id}.png"
        output_path = os.path.join(OUTPUT_DIR, filename)

        output_image = None

        if mode == "removebg":
            output_image = remove(input_image)
        elif mode == "grayscale":
            output_image = input_image.convert("L")
        else:
            raise HTTPException(status_code=400, detail="Invalid processing mode specified.")

        output_image.save(output_path, "PNG")

        return {"url": f"processed/{filename}"}

    except Exception as e:
        traceback.print_exc()
        raise HTTPException(status_code=500, detail=f"An internal error occurred: {str(e)}")
