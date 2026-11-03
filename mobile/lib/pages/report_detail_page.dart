
import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../config/api_config.dart';

class ReportDetailPage extends StatefulWidget {
  final int reportId;

  const ReportDetailPage({
    super.key,
    required this.reportId,
  });

  @override
  State<ReportDetailPage> createState() => _ReportDetailPageState();
}

class _ReportDetailPageState extends State<ReportDetailPage> {
  Map<String, dynamic>? report;
  bool isLoading = true;
  String? errorMessage;

  @override
  void initState() {
    super.initState();
    loadDetail();
  }

  Future<void> loadDetail() async {
    try {
      final result = await ApiService.getReportDetail(widget.reportId);

      if (!mounted) return;

      setState(() {
        report = result;
        isLoading = false;
      });
    } catch (e) {
      if (!mounted) return;

      setState(() {
        errorMessage = e.toString();
        isLoading = false;
      });
    }
  }

  String getStatusLabel(String status) {
    switch (status) {
      case 'pending':
        return 'Menunggu';
      case 'verified':
        return 'Terverifikasi';
      case 'in_progress':
        return 'Sedang Diproses';
      case 'completed':
        return 'Selesai';
      case 'rejected':
        return 'Ditolak';
      default:
        return status;
    }
  }

  Color getStatusColor(String status) {
    switch (status) {
      case 'pending':
        return Colors.orange;
      case 'verified':
        return Colors.blue;
      case 'in_progress':
        return Colors.purple;
      case 'completed':
        return Colors.green;
      case 'rejected':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }

  String formatDate(dynamic value) {
    if (value == null) return '-';

    final date = DateTime.tryParse(value.toString());
    if (date == null) return '-';

    return '${date.day.toString().padLeft(2, '0')}/'
        '${date.month.toString().padLeft(2, '0')}/'
        '${date.year} ${date.hour.toString().padLeft(2, '0')}:'
        '${date.minute.toString().padLeft(2, '0')}';
  }

  String getImageUrl(String path) {
    if (path.startsWith('http')) return path;

    final baseUrl = ApiConfig.baseUrl.replaceFirst('/api', '');
    return '$baseUrl/storage/$path';
  }

  Widget buildInfo(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              color: Colors.grey,
              fontSize: 13,
            ),
          ),
          const SizedBox(height: 5),
          Text(
            value,
            style: const TextStyle(
              fontSize: 15,
              fontWeight: FontWeight.w600,
            ),
          ),
        ],
      ),
    );
  }

  Widget buildStatusHistory(List histories) {
    if (histories.isEmpty) {
      return const Text(
        'Belum ada riwayat perubahan status.',
        style: TextStyle(color: Colors.grey),
      );
    }

    return Column(
      children: histories.map((item) {
        final status = item['new_status']?.toString() ?? '-';
        final notes = item['notes']?.toString() ?? '';
        final changedBy = item['changed_by'];

        return Padding(
          padding: const EdgeInsets.only(bottom: 18),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Icon(
                Icons.check_circle,
                color: getStatusColor(status),
                size: 22,
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      getStatusLabel(status),
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 15,
                      ),
                    ),
                    if (notes.isNotEmpty) ...[
                      const SizedBox(height: 4),
                      Text(
                        notes,
                        style: const TextStyle(color: Colors.grey),
                      ),
                    ],
                    const SizedBox(height: 4),
                    Text(
                      formatDate(item['created_at']),
                      style: const TextStyle(
                        color: Colors.grey,
                        fontSize: 12,
                      ),
                    ),
                    if (changedBy is Map &&
                        changedBy['name'] != null)
                      Text(
                        'Oleh: ${changedBy['name']}',
                        style: const TextStyle(
                          color: Colors.grey,
                          fontSize: 12,
                        ),
                      ),
                  ],
                ),
              ),
            ],
          ),
        );
      }).toList(),
    );
  }

  Widget buildDetail() {
    final data = report!;
    final status = data['status']?.toString() ?? 'pending';
    final category = data['category'];
    final images = data['images'] as List? ?? [];
    final histories = data['status_histories'] as List? ?? [];

    final imagePath = images.isNotEmpty
        ? images.first['image_path']?.toString()
        : null;

    return ListView(
      padding: const EdgeInsets.all(20),
      children: [
        if (imagePath != null && imagePath.isNotEmpty)
          ClipRRect(
            borderRadius: BorderRadius.circular(16),
            child: Image.network(
              getImageUrl(imagePath),
              height: 230,
              width: double.infinity,
              fit: BoxFit.cover,
              errorBuilder: (context, error, stackTrace) {
                return Container(
                  height: 230,
                  color: Colors.grey.shade200,
                  child: const Icon(Icons.broken_image, size: 50),
                );
              },
            ),
          ),

        const SizedBox(height: 20),

        Text(
          data['title']?.toString() ?? 'Tanpa Judul',
          style: const TextStyle(
            fontSize: 23,
            fontWeight: FontWeight.bold,
          ),
        ),

        const SizedBox(height: 12),

        Align(
          alignment: Alignment.centerLeft,
          child: Container(
            padding: const EdgeInsets.symmetric(
              horizontal: 14,
              vertical: 8,
            ),
            decoration: BoxDecoration(
              color: getStatusColor(status).withAlpha(25),
              borderRadius: BorderRadius.circular(20),
            ),
            child: Text(
              getStatusLabel(status),
              style: TextStyle(
                color: getStatusColor(status),
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ),

        const SizedBox(height: 25),

        const Text(
          'Informasi Laporan',
          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
        ),

        const SizedBox(height: 16),

        buildInfo(
          'Kategori',
          category is Map
              ? category['name']?.toString() ?? '-'
              : '-',
        ),

        buildInfo(
          'Deskripsi',
          data['description']?.toString().isNotEmpty == true
              ? data['description'].toString()
              : 'Tidak ada deskripsi.',
        ),

        buildInfo('Tanggal Laporan', formatDate(data['created_at'])),

        buildInfo(
          'Latitude',
          data['latitude']?.toString() ?? '-',
        ),

        buildInfo(
          'Longitude',
          data['longitude']?.toString() ?? '-',
        ),

        const Divider(height: 30),

        const Text(
          'Riwayat Status',
          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
        ),

        const SizedBox(height: 18),

        buildStatusHistory(histories),
      ],
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: const Text(
          'Detail Laporan',
          style: TextStyle(
            color: Color(0xFF16A34A),
            fontWeight: FontWeight.bold,
          ),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.black),
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : errorMessage != null
              ? Center(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(errorMessage!),
                      TextButton(
                        onPressed: () {
                          setState(() {
                            isLoading = true;
                            errorMessage = null;
                          });
                          loadDetail();
                        },
                        child: const Text('Coba Lagi'),
                      ),
                    ],
                  ),
                )
              : buildDetail(),
    );
  }
}
