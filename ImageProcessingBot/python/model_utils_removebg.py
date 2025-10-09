from rembg import remove
from PIL import Image
import io

def remove_bg(image_bytes: bytes) -> bytes:
    """
    Remove background from image using rembg (ONNX + GPU).
    """
    input_image = Image.open(io.BytesIO(image_bytes)).convert("RGBA")
    output_image = remove(input_image, only_mask=False, post_process=True)
    buf = io.BytesIO()
    output_image.save(buf, format="PNG")
    return buf.getvalue()
