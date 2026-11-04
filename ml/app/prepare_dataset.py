
from pathlib import Path
import shutil

# Source dataset
SOURCE_DIR = Path(
    r"F:\dataset bilcode\alphatrash-dataset-main\trash_dataset"
)

# Destination project dataset
BASE_DIR = Path(__file__).resolve().parent.parent
DEST_DIR = BASE_DIR / "dataset"

CATEGORIES = ["organic", "paper", "plastic", "metal"]
SPLITS = ["train", "val", "test"]

# Validate source
if not SOURCE_DIR.exists():
    raise FileNotFoundError(f"Dataset tidak ditemukan: {SOURCE_DIR}")

# Copy dataset
for split in SPLITS:
    for category in CATEGORIES:

        source = SOURCE_DIR / split / category
        destination = DEST_DIR / split / category

        if not source.exists():
            raise FileNotFoundError(f"Folder tidak ditemukan: {source}")

        destination.mkdir(parents=True, exist_ok=True)

        files = [
            file for file in source.iterdir()
            if file.is_file()
        ]

        for file in files:
            shutil.copy2(file, destination / file.name)

        print(f"{split}/{category}: {len(files)} gambar disalin")

print("\nDataset berhasil disiapkan!")
