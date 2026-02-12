import 'package:dio/dio.dart';
import '../../../../core/network/api_client.dart';
import '../../domain/models/business.dart';
import '../../domain/models/menu.dart';

class BusinessRepository {
  final Dio _dio = ApiClient.instance;

  Future<List<Business>> getBusinesses({
    String? query,
    int? categoryId,
    double? minPrice,
    double? maxPrice,
    double? distance,
    double? lat,
    double? lng,
  }) async {
    try {
      final response = await _dio.get('/businesses', queryParameters: {
        if (query != null) 'search': query,
        if (categoryId != null) 'category_id': categoryId,
        if (minPrice != null) 'min_price': minPrice,
        if (maxPrice != null) 'max_price': maxPrice,
        if (distance != null) 'distance': distance,
        if (lat != null) 'lat': lat,
        if (lng != null) 'lng': lng,
      });
      final List<dynamic> data = response.data['data'] ?? response.data;
      // Handling potential pagination wrapper 'data' or direct list

      return data.map((json) => Business.fromJson(json)).toList();
    } catch (e) {
      throw Exception('Failed to load businesses: $e');
    }
  }

  Future<Business> getBusiness(int id) async {
    try {
      final response = await _dio.get('/businesses/$id');
      final rawData = response.data;
      final Map<String, dynamic> json;
      if (rawData is Map<String, dynamic>) {
        json = rawData.containsKey('data') &&
                rawData['data'] is Map<String, dynamic>
            ? rawData['data'] as Map<String, dynamic>
            : rawData;
      } else {
        throw Exception('Invalid response format');
      }
      return Business.fromJson(json);
    } catch (e) {
      throw Exception('Failed to load business details: $e');
    }
  }

  Future<void> toggleFavorite(int id) async {
    try {
      await _dio.post('/businesses/$id/favorite');
    } catch (e) {
      throw Exception('Failed to toggle favorite: $e');
    }
  }

  Future<Map<String, dynamic>> submitReview(
    int businessId, {
    required int rating,
    required String comment,
  }) async {
    try {
      final response =
          await _dio.post('/businesses/$businessId/reviews', data: {
        'rating': rating,
        'comment': comment,
      });
      return Map<String, dynamic>.from(response.data);
    } on DioException catch (e) {
      final msg = e.response?.data is Map
          ? (e.response!.data['message'] ?? 'Yorum gönderilemedi.')
          : 'Yorum gönderilemedi.';
      throw Exception(msg);
    } catch (e) {
      throw Exception('Yorum gönderilemedi: $e');
    }
  }

  Future<List<Menu>> getMenus(int businessId) async {
    try {
      final response = await _dio.get('/businesses/$businessId/menus');
      final List<dynamic> data = response.data['data'] ?? response.data;
      return data.map((json) => Menu.fromJson(json)).toList();
    } catch (e) {
      throw Exception('Failed to load menus: $e');
    }
  }

  Future<List<String>> getAvailableSlots(int businessId, String date) async {
    try {
      final response = await _dio.get(
        '/businesses/$businessId/available-slots',
        queryParameters: {'date': date},
      );

      final data = response.data;
      if (data != null && data['slots'] != null) {
        return List<String>.from(data['slots']);
      }
      return [];
    } catch (e) {
      // Fallback or rethrow based on requirement, usually empty list is safer for UI
      return [];
    }
  }
}
