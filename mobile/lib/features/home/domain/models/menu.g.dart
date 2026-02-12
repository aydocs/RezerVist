// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'menu.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

Menu _$MenuFromJson(Map<String, dynamic> json) => Menu(
      id: (json['id'] as num).toInt(),
      businessId: (json['business_id'] as num).toInt(),
      name: json['name'] as String,
      description: json['description'] as String?,
      price: Menu._parseDecimalRequired(json['price']),
      image: json['image'] as String?,
      isAvailable: json['is_available'] as bool?,
    );

Map<String, dynamic> _$MenuToJson(Menu instance) => <String, dynamic>{
      'id': instance.id,
      'business_id': instance.businessId,
      'name': instance.name,
      'description': instance.description,
      'price': instance.price,
      'image': instance.image,
      'is_available': instance.isAvailable,
    };
