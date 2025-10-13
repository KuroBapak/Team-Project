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

# AI/ML Library Imports
from realesrgan import RealESRGANer
from basicsr.archs.rrdbnet_arch import RRDBNet
from basicsr.utils.download_util import load_file_from_url

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"], allow_credentials=True,
    allow_methods=["*"], allow_headers=["*"],
)

OUTPUT_DIR = os.path.join(os.path.dirname(__file__), "..", "storage", "app", "public", "processed")
os.makedirs(OUTPUT_DIR, exist_ok=True)

# --- GPU/Model Initialization (Unchanged) ---
# ... your existing model loading code ...
print("--- Starting ImagoLab AI Server ---")
if torch.cuda.is_available():
    print(f"✅ PyTorch has access to CUDA.")
    print(f"   GPU Device: {torch.cuda.get_device_name(0)}")
    GPU_ID = 0
else:
    print("⚠️ PyTorch CANNOT find a CUDA-enabled GPU. AI models will run on CPU (this will be very slow).")
    GPU_ID = None
print("---------------------------------")
model_name = 'RealESRGAN_x4plus'
model_url = f'https://github.com/xinntao/Real-ESRGAN/releases/download/v0.1.0/{model_name}.pth'
model_path = os.path.join('weights', f'{model_name}.pth')
if not os.path.isfile(model_path):
    ROOT_DIR = os.path.dirname(os.path.abspath(__file__))
    print(f"Downloading {model_name} model...")
    load_file_from_url(url=model_url, model_dir=os.path.join(ROOT_DIR, 'weights'), progress=True, file_name=f'{model_name}.pth')
model = RRDBNet(num_in_ch=3, num_out_ch=3, num_feat=64, num_block=23, num_grow_ch=32, scale=4)
upsampler = RealESRGANer(scale=4, model_path=model_path, model=model, tile=0, tile_pad=10, pre_pad=0, half=False, gpu_id=GPU_ID)

# --- HELPER FUNCTIONS ---

