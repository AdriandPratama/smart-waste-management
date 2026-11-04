# Machine Learning - Smart Waste Management

## 1. Overview

This module is part of the Smart Waste Management application. It uses a deep learning model to classify waste images into three categories:

- Organic
- Paper
- Plastic

The classification model uses **MobileNetV2**, a lightweight convolutional neural network architecture suitable for image classification.

## 2. Dataset

The dataset consists of three waste categories:

| Category | Description |
|---|---|
| Organic | Organic waste |
| Paper | Paper waste |
| Plastic | Plastic waste |

Dataset structure:

```text
dataset_3class/
├── train/
│   ├── organic/
│   ├── paper/
│   └── plastic/
├── validation/
│   ├── organic/
│   ├── paper/
│   └── plastic/
└── test/
    ├── organic/
    ├── paper/
    └── plastic/
```

The test dataset contains 512 images.

## 3. Model Architecture

- Model: MobileNetV2
- Input Image Size: 224 x 224 pixels
- Number of Classes: 3
- Model File: `models/mobilenetv2_best.keras`

## 4. Model Evaluation

The trained model was evaluated using the test dataset.

### Evaluation Results

| Metric | Result |
|---|---:|
| Test Accuracy | 96.88% |
| Test Loss | 0.1074 |
| Test Images | 512 |
| Correct Predictions | 496 |

### Classification Report

| Category | Precision | Recall | F1-Score |
|---|---:|---:|---:|
| Organic | 1.00 | 0.98 | 0.99 |
| Paper | 0.95 | 0.94 | 0.94 |
| Plastic | 0.97 | 0.98 | 0.97 |
| Macro Average | 0.97 | 0.97 | 0.97 |
| Weighted Average | 0.97 | 0.97 | 0.97 |

### Confusion Matrix

| Actual / Predicted | Organic | Paper | Plastic |
|---|---:|---:|---:|
| Organic | 120 | 1 | 1 |
| Paper | 0 | 125 | 8 |
| Plastic | 0 | 6 | 251 |

### Evaluation Analysis

- Organic achieved the best classification performance with an F1-score of 0.99.
- Paper had the lowest F1-score of 0.94, with some images misclassified as plastic.
- Plastic achieved an F1-score of 0.97.
- Overall, the model correctly classified 496 out of 512 test images.

## 5. Evaluation Files

Evaluation results are automatically saved in the `results/` directory.

```text
results/
├── confusion_matrix.csv
├── classification_report.txt
└── classification_report.csv
```

| File | Description |
|---|---|
| confusion_matrix.csv | Stores the confusion matrix |
| classification_report.txt | Stores the classification report in text format |
| classification_report.csv | Stores classification metrics in CSV format |

## 6. How to Run Evaluation

### Prerequisites

- Python
- TensorFlow
- NumPy
- Pandas
- Scikit-learn

### Install Dependencies

Activate the virtual environment and install the required packages:

```bash
pip install tensorflow numpy pandas scikit-learn
```

### Run Evaluation

Navigate to the `ml` directory and execute:

```bash
python app/evaluate.py
```

The evaluation script will:

1. Load the trained MobileNetV2 model.
2. Load the test dataset.
3. Calculate test accuracy and loss.
4. Generate predictions.
5. Generate the confusion matrix.
6. Generate the classification report.
7. Save all evaluation results into the `results/` directory.

## 7. Conclusion

The MobileNetV2 model achieved a test accuracy of **96.88%** on 512 test images.

The evaluation results demonstrate that the model can classify organic, paper, and plastic waste with high accuracy.

The evaluation reports are also saved automatically for documentation and further analysis.







INDONESIAN VERSION

# Machine Learning - Smart Waste Management

## 1. Gambaran Umum

Modul Machine Learning ini merupakan bagian dari aplikasi Smart Waste Management yang digunakan untuk mengklasifikasikan gambar sampah ke dalam tiga kategori, yaitu:

- Organik
- Kertas
- Plastik

Model yang digunakan adalah **MobileNetV2**, yaitu arsitektur Convolutional Neural Network (CNN) yang dapat digunakan untuk klasifikasi gambar dengan ukuran model yang relatif ringan.

## 2. Dataset

Dataset yang digunakan terdiri dari tiga kategori sampah:

| Kategori | Deskripsi |
|---|---|
| Organik | Sampah organik |
| Kertas | Sampah berbahan kertas |
| Plastik | Sampah berbahan plastik |

Struktur dataset:

```text
dataset_3class/
├── train/
│   ├── organic/
│   ├── paper/
│   └── plastic/
├── validation/
│   ├── organic/
│   ├── paper/
│   └── plastic/
└── test/
    ├── organic/
    ├── paper/
    └── plastic/
```

Dataset pengujian (test) terdiri dari 512 gambar.

