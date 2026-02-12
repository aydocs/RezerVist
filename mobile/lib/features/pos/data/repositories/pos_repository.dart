import '../../../../core/network/api_client.dart';

class PosRepository {
  // ApiClient is a static wrapper, so we access the instance directly

  Future<Map<String, dynamic>> getDailySummary() async {
    try {
      final response = await ApiClient.instance.get('/pos/summary');
      return response.data['data'];
    } catch (e) {
      throw Exception('Günlük özet alınamadı: $e');
    }
  }

  Future<List<Map<String, dynamic>>> getTables() async {
    try {
      final response = await ApiClient.instance.get('/pos/tables');
      final List data = response.data['data'];
      return List<Map<String, dynamic>>.from(data);
    } catch (e) {
      throw Exception('Masalar alınamadı: $e');
    }
  }

  Future<int> getOccupancy() async {
    try {
      final response = await ApiClient.instance.get('/pos/occupancy');
      return response.data['data']['rate'] ?? 0;
    } catch (e) {
      return 0;
    }
  }

  Future<Map<String, dynamic>> getOrder(int resourceId) async {
    try {
      print(
          'DEBUG POS: Fetching order for resource $resourceId at /pos/order/$resourceId');
      final response = await ApiClient.instance.get('/pos/order/$resourceId');
      print('DEBUG POS: Order response: ${response.data}');
      return response.data['data'];
    } catch (e) {
      print('DEBUG POS: Order fetch error: $e');
      throw Exception('Sipariş detayları alınamadı: $e');
    }
  }

  Future<List<Map<String, dynamic>>> getWeeklyTrend() async {
    try {
      final response =
          await ApiClient.instance.get('/pos/analytics/weekly-trend');
      final List data = response.data['data'] ?? [];
      return List<Map<String, dynamic>>.from(data);
    } catch (e) {
      throw Exception('Haftalık trend alınamadı: $e');
    }
  }

  Future<List<Map<String, dynamic>>> getTopProducts({int days = 7}) async {
    try {
      final response = await ApiClient.instance
          .get('/pos/analytics/top-products', queryParameters: {'days': days});
      final List data = response.data['data'] ?? [];
      return List<Map<String, dynamic>>.from(data);
    } catch (e) {
      throw Exception('En çok satanlar alınamadı: $e');
    }
  }

  Future<List<Map<String, dynamic>>> getHourlySales() async {
    try {
      final response =
          await ApiClient.instance.get('/pos/analytics/hourly-sales');
      final List data = response.data['data'] ?? [];
      return List<Map<String, dynamic>>.from(data);
    } catch (e) {
      throw Exception('Saatlik satışlar alınamadı: $e');
    }
  }
}
