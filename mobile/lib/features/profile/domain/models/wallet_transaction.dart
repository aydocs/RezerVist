import 'package:intl/intl.dart';

class WalletTransaction {
  final int id;
  final double amount;
  final String type;
  final String status;
  final String description;
  final DateTime createdAt;

  WalletTransaction({
    required this.id,
    required this.amount,
    required this.type,
    required this.status,
    required this.description,
    required this.createdAt,
  });

  factory WalletTransaction.fromJson(Map<String, dynamic> json) {
    return WalletTransaction(
      id: json['id'] is int ? json['id'] : int.parse(json['id'].toString()),
      amount: double.tryParse(json['amount'].toString()) ?? 0.0,
      type: json['type'] ?? 'unknown',
      status: json['status'] ?? 'pending',
      description: json['description'] ?? '',
      createdAt: DateTime.parse(json['created_at']),
    );
  }

  String get formattedAmount {
    final symbol = type == 'topup' ? '+' : '-';
    return '$symbol${amount.toStringAsFixed(2)} TL';
  }

  String get formattedDate {
    return DateFormat('dd.MM.yyyy HH:mm').format(createdAt);
  }

  bool get isCredit => type == 'topup';
}
