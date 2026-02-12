import 'package:dio/dio.dart';
import '../../../../core/network/api_client.dart';
import '../models/notification_model.dart';

class NotificationRepository {
  final Dio _dio = ApiClient.instance;

  Future<List<NotificationModel>> getNotifications({int page = 1}) async {
    try {
      final response =
          await _dio.get('/notifications', queryParameters: {'page': page});
      final data = response.data['data'] as List;
      return data.map((json) => NotificationModel.fromJson(json)).toList();
    } catch (e) {
      throw Exception('Bildirimler yüklenirken hata oluştu: $e');
    }
  }

  Future<void> markAsRead(String id) async {
    try {
      await _dio.post('/notifications/$id/read');
    } catch (e) {
      throw Exception('Bildirim okundu işaretlenemedi: $e');
    }
  }

  Future<void> markAllAsRead() async {
    try {
      await _dio.post('/notifications/mark-all-read');
    } catch (e) {
      throw Exception('Tüm bildirimler okundu işaretlenemedi: $e');
    }
  }
}
