import 'package:dio/dio.dart';
import '../../../../core/network/api_client.dart';

class FavoriteRepository {
  final Dio _dio = ApiClient.instance;

  Future<List<Map<String, dynamic>>> getFavorites() async {
    try {
      final response = await _dio.get('/favorites');
      final List data =
          response.data is List ? response.data : (response.data['data'] ?? []);
      return List<Map<String, dynamic>>.from(data);
    } catch (e) {
      throw Exception('Favoriler alınamadı: $e');
    }
  }

  Future<bool> toggleFavorite(int businessId) async {
    try {
      final response = await _dio.post('/favorites/toggle/$businessId');
      return response.data['favorited'] ?? false;
    } catch (e) {
      throw Exception('Favori değiştirilemedi: $e');
    }
  }
}
