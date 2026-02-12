import 'package:json_annotation/json_annotation.dart';
import '../../../../core/network/api_client.dart';

part 'business.g.dart';

@JsonSerializable()
class Business {
  @JsonKey(fromJson: _parseIntCore)
  final int id;
  final String name;
  final String? description;
  final String? address;
  final String? phone;
  @JsonKey(fromJson: _parseDecimal)
  final double? rating;
  @JsonKey(name: 'price_per_person', fromJson: _parseDecimal)
  final double? pricePerPerson;
  @JsonKey(name: 'is_active', fromJson: _parseBool)
  final bool? isActive;
  @JsonKey(name: 'image_url')
  final String? imageUrl;
  final String? city;
  @JsonKey(name: 'is_verified', fromJson: _parseBool)
  final bool? isVerified;
  @JsonKey(name: 'reviews_count', fromJson: _parseInt)
  final int? reviewsCount;
  final List<BusinessImage>? images;
  final List<BusinessTag>? tags;
  @JsonKey(name: 'latitude', fromJson: _parseDecimal)
  final double? latitude;
  @JsonKey(name: 'longitude', fromJson: _parseDecimal)
  final double? longitude;

  @JsonKey(name: 'is_favorite', fromJson: _parseBool)
  final bool? isFavorite;
  final List<String>? amenities;
  @JsonKey(name: 'opening_hours')
  final Map<String, String>? openingHours;
  @JsonKey(name: 'menus', fromJson: _parseMenusToCategories)
  final List<BusinessCategory>? categories;
  @JsonKey(name: 'pricing_type')
  final String? pricingType;
  final List<Review>? reviews;

  Business({
    required this.id,
    required this.name,
    this.description,
    this.address,
    this.phone,
    this.rating,
    this.pricePerPerson,
    this.isActive,
    this.imageUrl,
    this.city,
    this.isVerified,
    this.reviewsCount,
    this.images,
    this.tags,
    this.latitude,
    this.longitude,
    this.isFavorite,
    this.amenities,
    this.openingHours,
    this.categories,
    this.pricingType,
    this.reviews,
  });

  String get fullImageUrl {
    if (imageUrl != null && imageUrl!.startsWith('http')) {
      return imageUrl!;
    }

    // Check first available image in images list
    if (images != null && images!.isNotEmpty) {
      final path = images!.first.imagePath;
      if (path.startsWith('http')) return path;
      return '${ApiClient.baseHost}/storage/$path';
    }

    // Default if provided as relative path in imageUrl
    if (imageUrl != null && imageUrl!.isNotEmpty) {
      return '${ApiClient.baseHost}/storage/$imageUrl';
    }

    return 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=80'; // Fallback
  }

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

  static bool? _parseBool(dynamic value) {
    if (value == null) return null;
    if (value is bool) return value;
    if (value is int) return value == 1;
    if (value is String) {
      return value.toLowerCase() == 'true' || value == '1';
    }
    return null;
  }

  static List<BusinessCategory>? _parseMenusToCategories(dynamic value) {
    if (value == null || value is! List) return null;

    final Map<String, List<MenuItem>> grouped = {};

    for (var item in value) {
      if (item is Map<String, dynamic>) {
        final categoryName = item['category'] as String? ?? 'Diğer';
        if (!grouped.containsKey(categoryName)) {
          grouped[categoryName] = [];
        }

        grouped[categoryName]!.add(MenuItem(
          id: _parseIntCore(item['id']),
          name: item['name'] as String? ?? '',
          description: item['description'] as String?,
          price: _parseDecimal(item['price']) ?? 0.0,
          imageUrl: item['image'] as String?,
        ));
      }
    }

    return grouped.entries.map((entry) {
      return BusinessCategory(
        id: entry.key.hashCode, // Temporary ID from hascode
        name: entry.key,
        items: entry.value,
      );
    }).toList();
  }

  static int _parseIntCore(dynamic value) {
    if (value == null) return 0;
    if (value is num) return value.toInt();
    if (value is String) return int.tryParse(value) ?? 0;
    return 0;
  }

