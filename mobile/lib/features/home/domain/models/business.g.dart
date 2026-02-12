// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'business.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

Business _$BusinessFromJson(Map<String, dynamic> json) => Business(
      id: Business._parseIntCore(json['id']),
      name: json['name'] as String,
      description: json['description'] as String?,
      address: json['address'] as String?,
      phone: json['phone'] as String?,
      rating: Business._parseDecimal(json['rating']),
      pricePerPerson: Business._parseDecimal(json['price_per_person']),
      isActive: Business._parseBool(json['is_active']),
      imageUrl: json['image_url'] as String?,
      city: json['city'] as String?,
      isVerified: Business._parseBool(json['is_verified']),
      reviewsCount: Business._parseInt(json['reviews_count']),
      images: (json['images'] as List<dynamic>?)
          ?.map((e) => BusinessImage.fromJson(e as Map<String, dynamic>))
          .toList(),
      tags: (json['tags'] as List<dynamic>?)
          ?.map((e) => BusinessTag.fromJson(e as Map<String, dynamic>))
          .toList(),
      latitude: Business._parseDecimal(json['latitude']),
      longitude: Business._parseDecimal(json['longitude']),
      isFavorite: Business._parseBool(json['is_favorite']),
      amenities: (json['amenities'] as List<dynamic>?)
          ?.map((e) => e as String)
          .toList(),
      openingHours: (json['opening_hours'] as Map<String, dynamic>?)?.map(
        (k, e) => MapEntry(k, e as String),
      ),
      categories: Business._parseMenusToCategories(json['menus']),
      pricingType: json['pricing_type'] as String?,
      reviews: (json['reviews'] as List<dynamic>?)
          ?.map((e) => Review.fromJson(e as Map<String, dynamic>))
          .toList(),
    );

Map<String, dynamic> _$BusinessToJson(Business instance) => <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'description': instance.description,
      'address': instance.address,
      'phone': instance.phone,
      'rating': instance.rating,
      'price_per_person': instance.pricePerPerson,
      'is_active': instance.isActive,
      'image_url': instance.imageUrl,
      'city': instance.city,
      'is_verified': instance.isVerified,
      'reviews_count': instance.reviewsCount,
      'images': instance.images,
      'tags': instance.tags,
      'latitude': instance.latitude,
      'longitude': instance.longitude,
      'is_favorite': instance.isFavorite,
      'amenities': instance.amenities,
      'opening_hours': instance.openingHours,
      'menus': instance.categories,
      'pricing_type': instance.pricingType,
      'reviews': instance.reviews,
    };

BusinessImage _$BusinessImageFromJson(Map<String, dynamic> json) =>
    BusinessImage(
      id: Business._parseIntCore(json['id']),
      imagePath: Business._parseStringCore(json['image_path']),
    );

Map<String, dynamic> _$BusinessImageToJson(BusinessImage instance) =>
    <String, dynamic>{
      'id': instance.id,
      'image_path': instance.imagePath,
    };

BusinessCategory _$BusinessCategoryFromJson(Map<String, dynamic> json) =>
    BusinessCategory(
      id: Business._parseIntCore(json['id']),
      name: Business._parseStringCore(json['name']),
      items: (json['items'] as List<dynamic>?)
          ?.map((e) => MenuItem.fromJson(e as Map<String, dynamic>))
          .toList(),
    );

Map<String, dynamic> _$BusinessCategoryToJson(BusinessCategory instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'items': instance.items,
    };

MenuItem _$MenuItemFromJson(Map<String, dynamic> json) => MenuItem(
      id: MenuItem._parseIntForMenuItem(json['id']),
      name: json['name'] as String,
      description: json['description'] as String?,
      price: MenuItem._parseDecimalRequired(json['price']),
      imageUrl: json['image_url'] as String?,
    );

Map<String, dynamic> _$MenuItemToJson(MenuItem instance) => <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'description': instance.description,
      'price': instance.price,
      'image_url': instance.imageUrl,
    };

BusinessTag _$BusinessTagFromJson(Map<String, dynamic> json) => BusinessTag(
      id: Business._parseIntCore(json['id']),
      name: Business._parseStringCore(json['name']),
      slug: Business._parseStringCore(json['slug']),
    );

Map<String, dynamic> _$BusinessTagToJson(BusinessTag instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'slug': instance.slug,
    };

Review _$ReviewFromJson(Map<String, dynamic> json) => Review(
      id: Business._parseIntCore(json['id']),
      rating: Business._parseDecimal(json['rating']),
      comment: json['comment'] as String?,
      createdAt: json['created_at'] as String?,
      user: json['user'] == null
          ? null
          : SimpleUser.fromJson(json['user'] as Map<String, dynamic>),
    );

Map<String, dynamic> _$ReviewToJson(Review instance) => <String, dynamic>{
      'id': instance.id,
      'rating': instance.rating,
      'comment': instance.comment,
      'created_at': instance.createdAt,
      'user': instance.user,
    };

SimpleUser _$SimpleUserFromJson(Map<String, dynamic> json) => SimpleUser(
      id: Business._parseIntCore(json['id']),
      name: json['name'] as String,
      profilePhotoUrl: json['profile_photo_url'] as String?,
    );

Map<String, dynamic> _$SimpleUserToJson(SimpleUser instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'profile_photo_url': instance.profilePhotoUrl,
    };
