import 'package:dio/dio.dart';
import '../../../../core/network/api_client.dart';

class AnalyticsRepository {
  final Dio _dio = ApiClient.instance;

  Future<List<Map<String, dynamic>>> getWeeklyTrend() async {
    try {
      final response = await _dio.get('/pos/analytics/weekly-trend');
      if (response.data != null && response.data['success'] == true) {
        return List<Map<String, dynamic>>.from(response.data['data']);
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  Future<List<Map<String, dynamic>>> getTopProducts() async {
    try {
      final response = await _dio.get('/pos/analytics/top-products');
      if (response.data != null && response.data['success'] == true) {
        return List<Map<String, dynamic>>.from(response.data['data']);
      }
      return [];
    } catch (e) {
      return [];
    }
  }
}
