
import tensorflow as tf
from tensorflow.keras import layers, models


IMG_SIZE = (224, 224)
NUM_CLASSES = 3


def create_model():
    # Data augmentation
    data_augmentation = tf.keras.Sequential([
        layers.RandomFlip("horizontal"),
        layers.RandomRotation(0.1),
        layers.RandomZoom(0.1),
    ])

    # Load MobileNetV2 pretrained ImageNet
    base_model = tf.keras.applications.MobileNetV2(
        input_shape=(224, 224, 3),
        include_top=False,
        weights="imagenet"
    )

    # Freeze pretrained layers
    base_model.trainable = False

    # Build classification model
    inputs = layers.Input(shape=(224, 224, 3))

    x = data_augmentation(inputs)

    # Preprocessing MobileNetV2
    x = layers.Rescaling(
        scale=1.0 / 127.5,
        offset=-1
    )(x)

    x = base_model(x, training=False)

    x = layers.GlobalAveragePooling2D()(x)
    x = layers.Dropout(0.3)(x)

    outputs = layers.Dense(
        NUM_CLASSES,
        activation="softmax"
    )(x)

    model = models.Model(
        inputs=inputs,
        outputs=outputs
    )

    model.compile(
        optimizer=tf.keras.optimizers.Adam(learning_rate=0.001),
        loss="sparse_categorical_crossentropy",
        metrics=["accuracy"]
    )

    return model
