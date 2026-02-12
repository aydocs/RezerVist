import 'package:dio/dio.dart';
import '../../../../core/network/api_client.dart';

class QrRepository {
  final Dio _dio = ApiClient.instance;

  /// Start a QR session for a table
  Future<Map<String, dynamic>> startSession({
    required int businessId,
    required int resourceId,
  }) async {
    try {
      final response = await _dio.post('/qr/session', data: {
        'business_id': businessId,
        'resource_id': resourceId,
      });
      return Map<String, dynamic>.from(response.data['data']);
    } catch (e) {
      throw Exception('QR oturum başlatılamadı: $e');
    }
  }

  /// Get session details
  Future<Map<String, dynamic>> getSession(String token) async {
    try {
      final response = await _dio.get('/qr/session/$token');
      return Map<String, dynamic>.from(response.data['data']);
    } catch (e) {
      throw Exception('Oturum bilgisi alınamadı: $e');
    }
  }

  /// Get menu for the session
  Future<Map<String, dynamic>> getMenu(String token) async {
    try {
      final response = await _dio.get('/qr/session/$token/menu');
      return Map<String, dynamic>.from(response.data['data']);
    } catch (e) {
      throw Exception('Menü yüklenemedi: $e');
    }
  }

  /// Submit order
  Future<Map<String, dynamic>> submitOrder(
      String token, List<Map<String, dynamic>> items) async {
    try {
      final response = await _dio.post('/qr/session/$token/order', data: {
        'items': items,
      });
      return Map<String, dynamic>.from(response.data['data']);
    } catch (e) {
      throw Exception('Sipariş gönderilemedi: $e');
    }
  }

  /// Get bill
  Future<Map<String, dynamic>> getBill(String token) async {
    try {
      final response = await _dio.get('/qr/session/$token/bill');
      return Map<String, dynamic>.from(response.data['data']);
    } catch (e) {
      throw Exception('Hesap bilgisi alınamadı: $e');
    }
  }

  /// Pay bill
  Future<Map<String, dynamic>> payBill(
    String token, {
    required String paymentMethod,
    double? amount,
  }) async {
    try {
      final response = await _dio.post('/qr/session/$token/pay', data: {
        'payment_method': paymentMethod,
        if (amount != null) 'amount': amount,
      });
      return Map<String, dynamic>.from(response.data['data']);
    } catch (e) {
      throw Exception('Ödeme yapılamadı: $e');
    }
  }
}
