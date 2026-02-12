// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'user.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

User _$UserFromJson(Map<String, dynamic> json) => User(
      id: (json['id'] as num).toInt(),
      name: json['name'] as String,
      email: json['email'] as String,
      role: json['role'] as String,
      phone: json['phone'] as String?,
      balance: User._parseDecimal(json['balance']),
      points: User._parseInt(json['points']),
      referral_code: json['referral_code'] as String?,
      profile_photo_url: json['profile_photo_url'] as String?,
    );

Map<String, dynamic> _$UserToJson(User instance) => <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'email': instance.email,
      'role': instance.role,
      'phone': instance.phone,
      'balance': instance.balance,
      'points': instance.points,
      'referral_code': instance.referral_code,
      'profile_photo_url': instance.profile_photo_url,
    };
