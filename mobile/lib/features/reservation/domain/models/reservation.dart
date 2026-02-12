class Reservation {
  final int id;
  final int businessId;
  final int userId;
  final String? startTime;
  final String? endTime;
  final int guestCount;
  final String? note;
  final String status;
  final double? price;
  final double? totalAmount;
  final String? businessName;
  final String? businessImage;
  final String? resourceName;

  Reservation({
    required this.id,
    required this.businessId,
    required this.userId,
    this.startTime,
    this.endTime,
    required this.guestCount,
    this.note,
    required this.status,
    this.price,
    this.totalAmount,
    this.businessName,
    this.businessImage,
    this.resourceName,
  });

  factory Reservation.fromJson(Map<String, dynamic> json) {
    final business = json['business'] as Map<String, dynamic>?;
    final resource = json['resource'] as Map<String, dynamic>?;

    return Reservation(
      id: _safeInt(json['id']),
      businessId: _safeInt(json['business_id']),
      userId: _safeInt(json['user_id']),
      startTime: json['start_time'],
      endTime: json['end_time'],
      guestCount: _safeInt(json['guest_count'], defaultValue: 1),
      note: json['note'],
      status: json['status'] ?? 'pending',
      price: json['price'] != null
          ? double.tryParse(json['price'].toString())
          : null,
      totalAmount: json['total_amount'] != null
          ? double.tryParse(json['total_amount'].toString())
          : null,
      businessName: business?['name'],
      businessImage: business?['logo'],
      resourceName: resource?['name'],
    );
  }

  static int _safeInt(dynamic value, {int defaultValue = 0}) {
    if (value == null) return defaultValue;
    if (value is int) return value;
    if (value is String) return int.tryParse(value) ?? defaultValue;
    if (value is num) return value.toInt();
    return defaultValue;
  }
}
