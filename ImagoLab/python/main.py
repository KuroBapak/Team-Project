import io
import os
import uuid
import time
import traceback
from fastapi import FastAPI, UploadFile, File, Form, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from PIL import Image
from rembg import remove
import numpy as np
import cv2
import torch

from realesrgan import RealESRGANer
from basicsr.archs.rrdbnet_arch import RRDBNet
from basicsr.utils.download_util import load_file_from_url

app = FastAPI()

# ... (your middleware is fine) ...
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

OUTPUT_DIR = os.path.join(os.path.dirname(__file__), "..", "storage", "app", "public", "processed")
os.makedirs(OUTPUT_DIR, exist_ok=True)

# ... (GPU diagnostic is fine) ...
print("--- Starting ImagoLab AI Server ---")
if torch.cuda.is_available():
    print(f"✅ PyTorch has access to CUDA.")
    print(f"   GPU Device: {torch.cuda.get_device_name(0)}")
    GPU_ID = 0
else:
    print("⚠️ PyTorch CANNOT find a CUDA-enabled GPU. AI models will run on CPU (this will be very slow).")
    GPU_ID = None
print("---------------------------------")


# --- Automatic Model Downloader and Initializer ---
model_name = 'RealESRGAN_x4plus'
model_url = f'https://github.com/xinntao/Real-ESRGAN/releases/download/v0.1.0/{model_name}.pth'
model_path = os.path.join('weights', f'{model_name}.pth')

if not os.path.isfile(model_path):
    ROOT_DIR = os.path.dirname(os.path.abspath(__file__))
    load_file_from_url(url=model_url, model_dir=os.path.join(ROOT_DIR, 'weights'), progress=True, file_name=f'{model_name}.pth')

model = RRDBNet(num_in_ch=3, num_out_ch=3, num_feat=64, num_block=23, num_grow_ch=32, scale=4)
upsampler = RealESRGANer(
    scale=4,
    model_path=model_path,
    model=model,
    tile=0,
    tile_pad=10,
    pre_pad=0,
    half=False, # CHANGED: Disabled half-precision for better stability
    gpu_id=GPU_ID
)


@app.post("/process-image")
async def process_image(file: UploadFile = File(...), mode: str = Form(...)):
    try:
        # ... (rest of the code is the same until the superres block) ...
        contents = await file.read()

        unique_id = uuid.uuid4().hex[:8]
        timestamp = int(time.time())
        filename = f"{timestamp}_{unique_id}.png"
        output_path = os.path.join(OUTPUT_DIR, filename)

        if mode == "removebg":
            input_image = Image.open(io.BytesIO(contents))
            output_image = remove(input_image)
            output_image.save(output_path, "PNG")

        elif mode == "grayscale":
            input_image = Image.open(io.BytesIO(contents))
            output_image = input_image.convert("L")
            output_image.save(output_path, "PNG")

        elif mode == "superres":
            print(f"Starting Super Resolution for a new image...")
            start_time = time.time()

            nparr = np.frombuffer(contents, np.uint8)
            img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

            try:
                output_img, _ = upsampler.enhance(img, outscale=4)
            except RuntimeError as error:
                print('GPU Error:', error)
                print('Switching to CPU mode for this request as a fallback.')
                upsampler.gpu_id = None
                torch.cuda.empty_cache()
                output_img, _ = upsampler.enhance(img, outscale=4)
                upsampler.gpu_id = GPU_ID

            # ADDED: This is a safety measure to ensure the data is in the correct format before saving.
            output_img = output_img.astype(np.uint8)

            cv2.imwrite(output_path, output_img)

            end_time = time.time()
            print(f"✅ Super Resolution finished in {end_time - start_time:.2f} seconds.")

        else:
            raise HTTPException(status_code=400, detail="Invalid processing mode specified.")

        return {"url": f"processed/{filename}"}

    except Exception as e:
        traceback.print_exc()
        raise HTTPException(status_code=500, detail=f"An internal error occurred: {str(e)}")