## 3. Arsitektur Model

Model yang digunakan dalam proses klasifikasi sampah memiliki spesifikasi berikut:

- Arsitektur: MobileNetV2
- Ukuran input gambar: 224 x 224 piksel
- Jumlah kategori: 3 kelas
- File model: `models/mobilenetv2_best.keras`

## 4. Hasil Evaluasi Model

Model yang telah dilatih kemudian dievaluasi menggunakan dataset pengujian (test) untuk mengetahui tingkat performa klasifikasinya.

### Hasil Evaluasi

| Metrik | Hasil |
|---|---:|
| Akurasi Pengujian | 96,88% |
| Loss Pengujian | 0,1074 |
| Jumlah Gambar Pengujian | 512 |
| Prediksi Benar | 496 |

### Classification Report

Berikut hasil pengukuran Precision, Recall, dan F1-Score untuk setiap kategori:

| Kategori | Precision | Recall | F1-Score |
|---|---:|---:|---:|
| Organik | 1,00 | 0,98 | 0,99 |
| Kertas | 0,95 | 0,94 | 0,94 |
| Plastik | 0,97 | 0,98 | 0,97 |
| Macro Average | 0,97 | 0,97 | 0,97 |
| Weighted Average | 0,97 | 0,97 | 0,97 |

### Confusion Matrix

Confusion Matrix digunakan untuk melihat perbandingan antara kategori sebenarnya dengan hasil prediksi model.

| Aktual / Prediksi | Organik | Kertas | Plastik |
|---|---:|---:|---:|
| Organik | 120 | 1 | 1 |
| Kertas | 0 | 125 | 8 |
| Plastik | 0 | 6 | 251 |

### Analisis Hasil Evaluasi

Berdasarkan hasil pengujian, diperoleh beberapa kesimpulan:

- Kategori organik memiliki performa klasifikasi terbaik dengan F1-Score sebesar 0,99.
- Kategori kertas memiliki F1-Score sebesar 0,94. Beberapa gambar kertas masih salah diklasifikasikan sebagai plastik.
- Kategori plastik memperoleh F1-Score sebesar 0,97.
- Secara keseluruhan, model berhasil mengklasifikasikan 496 dari 512 gambar pengujian dengan benar.

Hasil tersebut menunjukkan bahwa model MobileNetV2 memiliki performa yang baik dalam mengklasifikasikan ketiga kategori sampah.

## 5. File Hasil Evaluasi

Seluruh hasil evaluasi model disimpan secara otomatis ke dalam folder `results/`.

Struktur folder:

```text
results/
├── confusion_matrix.csv
├── classification_report.txt
└── classification_report.csv
```

Keterangan file:

| File | Keterangan |
|---|---|
| confusion_matrix.csv | Menyimpan hasil Confusion Matrix |
| classification_report.txt | Menyimpan laporan evaluasi dalam format teks |
| classification_report.csv | Menyimpan hasil metrik klasifikasi dalam format CSV |

## 6. Cara Menjalankan Evaluasi

### Persyaratan

Sebelum menjalankan evaluasi, pastikan beberapa library berikut sudah terpasang:

- Python
- TensorFlow
- NumPy
- Pandas
- Scikit-learn

### Instalasi Dependencies

Aktifkan virtual environment, kemudian jalankan perintah:

```bash
pip install tensorflow numpy pandas scikit-learn
```

### Menjalankan Evaluasi

Masuk ke direktori `ml`, kemudian jalankan perintah berikut:

```bash
python app/evaluate.py
```

Script evaluasi akan menjalankan beberapa proses secara otomatis:

1. Memuat model MobileNetV2 yang telah dilatih.
2. Memuat dataset pengujian.
3. Menghitung nilai akurasi dan loss.
4. Melakukan prediksi terhadap gambar pengujian.
5. Menghasilkan Confusion Matrix.
6. Menghasilkan Classification Report.
7. Menyimpan seluruh hasil evaluasi ke dalam folder `results/`.

## 7. Kesimpulan

Berdasarkan hasil evaluasi menggunakan 512 gambar pengujian, model MobileNetV2 berhasil memperoleh tingkat akurasi sebesar **96,88%** dengan nilai loss sebesar 0,1074.

Model mampu mengklasifikasikan sampah ke dalam tiga kategori, yaitu organik, kertas, dan plastik dengan performa klasifikasi yang baik.

Hasil evaluasi juga menunjukkan bahwa kategori kertas masih memiliki beberapa kesalahan prediksi terhadap kategori plastik. Meskipun demikian, secara keseluruhan model telah menunjukkan hasil yang baik dalam proses klasifikasi sampah.

Seluruh hasil evaluasi telah disimpan secara otomatis sehingga dapat digunakan untuk dokumentasi dan analisis lebih lanjut.
