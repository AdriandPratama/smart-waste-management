
from fastapi import FastAPI, UploadFile, File, HTTPException
from pathlib import Path
from PIL import Image
import tensorflow as tf
import numpy as np
import io


# =====================================
# CONFIGURATION
# =====================================

BASE_DIR = Path(__file__).resolve().parent.parent

MODEL_PATH = BASE_DIR / "models" / "mobilenetv2_best.keras"

CLASS_NAMES = ["organic", "paper", "plastic"]

IMG_SIZE = (224, 224)


# =====================================
# LOAD MODEL
# =====================================

print("Loading MobileNetV2 model...")

model = tf.keras.models.load_model(MODEL_PATH)

print("Model loaded successfully!")


# =====================================
# FASTAPI
# =====================================

app = FastAPI(
    title="Smart Waste Management API",
    description="Waste classification using MobileNetV2",
    version="1.0.0"
)


@app.get("/")
def home():
    return {
        "message": "Smart Waste Management ML API",
        "status": "running",
        "categories": CLASS_NAMES
    }


# =====================================
# PREDICTION ENDPOINT
# =====================================

@app.post("/predict")
async def predict(file: UploadFile = File(...)):

    if not file.content_type or not file.content_type.startswith("image/"):
        raise HTTPException(
            status_code=400,
            detail="File harus berupa gambar"
        )

    try:
        contents = await file.read()

        image = Image.open(
            io.BytesIO(contents)
        ).convert("RGB")

        image = image.resize(IMG_SIZE)

        image_array = np.array(
            image,
            dtype=np.float32
        )

        image_array = np.expand_dims(
            image_array,
            axis=0
        )

        predictions = model.predict(
            image_array,
            verbose=0
        )

        predicted_index = int(
            np.argmax(predictions[0])
        )

        predicted_class = CLASS_NAMES[predicted_index]

        confidence = float(
            predictions[0][predicted_index]
        )

        return {
            "success": True,
            "filename": file.filename,
            "predicted_class": predicted_class,
            "confidence": round(confidence * 100, 2),
            "all_predictions": {
                CLASS_NAMES[i]: round(
                    float(predictions[0][i]) * 100, 2
                )
                for i in range(len(CLASS_NAMES))
            }
        }

    except Exception as error:
        raise HTTPException(
            status_code=400,
            detail=f"Gagal memproses gambar: {str(error)}"
        )
