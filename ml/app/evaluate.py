
import tensorflow as tf
import numpy as np

from pathlib import Path
from sklearn.metrics import (
    confusion_matrix,
    classification_report
)

# =====================================
# CONFIGURATION
# =====================================

BASE_DIR = Path(__file__).resolve().parent.parent

TEST_DIR = BASE_DIR / "dataset_3class" / "test"
MODEL_PATH = BASE_DIR / "models" / "mobilenetv2_best.keras"

IMG_SIZE = (224, 224)
BATCH_SIZE = 32


# =====================================
# LOAD MODEL
# =====================================

print("Loading trained model...")

model = tf.keras.models.load_model(MODEL_PATH)

print("Model loaded successfully!")


# =====================================
# LOAD TEST DATASET
# =====================================

print("\nLoading test dataset...")

test_ds = tf.keras.utils.image_dataset_from_directory(
    TEST_DIR,
    image_size=IMG_SIZE,
    batch_size=BATCH_SIZE,
    label_mode="int",
    shuffle=False
)

class_names = test_ds.class_names

print("\nCategories:", class_names)


# =====================================
# MODEL EVALUATION
# =====================================

print("\nEvaluating model...")

test_loss, test_accuracy = model.evaluate(test_ds)

print(f"\nTest Loss: {test_loss:.4f}")
print(f"Test Accuracy: {test_accuracy * 100:.2f}%")


# =====================================
# PREDICTION
# =====================================

y_true = np.concatenate([
    labels.numpy()
    for images, labels in test_ds
])

predictions = model.predict(test_ds)

y_pred = np.argmax(predictions, axis=1)


# =====================================
# CONFUSION MATRIX
# =====================================

cm = confusion_matrix(
    y_true,
    y_pred,
    labels=range(len(class_names))
)

print("\nConfusion Matrix:")
print(cm)


# =====================================
# CLASSIFICATION REPORT
# =====================================

print("\nClassification Report:")

print(
    classification_report(
        y_true,
        y_pred,
        labels=range(len(class_names)),
        target_names=class_names,
        zero_division=0
    )
)


# =====================================
# SAVE RESULTS
# =====================================

RESULT_DIR = BASE_DIR / "results"
RESULT_DIR.mkdir(parents=True, exist_ok=True)

np.savetxt(
    RESULT_DIR / "confusion_matrix.csv",
    cm,
    delimiter=",",
    fmt="%d"
)

print("\nEvaluation completed!")
print("Confusion matrix saved to:", RESULT_DIR)
