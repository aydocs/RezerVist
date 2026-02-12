import 'package:json_annotation/json_annotation.dart';

part 'user.g.dart';

@JsonSerializable()
class User {
  final int id;
  final String name;
  final String email;
  final String role;
  final String? phone;

  @JsonKey(fromJson: _parseDecimal)
  final double? balance;

  @JsonKey(fromJson: _parseInt)
  final int? points;

  final String? referral_code;

  @JsonKey(name: 'profile_photo_url')
  final String? profile_photo_url;

  final bool? notifications_enabled;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    this.phone,
    this.balance,
    this.points,
    this.referral_code,
    this.profile_photo_url,
    this.notifications_enabled,
  });

  static double? _parseDecimal(dynamic value) {
    if (value == null) return null;
    if (value is num) return value.toDouble();
    if (value is String) return double.tryParse(value);
    return null;
  }

  static int? _parseInt(dynamic value) {
    if (value == null) return null;
    if (value is num) return value.toInt();
    if (value is String) return int.tryParse(value);
    return null;
  }

  factory User.fromJson(Map<String, dynamic> json) => _$UserFromJson(json);
  Map<String, dynamic> toJson() => _$UserToJson(this);
}
