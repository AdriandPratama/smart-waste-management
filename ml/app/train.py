
import tensorflow as tf
from pathlib import Path

from model import create_model


# =====================================
# CONFIGURATION
# =====================================

BASE_DIR = Path(__file__).resolve().parent.parent

DATASET_DIR = BASE_DIR / "dataset_3class"
MODEL_DIR = BASE_DIR / "models"

IMG_SIZE = (224, 224)
BATCH_SIZE = 32
EPOCHS = 20
SEED = 42

MODEL_DIR.mkdir(parents=True, exist_ok=True)


# =====================================
# LOAD DATASET
# =====================================

print("Loading dataset...")

train_ds = tf.keras.utils.image_dataset_from_directory(
    DATASET_DIR / "train",
    image_size=IMG_SIZE,
    batch_size=BATCH_SIZE,
    label_mode="int",
    shuffle=True,
    seed=SEED
)

val_ds = tf.keras.utils.image_dataset_from_directory(
    DATASET_DIR / "val",
    image_size=IMG_SIZE,
    batch_size=BATCH_SIZE,
    label_mode="int",
    shuffle=False
)

class_names = train_ds.class_names

print("\nWaste Categories:", class_names)
print("Number of Classes:", len(class_names))

assert len(class_names) == 3, "Dataset harus memiliki 3 kategori!"

# Optimasi dataset
AUTOTUNE = tf.data.AUTOTUNE

train_ds = train_ds.prefetch(buffer_size=AUTOTUNE)
val_ds = val_ds.prefetch(buffer_size=AUTOTUNE)


# =====================================
# CLASS WEIGHT
# =====================================

class_counts = {}

for index, class_name in enumerate(class_names):
    folder = DATASET_DIR / "train" / class_name

    class_counts[index] = len([
        file for file in folder.iterdir()
        if file.is_file()
    ])

total_images = sum(class_counts.values())
num_classes = len(class_names)

class_weights = {
    index: total_images / (num_classes * count)
    for index, count in class_counts.items()
}

print("\nClass Weights:", class_weights)


# =====================================
# CREATE MODEL
# =====================================

print("\nCreating MobileNetV2 model...")

model = create_model()

model.summary()


# =====================================
# CALLBACKS
# =====================================

callbacks = [
    tf.keras.callbacks.ModelCheckpoint(
        filepath=str(MODEL_DIR / "mobilenetv2_best.keras"),
        monitor="val_accuracy",
        save_best_only=True,
        mode="max",
        verbose=1
    ),

    tf.keras.callbacks.EarlyStopping(
        monitor="val_loss",
        patience=5,
        restore_best_weights=True,
        verbose=1
    ),

    tf.keras.callbacks.ReduceLROnPlateau(
        monitor="val_loss",
        factor=0.2,
        patience=2,
        min_lr=0.00001,
        verbose=1
    )
]


# =====================================
# TRAINING
# =====================================

print("\nStarting MobileNetV2 training...")

history = model.fit(
    train_ds,
    validation_data=val_ds,
    epochs=EPOCHS,
    class_weight=class_weights,
    callbacks=callbacks
)


# =====================================
# SAVE FINAL MODEL
# =====================================

model.save(MODEL_DIR / "mobilenetv2_final.keras")

print("\nTraining completed!")
print("Best model:", MODEL_DIR / "mobilenetv2_best.keras")
print("Final model:", MODEL_DIR / "mobilenetv2_final.keras")