  static String _parseStringCore(dynamic value) {
    if (value == null) return '';
    return value.toString();
  }

  factory Business.fromJson(Map<String, dynamic> json) =>
      _$BusinessFromJson(json);
  Map<String, dynamic> toJson() => _$BusinessToJson(this);
}

@JsonSerializable()
class BusinessImage {
  @JsonKey(fromJson: Business._parseIntCore)
  final int id;
  @JsonKey(name: 'image_path', fromJson: Business._parseStringCore)
  final String imagePath;

  BusinessImage({required this.id, required this.imagePath});

  factory BusinessImage.fromJson(Map<String, dynamic> json) =>
      _$BusinessImageFromJson(json);
  Map<String, dynamic> toJson() => _$BusinessImageToJson(this);
}

@JsonSerializable()
class BusinessCategory {
  @JsonKey(fromJson: Business._parseIntCore)
  final int id;
  @JsonKey(fromJson: Business._parseStringCore)
  final String name;
  final List<MenuItem>? items;

  BusinessCategory({required this.id, required this.name, this.items});

  factory BusinessCategory.fromJson(Map<String, dynamic> json) =>
      _$BusinessCategoryFromJson(json);
  Map<String, dynamic> toJson() => _$BusinessCategoryToJson(this);
}

@JsonSerializable()
class MenuItem {
  @JsonKey(fromJson: _parseIntForMenuItem)
  final int id;
  final String name;
  final String? description;
  @JsonKey(fromJson: _parseDecimalRequired)
  final double price;
  @JsonKey(name: 'image_url')
  final String? imageUrl;

  MenuItem({
    required this.id,
    required this.name,
    this.description,
    required this.price,
    this.imageUrl,
  });

  static double _parseDecimalRequired(dynamic value) {
    if (value == null) return 0.0;
    if (value is num) return value.toDouble();
    if (value is String) return double.tryParse(value) ?? 0.0;
    return 0.0;
  }

  static int _parseIntForMenuItem(dynamic value) {
    if (value == null) return 0;
    if (value is num) return value.toInt();
    if (value is String) return int.tryParse(value) ?? 0;
    return 0;
  }

  factory MenuItem.fromJson(Map<String, dynamic> json) =>
      _$MenuItemFromJson(json);
  Map<String, dynamic> toJson() => _$MenuItemToJson(this);
}

@JsonSerializable()
class BusinessTag {
  @JsonKey(fromJson: Business._parseIntCore)
  final int id;
  @JsonKey(fromJson: Business._parseStringCore)
  final String name;
  @JsonKey(fromJson: Business._parseStringCore)
  final String slug;

  BusinessTag({required this.id, required this.name, required this.slug});

  factory BusinessTag.fromJson(Map<String, dynamic> json) =>
      _$BusinessTagFromJson(json);
  Map<String, dynamic> toJson() => _$BusinessTagToJson(this);
}

@JsonSerializable()
class Review {
  @JsonKey(fromJson: Business._parseIntCore)
  final int id;
  @JsonKey(fromJson: Business._parseDecimal)
  final double? rating;
  final String? comment;
  @JsonKey(name: 'created_at')
  final String? createdAt;
  final SimpleUser? user;

  Review({
    required this.id,
    this.rating,
    this.comment,
    this.createdAt,
    this.user,
  });

  factory Review.fromJson(Map<String, dynamic> json) => _$ReviewFromJson(json);
  Map<String, dynamic> toJson() => _$ReviewToJson(this);
}

@JsonSerializable()
class SimpleUser {
  @JsonKey(fromJson: Business._parseIntCore)
  final int id;
  final String name;
  @JsonKey(name: 'profile_photo_url')
  final String? profilePhotoUrl;

  SimpleUser({required this.id, required this.name, this.profilePhotoUrl});

  factory SimpleUser.fromJson(Map<String, dynamic> json) =>
      _$SimpleUserFromJson(json);
  Map<String, dynamic> toJson() => _$SimpleUserToJson(this);
}
