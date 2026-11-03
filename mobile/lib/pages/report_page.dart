
import 'dart:io';

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:geolocator/geolocator.dart';

import '../services/api_service.dart';

class ReportPage extends StatefulWidget {
  const ReportPage({super.key});

  @override
  State<ReportPage> createState() => _ReportPageState();
}

class _ReportPageState extends State<ReportPage> {
  final titleController = TextEditingController();
  final descriptionController = TextEditingController();

  final ImagePicker picker = ImagePicker();

  int? selectedCategoryId;

  List<Map<String, dynamic>> categories = [];
  bool isLoadingCategories = false;
  bool isSubmitting = false;

  File? selectedImage;

  // Variabel koordinat GPS
  double? latitude;
  double? longitude;

  bool isLoadingLocation = false;

  @override
  void initState() {
    super.initState();
    loadCategories();
  }

  // Mengambil kategori dari API Laravel
  Future<void> loadCategories() async {
    setState(() {
      isLoadingCategories = true;
    });

    try {
      final result = await ApiService.getCategories();

      if (!mounted) return;

      setState(() {
        categories = result;
        isLoadingCategories = false;
      });
    } catch (e) {
      if (!mounted) return;

      setState(() {
        isLoadingCategories = false;
      });

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Gagal memuat kategori: $e'),
        ),
      );
    }
  }

  // Mengambil foto dari kamera atau galeri
  Future<void> pickImage(ImageSource source) async {
    try {
      final XFile? image = await picker.pickImage(
        source: source,
        imageQuality: 80,
      );

      if (image != null) {
        setState(() {
          selectedImage = File(image.path);
        });
      }
    } catch (e) {
      if (!mounted) return;

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Gagal mengambil foto: $e')),
      );
    }
  }

  // Menampilkan pilihan kamera dan galeri
  void showImagePicker() {
    showModalBottomSheet(
      context: context,
      builder: (context) {
        return SafeArea(
          child: Wrap(
            children: [
              ListTile(
                leading: const Icon(Icons.camera_alt),
                title: const Text('Ambil dari Kamera'),
                onTap: () {
                  Navigator.pop(context);
                  pickImage(ImageSource.camera);
                },
              ),
              ListTile(
                leading: const Icon(Icons.photo_library),
                title: const Text('Pilih dari Galeri'),
                onTap: () {
                  Navigator.pop(context);
                  pickImage(ImageSource.gallery);
                },
              ),
            ],
          ),
        );
      },
    );
  }

  // Mengambil lokasi GPS
  Future<void> getCurrentLocation() async {
    setState(() {
      isLoadingLocation = true;
    });

    try {
      // Periksa apakah GPS aktif
      bool serviceEnabled =
          await Geolocator.isLocationServiceEnabled();

      if (!serviceEnabled) {
        throw Exception(
          'GPS belum aktif. Silakan aktifkan lokasi perangkat.',
        );
      }

      // Periksa permission lokasi
      LocationPermission permission =
          await Geolocator.checkPermission();

      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
      }

      if (permission == LocationPermission.denied) {
        throw Exception('Izin lokasi ditolak.');
      }

      if (permission == LocationPermission.deniedForever) {
        throw Exception(
          'Izin lokasi ditolak permanen. Aktifkan melalui pengaturan aplikasi.',
        );
      }

      // Ambil koordinat GPS
      Position position = await Geolocator.getCurrentPosition(
        locationSettings: const LocationSettings(
          accuracy: LocationAccuracy.high,
          timeLimit: Duration(seconds: 30),
        ),
      );

      if (!mounted) return;

      setState(() {
        latitude = position.latitude;
        longitude = position.longitude;
        isLoadingLocation = false;
      });

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Lokasi berhasil diambil!'),
          backgroundColor: Colors.green,
        ),
      );
    } catch (e) {
      if (!mounted) return;

      setState(() {
        isLoadingLocation = false;
      });

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Gagal mengambil lokasi: $e'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

// Mengirim laporan ke API Laravel
Future<void> submitReport() async {
  if (titleController.text.trim().isEmpty) {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Judul laporan wajib diisi.'),
        backgroundColor: Colors.red,
      ),
    );
    return;
  }

  if (selectedCategoryId == null) {
  ScaffoldMessenger.of(context).showSnackBar(
    const SnackBar(
      content: Text('Silakan pilih kategori sampah.'),
      backgroundColor: Colors.red,
    ),
  );
  return;
}

  setState(() {
    isSubmitting = true;
  });

  try {
    final result = await ApiService.submitReport(
      title: titleController.text.trim(),
      description: descriptionController.text.trim(),
      categoryId: selectedCategoryId,
      latitude: latitude,
      longitude: longitude,
      photo: selectedImage,
    );

    if (!mounted) return;

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          result['message'] ?? 'Laporan berhasil dikirim.',
        ),
        backgroundColor: Colors.green,
      ),
    );

    Navigator.pop(context, true);
  } catch (e) {
    if (!mounted) return;

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Gagal mengirim laporan: $e'),
        backgroundColor: Colors.red,
      ),
    );
  } finally {
    if (mounted) {
      setState(() {
        isSubmitting = false;
      });
    }
  }
}

  @override
  void dispose() {
    titleController.dispose();
    descriptionController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: const Text(
          'Buat Laporan Sampah',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        backgroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Laporkan Sampah di Sekitarmu',
              style: TextStyle(
                fontSize: 22,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 8),
            const Text(
              'Bantu menjaga lingkungan dengan melaporkan sampah yang kamu temukan.',
              style: TextStyle(color: Colors.grey),
            ),

            const SizedBox(height: 28),

            // Judul laporan
            const Text(
              'Judul Laporan',
              style: TextStyle(fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            TextField(
              controller: titleController,
              decoration: const InputDecoration(
                hintText: 'Contoh: Sampah menumpuk di pinggir jalan',
                border: OutlineInputBorder(),
                filled: true,
                fillColor: Colors.white,
              ),
            ),

            const SizedBox(height: 20),

            // Kategori sampah dari API
            const Text(
              'Kategori Sampah',
              style: TextStyle(fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            DropdownButtonFormField<int>(
              initialValue: selectedCategoryId,
              decoration: InputDecoration(
                hintText: isLoadingCategories
                    ? 'Memuat kategori...'
                    : 'Pilih kategori sampah',
                border: const OutlineInputBorder(),
                filled: true,
                fillColor: Colors.white,
              ),
              items: categories.map((category) {
                return DropdownMenuItem<int>(
                  value: category['id'] as int,
                  child: Text(category['name']),
                );
              }).toList(),
              onChanged: isLoadingCategories
                  ? null
                  : (value) {
                      setState(() {
                        selectedCategoryId = value;
                      });
                    },
            ),

            const SizedBox(height: 20),

            // Deskripsi
            const Text(
              'Deskripsi',
              style: TextStyle(fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            TextField(
              controller: descriptionController,
              maxLines: 4,
              decoration: const InputDecoration(
                hintText: 'Jelaskan kondisi sampah yang ditemukan...',
                border: OutlineInputBorder(),
                filled: true,
                fillColor: Colors.white,
              ),
            ),

            const SizedBox(height: 24),

            // Foto sampah
            const Text(
              'Foto Sampah',
              style: TextStyle(fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),

            GestureDetector(
              onTap: showImagePicker,
              child: Container(
                width: double.infinity,
                height: 180,
                decoration: BoxDecoration(
                  color: Colors.white,
                  border: Border.all(
                    color: Colors.grey.shade300,
                  ),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: selectedImage != null
                    ? ClipRRect(
                        borderRadius: BorderRadius.circular(12),
                        child: Stack(
                          fit: StackFit.expand,
                          children: [
                            Image.file(
                              selectedImage!,
                              fit: BoxFit.cover,
                            ),
                            Positioned(
                              bottom: 0,
                              left: 0,
                              right: 0,
                              child: Container(
                                padding: const EdgeInsets.all(10),
                                color: Colors.black54,
                                child: const Text(
                                  'Ketuk untuk mengganti foto',
                                  textAlign: TextAlign.center,
                                  style: TextStyle(
                                    color: Colors.white,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                      )
                    : const Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(
                            Icons.add_a_photo_outlined,
                            size: 40,
                            color: Color(0xFF16A34A),
                          ),
                          SizedBox(height: 8),
                          Text('Ketuk untuk mengambil foto'),
                          Text(
                            'Kamera atau Galeri',
                            style: TextStyle(
                              color: Colors.grey,
                              fontSize: 12,
                            ),
                          ),
                        ],
                      ),
              ),
            ),

            const SizedBox(height: 20),

            // Lokasi GPS
            const Text(
              'Lokasi GPS',
              style: TextStyle(fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),

            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                border: Border.all(
                  color: Colors.grey.shade300,
                ),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      const Icon(
                        Icons.location_on_outlined,
                        color: Color(0xFF16A34A),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Text(
                          latitude != null && longitude != null
                              ? 'Lokasi berhasil ditemukan'
                              : 'Lokasi GPS belum diambil',
                          style: const TextStyle(
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                      ),
                    ],
                  ),

                  if (latitude != null && longitude != null) ...[
                    const SizedBox(height: 16),
                    Text(
                      'Latitude: $latitude',
                      style: const TextStyle(fontSize: 13),
                    ),
                    const SizedBox(height: 6),
                    Text(
                      'Longitude: $longitude',
                      style: const TextStyle(fontSize: 13),
                    ),
                  ],

                  const SizedBox(height: 16),

                  SizedBox(
                    width: double.infinity,
                    child: OutlinedButton.icon(
                      onPressed: isLoadingLocation
                          ? null
                          : getCurrentLocation,
                      icon: isLoadingLocation
                          ? const SizedBox(
                              width: 18,
                              height: 18,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                              ),
                            )
                          : const Icon(Icons.my_location),
                      label: Text(
                        isLoadingLocation
                            ? 'Mengambil Lokasi...'
                            : latitude != null
                                ? 'Perbarui Lokasi'
                                : 'Ambil Lokasi Sekarang',
                      ),
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 30),

// Tombol kirim laporan
SizedBox(
  width: double.infinity,
  height: 52,
  child: ElevatedButton(
    onPressed: isSubmitting ? null : submitReport,
    style: ElevatedButton.styleFrom(
      backgroundColor: const Color(0xFF16A34A),
      foregroundColor: Colors.white,
      disabledBackgroundColor: Colors.grey,
    ),
    child: isSubmitting
        ? const SizedBox(
            width: 22,
            height: 22,
            child: CircularProgressIndicator(
              strokeWidth: 2,
              color: Colors.white,
            ),
          )
        : const Text(
            'Kirim Laporan',
            style: TextStyle(fontSize: 16),
          ),
  ),
),
          ],
        ),
      ),
    );
  }
}
