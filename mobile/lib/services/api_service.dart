import 'dart:io';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../config/api_config.dart';

class ApiService {
  static Future<Map<String, dynamic>> login(
    String email,
    String password,
  ) async {
    final response = await http.post(
      Uri.parse('${ApiConfig.baseUrl}/login'),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
    );

    final data = jsonDecode(response.body);

    if (response.statusCode == 200) {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('token', data['token']);
      await prefs.setString('user', jsonEncode(data['user']));

      return data;
    }

    throw Exception(data['message'] ?? 'Login gagal');
  }

  static Future<Map<String, dynamic>> register(
    String name,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    final response = await http.post(
      Uri.parse('${ApiConfig.baseUrl}/register'),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: jsonEncode({
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
      }),
    );

    final data = jsonDecode(response.body);

    if (response.statusCode == 201) {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('token', data['token']);
      await prefs.setString('user', jsonEncode(data['user']));

      return data;
    }

    throw Exception(data['message'] ?? 'Registrasi gagal');
  }



  static Future<List<Map<String, dynamic>>> getCategories() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    if (token == null) {
      throw Exception('Token tidak ditemukan. Silakan login kembali.');
    }

    final response = await http.get(
      Uri.parse('${ApiConfig.baseUrl}/categories'),
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    final data = jsonDecode(response.body);

    if (response.statusCode == 200) {
      return List<Map<String, dynamic>>.from(data);
    }

    throw Exception('Gagal mengambil kategori');
  }

static Future<List<Map<String, dynamic>>> getMyReports() async {
  final prefs = await SharedPreferences.getInstance();
  final token = prefs.getString('token');

  if (token == null) {
    throw Exception('Token tidak ditemukan. Silakan login kembali.');
  }

  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/my-reports'),
    headers: {
      'Accept': 'application/json',
      'Authorization': 'Bearer $token',
    },
  );

  final data = jsonDecode(response.body);

  if (response.statusCode == 200) {
    return List<Map<String, dynamic>>.from(data);
  }

  throw Exception(data['message'] ?? 'Gagal mengambil laporan');
}

static Future<Map<String, dynamic>> submitReport({
  required String title,
  String? description,
  int? categoryId,
  double? latitude,
  double? longitude,
  File? photo,
}) async {
  final prefs = await SharedPreferences.getInstance();
  final token = prefs.getString('token');

  if (token == null) {
    throw Exception('Token tidak ditemukan. Silakan login kembali.');
  }

  final request = http.MultipartRequest(
    'POST',
    Uri.parse('${ApiConfig.baseUrl}/reports'),
  );

  request.headers.addAll({
    'Accept': 'application/json',
    'Authorization': 'Bearer $token',
  });

  request.fields['title'] = title;

  if (description != null && description.isNotEmpty) {
    request.fields['description'] = description;
  }

  if (categoryId != null) {
    request.fields['category_id'] = categoryId.toString();
  }

  if (latitude != null) {
    request.fields['latitude'] = latitude.toString();
  }

  if (longitude != null) {
    request.fields['longitude'] = longitude.toString();
  }

  if (photo != null) {
    request.files.add(
      await http.MultipartFile.fromPath(
        'photo',
        photo.path,
      ),
    );
  }

  final streamedResponse = await request.send();
  final response = await http.Response.fromStream(streamedResponse);

  final data = jsonDecode(response.body);

  if (response.statusCode == 201) {
    return Map<String, dynamic>.from(data);
  }

  throw Exception(data['message'] ?? 'Gagal mengirim laporan');
}


  static Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    if (token != null) {
      await http.post(
        Uri.parse('${ApiConfig.baseUrl}/logout'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );
    }

    await prefs.remove('token');
    await prefs.remove('user');
  }

  static Future<Map<String, dynamic>> getReportDetail(int id) async {
  final prefs = await SharedPreferences.getInstance();
  final token = prefs.getString('token');

  if (token == null) {
    throw Exception('Token tidak ditemukan.');
  }

  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/reports/$id'),
    headers: {
      'Accept': 'application/json',
      'Authorization': 'Bearer $token',
    },
  );

  final data = jsonDecode(response.body);

  if (response.statusCode == 200) {
    return Map<String, dynamic>.from(data);
  }

  throw Exception(data['message'] ?? 'Gagal mengambil detail laporan');
}

}
