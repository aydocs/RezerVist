import 'package:dio/dio.dart';
import '../../../../core/network/api_client.dart';
import '../../domain/models/wallet_transaction.dart';

class WalletRepository {
  final Dio _dio = ApiClient.instance;

  Future<List<WalletTransaction>> getTransactions() async {
    try {
      final response = await _dio.get('/wallet/transactions');

      if (response.data != null && response.data['data'] != null) {
        final List<dynamic> data = response.data['data'];
        return data.map((json) => WalletTransaction.fromJson(json)).toList();
      }

      return [];
    } catch (e) {
      rethrow;
    }
  }

  Future<List<Map<String, dynamic>>> searchRecipients(String query) async {
    try {
      final response =
          await _dio.get('/wallet/search', queryParameters: {'q': query});
      if (response.data != null) {
        return List<Map<String, dynamic>>.from(response.data);
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  Future<Map<String, dynamic>> transfer(int recipientId, double amount) async {
    try {
      final response = await _dio.post('/wallet/transfer', data: {
        'recipient_id': recipientId,
        'amount': amount,
      });
      return Map<String, dynamic>.from(response.data);
    } catch (e) {
      rethrow;
    }
  }
}
