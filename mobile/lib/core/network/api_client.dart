import 'package:dio/dio.dart';

class ApiClient {
  static const String _host = 'https://rezervist.com';

  static final Dio _dio = Dio(
    BaseOptions(
      baseUrl: '$_host/api', // Physical device via ADB reverse
      connectTimeout: const Duration(seconds: 30),
      receiveTimeout: const Duration(seconds: 30),
      headers: {
        'Accept': 'application/json',
        'Connection': 'close',
      },
    ),
  );

  static String get baseHost => _host;
  static Dio get instance => _dio;
}
