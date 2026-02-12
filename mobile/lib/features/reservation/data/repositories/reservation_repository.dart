import 'package:dio/dio.dart';
import '../../../../core/network/api_client.dart';
import '../../domain/models/reservation.dart';

class ReservationRepository {
  final Dio _dio = ApiClient.instance;

  Future<Map<String, dynamic>> createReservation({
    required int businessId,
    required String reservationDate,
    required String reservationTime,
    required int guestCount,
    required String paymentMethod,
    List<Map<String, dynamic>>? services,
    String? specialRequests,
  }) async {
    try {
      final response = await _dio.post('/reservations', data: {
        'business_id': businessId,
        'reservation_date': reservationDate,
        'reservation_time': reservationTime,
        'guest_count': guestCount,
        'payment_method': paymentMethod,
        'services': services,
        'special_requests': specialRequests,
      });

      if (response.statusCode == 201) {
        return {
          'reservation': Reservation.fromJson(response.data['reservation']),
          'payment_url': response.data['payment_url'],
          'message': response.data['message'],
        };
      } else {
        throw Exception('Failed to create reservation');
      }
    } catch (e) {
      if (e is DioException) {
        throw Exception(
            e.response?.data['message'] ?? 'Rezervasyon oluşturulamadı.');
      }
      throw Exception('Reservation creation failed: $e');
    }
  }

  Future<List<Reservation>> getMyReservations() async {
    try {
      final response = await _dio.get('/reservations');
      final dynamic data = response.data;

      List<dynamic> list;
      if (data is List) {
        list = data;
      } else if (data is Map<String, dynamic> && data.containsKey('data')) {
        list = data['data'];
      } else {
        list = [];
      }

      return list.map((json) => Reservation.fromJson(json)).toList();
    } catch (e) {
      throw Exception('Failed to load reservations: $e');
    }
  }
}
