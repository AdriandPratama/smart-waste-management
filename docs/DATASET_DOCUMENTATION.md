# Dataset Documentation
## Smart Waste Management System

Dataset Source and License

Dataset Name: AlphaTrash Dataset

Author: Patipol Tiyajamorn

Copyright: Copyright (c) 2023 Patipol Tiyajamorn

Source Repository:
https://github.com/Patipol-BKK/alphatrash-dataset

License: MIT License

The AlphaTrash Dataset repository provides an MIT License, which permits use, copying, modification, distribution, sublicensing, and commercial use, provided that the original copyright notice and permission notice are included in all copies or substantial portions of the software.

The original dataset license notice is available in the source repository.

Attribution

This project uses a selected subset of the AlphaTrash Dataset for training and evaluating the MobileNetV2 waste classification model.

Original dataset author: Patipol Tiyajamorn.

Copyright (c) 2023 Patipol Tiyajamorn.

Dokumen ini menjelaskan sumber, kategori, distribusi, dan proses persiapan dataset yang digunakan dalam pengembangan Machine Learning pada Smart Waste Management System.

## 1. Informasi Dataset

| Informasi | Keterangan |
|---|---|
| Nama Dataset | AlphaTrash Dataset |
| Format | Image Classification |
| Jumlah Kategori | 3 kategori |
| Jumlah Gambar Digunakan | 3.436 gambar |
| Framework | TensorFlow / Keras |
| Model | MobileNetV2 |

Dataset diperoleh dari AlphaTrash Dataset dan dipersiapkan kembali untuk kebutuhan pelatihan serta evaluasi model klasifikasi sampah.

**Sumber Dataset:**  
https://github.com/Patipol-BKK/alphatrash-dataset.git

**Lisensi Dataset:**  
Perlu diperiksa berdasarkan ketentuan penggunaan pada sumber dataset asli.

## 2. Kategori Dataset

Model menggunakan tiga kategori sampah:

| Kategori | Deskripsi |
|---|---|
| Organic | Sampah organik |
| Paper | Sampah kertas |
| Plastic | Sampah plastik |

Kategori `Metal` dan `General` dari dataset asli tidak digunakan dalam pelatihan maupun evaluasi model.

## 3. Distribusi Dataset

Dataset dibagi menjadi training, validation, dan testing.

### Training Dataset

| Kategori | Jumlah Gambar |
|---|---:|
| Organic | 574 |
| Paper | 627 |
| Plastic | 1.206 |
| **Total** | **2.407** |

### Validation Dataset

| Kategori | Jumlah Gambar |
|---|---:|
| Organic | 123 |
| Paper | 135 |
| Plastic | 259 |
| **Total** | **517** |

### Testing Dataset

| Kategori | Jumlah Gambar |
|---|---:|
| Organic | 122 |
| Paper | 133 |
| Plastic | 257 |
| **Total** | **512** |

### Ringkasan Dataset

| Pembagian | Jumlah Gambar |
|---|---:|
| Training | 2.407 |
| Validation | 517 |
| Testing | 512 |
| **Total** | **3.436** |

## 4. Persiapan Dataset

Dataset dipersiapkan melalui beberapa tahapan:

1. Mengekstrak dataset asli.
2. Memeriksa struktur folder dan kategori gambar.
3. Memilih kategori Organic, Paper, dan Plastic.
4. Mengecualikan kategori Metal dan General.
5. Memisahkan gambar ke dalam folder training, validation, dan testing.
6. Menyalin gambar ke direktori dataset project.
7. Memastikan struktur folder sesuai untuk proses training TensorFlow/Keras.

## 5. Struktur Dataset

```text
dataset/
├── train/
│   ├── organic/
│   ├── paper/
│   └── plastic/
├── val/
│   ├── organic/
│   ├── paper/
│   └── plastic/
└── test/
    ├── organic/
    ├── paper/
    └── plastic/
```

## 6. Penggunaan Dataset

Dataset digunakan untuk melatih dan mengevaluasi model klasifikasi gambar sampah menggunakan TensorFlow/Keras dengan arsitektur MobileNetV2.

Tahapan penggunaan:

- Training: melatih model.
- Validation: memantau performa model selama proses training.
- Testing: mengukur performa model menggunakan data pengujian.

## 7. Evaluasi Model

Evaluasi dilakukan menggunakan 512 gambar pada dataset testing.

| Metrik | Hasil |
|---|---:|
| Jumlah Data Testing | 512 |
| Prediksi Benar | 496 |
| Test Accuracy | 96,88% |
| Test Loss | 0,1074 |

Hasil evaluasi menunjukkan bahwa model mencapai akurasi 96,88% pada data testing yang digunakan.

## 8. Catatan

- Dataset asli tidak disertakan secara keseluruhan dalam repository karena ukuran file gambar.
- Dataset dapat diperoleh melalui sumber asli yang dicantumkan pada bagian informasi dataset.
- Hanya tiga kategori, yaitu Organic, Paper, dan Plastic, yang digunakan dalam model.
- Kategori Metal dan General tidak digunakan.
- Penggunaan dan distribusi dataset mengikuti ketentuan lisensi dari sumber aslinya.

---

**Project:** Smart Waste Management System  
**Purpose:** Technical Coding Test – Fullstack Developer  
**Developer:** Adriand Pratama
