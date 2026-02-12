import 'package:json_annotation/json_annotation.dart';

part 'menu.g.dart';

@JsonSerializable()
class Menu {
  final int id;
  @JsonKey(name: 'business_id')
  final int businessId;
  final String name;
  final String? description;
  @JsonKey(fromJson: _parseDecimalRequired)
  final double price;
  final String? image;
  @JsonKey(name: 'is_available')
  final bool? isAvailable;
  // Add other flags if needed

  Menu({
    required this.id,
    required this.businessId,
    required this.name,
    this.description,
    required this.price,
    this.image,
    this.isAvailable,
  });

  static double _parseDecimalRequired(dynamic value) {
    if (value == null) return 0.0;
    if (value is num) return value.toDouble();
    if (value is String) return double.tryParse(value) ?? 0.0;
    return 0.0;
  }

  factory Menu.fromJson(Map<String, dynamic> json) => _$MenuFromJson(json);
  Map<String, dynamic> toJson() => _$MenuToJson(this);
}
