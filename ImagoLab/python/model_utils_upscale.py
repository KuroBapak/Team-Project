import torch
from realesrgan import RealESRGAN
from PIL import Image
import io

# Load model once at startup
device = torch.device("cuda" if torch.cuda.is_available() else "cpu")
model = RealESRGAN(device, scale=4)   # 4x upscaling
model.load_weights('models/RealESRGAN_x4plus.pth', download=True)

def upscale_image(image_bytes: bytes) -> bytes:
    """
    Upscale image using Real-ESRGAN (PyTorch + CUDA).
    """
    input_image = Image.open(io.BytesIO(image_bytes)).convert("RGB")
    sr_image = model.predict(input_image)
    buf = io.BytesIO()
    sr_image.save(buf, format="PNG")
    return buf.getvalue()