# --- Existing Functions (Unchanged) ---
def rotate_image(image, angle=0):
    if angle == 0: return image
    (h, w) = image.shape[:2]; center = (w // 2, h // 2)
    M = cv2.getRotationMatrix2D(center, angle, 1.0)
    return cv2.warpAffine(image, M, (w, h), flags=cv2.INTER_CUBIC, borderMode=cv2.BORDER_REPLICATE)

def resize_image(image, scale_percent=100):
    if scale_percent == 100: return image
    width = int(image.shape[1] * scale_percent / 100); height = int(image.shape[0] * scale_percent / 100)
    return cv2.resize(image, (width, height), interpolation=cv2.INTER_AREA)

def flip_image(image, flip_code=99):
    if flip_code > 1 or flip_code < -1: return image
    return cv2.flip(image, int(flip_code))

def apply_histogram_equalization(image):
    if len(image.shape) == 2: return cv2.equalizeHist(image)
    ycrcb = cv2.cvtColor(image, cv2.COLOR_BGR2YCrCb)
    ycrcb[:, :, 0] = cv2.equalizeHist(ycrcb[:, :, 0])
    return cv2.cvtColor(ycrcb, cv2.COLOR_YCrCb2BGR)

def apply_sharpen(image):
    kernel = np.array([[-1,-1,-1], [-1,9,-1], [-1,-1,-1]])
    return cv2.filter2D(image, -1, kernel)

def apply_sobel_edge(image):
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    sobelx = cv2.Sobel(gray, cv2.CV_64F, 1, 0, ksize=5)
    sobely = cv2.Sobel(gray, cv2.CV_64F, 0, 1, ksize=5)
    return cv2.magnitude(sobelx, sobely)

def apply_morphology(image, operation='erosion', kernel_size=5):
    kernel = np.ones((kernel_size, kernel_size), np.uint8)
    if operation == 'erosion': return cv2.erode(image, kernel, iterations=1)
    elif operation == 'dilation': return cv2.dilate(image, kernel, iterations=1)
    elif operation == 'opening': return cv2.morphologyEx(image, cv2.MORPH_OPEN, kernel)
    elif operation == 'closing': return cv2.morphologyEx(image, cv2.MORPH_CLOSE, kernel)
    return image

def apply_gamma_correction(image, gamma=1.0):
    invGamma = 1.0 / gamma
    table = np.array([((i / 255.0) ** invGamma) * 255 for i in np.arange(0, 256)]).astype("uint8")
    return cv2.LUT(image, table)

def apply_threshold(image, thresh_val=128, adaptive=False):
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    if adaptive: return cv2.adaptiveThreshold(gray, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, cv2.THRESH_BINARY, 11, 2)
    else: _, thresh = cv2.threshold(gray, thresh_val, 255, cv2.THRESH_BINARY); return thresh

# --- NEWLY IMPLEMENTED FUNCTIONS ---

def apply_brightness_contrast(img, brightness=0, contrast=0):
    alpha = 1.0 + contrast / 100.0
    beta = brightness
    return cv2.convertScaleAbs(img, alpha=alpha, beta=beta)

def apply_hsv(image, hue_shift=0, saturation_shift=0):
    hsv = cv2.cvtColor(image, cv2.COLOR_BGR2HSV)
    h, s, v = cv2.split(hsv)
    # Hue is 0-179 in OpenCV. We wrap around.
    h = (h.astype(int) + hue_shift) % 180
    h = h.astype(np.uint8)
    # Saturation is 0-255. We clip.
    s = cv2.add(s, saturation_shift)
    final_hsv = cv2.merge((h, s, v))
    return cv2.cvtColor(final_hsv, cv2.COLOR_HSV2BGR)

def apply_contrast_stretching(image):
    return cv2.normalize(image, None, 0, 255, cv2.NORM_MINMAX)

def apply_mean_blur(image, kernel_size=1):
    if kernel_size <= 1: return image
    return cv2.blur(image, (kernel_size, kernel_size))

def apply_median_blur(image, kernel_size=3):
    if kernel_size < 3: return image
    kernel_size = kernel_size if kernel_size % 2 != 0 else kernel_size + 1
    return cv2.medianBlur(image, kernel_size)

def apply_laplacian_edge(image):
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    return cv2.Laplacian(gray, cv2.CV_64F)

def apply_prewitt_edge(image):
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    kernel_x = np.array([[1,1,1],[0,0,0],[-1,-1,-1]])
    kernel_y = np.array([[-1,0,1],[-1,0,1],[-1,0,1]])
    prewitt_x = cv2.filter2D(gray, -1, kernel_x)
    prewitt_y = cv2.filter2D(gray, -1, kernel_y)
    return cv2.magnitude(prewitt_x.astype(np.float64), prewitt_y.astype(np.float64))

def apply_fourier_filter(image, radius=30, high_pass=False):
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    dft = cv2.dft(np.float32(gray), flags=cv2.DFT_COMPLEX_OUTPUT)
    dft_shift = np.fft.fftshift(dft)
    rows, cols = gray.shape
    crow, ccol = rows // 2 , cols // 2
    mask = np.zeros((rows, cols, 2), np.uint8)
    if high_pass: # High-pass filter (keeps high frequencies)
        cv2.circle(mask, (ccol, crow), radius, (1, 1), -1)
        mask = 1 - mask
    else: # Low-pass filter (keeps low frequencies)
        cv2.circle(mask, (ccol, crow), radius, (1, 1), -1)
    fshift = dft_shift * mask
    f_ishift = np.fft.ifftshift(fshift)
    img_back = cv2.idft(f_ishift)
    img_back = cv2.magnitude(img_back[:,:,0], img_back[:,:,1])
    return cv2.normalize(img_back, None, 0, 255, cv2.NORM_MINMAX)

@app.post("/process-image")
async def process_image(
    file: UploadFile = File(...), mode: str = Form(...),
    # Existing Params
    brightness: int = Form(0), contrast: int = Form(0),
    angle: int = Form(0), scale_percent: int = Form(100),
    flip: int = Form(99), blur: int = Form(1),
    morph_op: str = Form('erosion'), morph_kernel: int = Form(5),
    gamma: float = Form(1.0), threshold_value: int = Form(128),
    adaptive_threshold: bool = Form(False),
    # New Params
    hue: int = Form(0), saturation: int = Form(0),
    median_blur: int = Form(1), mean_blur: int = Form(1),
    fourier_radius: int = Form(30)
):
    try:
        contents = await file.read()
        nparr = np.frombuffer(contents, np.uint8)
        img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        output_image = None

        # ADVANCED AI TOOLS
        if mode == "removebg":
            input_pil = Image.open(io.BytesIO(contents))
            output_pil = remove(input_pil)
            output_path = os.path.join(OUTPUT_DIR, f"{uuid.uuid4().hex[:8]}.png")
            output_pil.save(output_path, "PNG"); return {"url": f"processed/{os.path.basename(output_path)}"}
        elif mode == "superres": output_image, _ = upsampler.enhance(img, outscale=4)

        # BASIC TOOLS
        elif mode == "grayscale": output_image = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        elif mode == "brightness_contrast": output_image = apply_brightness_contrast(img, brightness, contrast)
        elif mode == "rotate": output_image = rotate_image(img, angle)
        elif mode == "resize": output_image = resize_image(img, scale_percent)
        elif mode == "flip": output_image = flip_image(img, flip)
        elif mode == "gamma": output_image = apply_gamma_correction(img, gamma)
        elif mode == "threshold": output_image = apply_threshold(img, threshold_value, adaptive_threshold)
        elif mode == "histogram_equalization": output_image = apply_histogram_equalization(img)
        elif mode == "sharpen": output_image = apply_sharpen(img)
        elif mode == "sobel_edge": output_image = apply_sobel_edge(img)
        elif mode == "morphology": output_image = apply_morphology(img, morph_op, morph_kernel)

        # NEWLY ADDED MODES
        elif mode == "hue_saturation": output_image = apply_hsv(img, hue, saturation)
        elif mode == "contrast_stretching": output_image = apply_contrast_stretching(img)
        elif mode == "gaussian_blur": output_image = cv2.GaussianBlur(img, (blur if blur % 2 != 0 else blur + 1, blur if blur % 2 != 0 else blur + 1), 0)
        elif mode == "mean_blur": output_image = apply_mean_blur(img, mean_blur)
        elif mode == "median_blur": output_image = apply_median_blur(img, median_blur)
        elif mode == "laplacian_edge": output_image = apply_laplacian_edge(img)
        elif mode == "prewitt_edge": output_image = apply_prewitt_edge(img)
        elif mode == "low_pass_filter": output_image = apply_fourier_filter(img, fourier_radius, high_pass=False)
        elif mode == "high_pass_filter": output_image = apply_fourier_filter(img, fourier_radius, high_pass=True)

        else: raise HTTPException(status_code=400, detail="Invalid processing mode specified.")

        if output_image is not None:
            filename = f"{uuid.uuid4().hex[:8]}.png"
            output_path = os.path.join(OUTPUT_DIR, filename)
            cv2.imwrite(output_path, output_image.astype(np.uint8))
            return {"url": f"processed/{filename}"}
        else:
            raise HTTPException(status_code=500, detail="Image processing failed to produce an output.")

    except Exception as e:
        traceback.print_exc()
        raise HTTPException(status_code=500, detail=f"An internal error occurred: {str(e)}")
