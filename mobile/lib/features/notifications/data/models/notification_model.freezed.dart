// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'notification_model.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
    'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models');

NotificationModel _$NotificationModelFromJson(Map<String, dynamic> json) {
  return _NotificationModel.fromJson(json);
}

/// @nodoc
mixin _$NotificationModel {
  String get id => throw _privateConstructorUsedError;
  String get type => throw _privateConstructorUsedError;
  Map<String, dynamic> get data => throw _privateConstructorUsedError;
  @JsonKey(name: 'read_at')
  DateTime? get readAt => throw _privateConstructorUsedError;
  @JsonKey(name: 'created_at')
  DateTime get createdAt => throw _privateConstructorUsedError;

  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;
  @JsonKey(ignore: true)
  $NotificationModelCopyWith<NotificationModel> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $NotificationModelCopyWith<$Res> {
  factory $NotificationModelCopyWith(
          NotificationModel value, $Res Function(NotificationModel) then) =
      _$NotificationModelCopyWithImpl<$Res, NotificationModel>;
  @useResult
  $Res call(
      {String id,
      String type,
      Map<String, dynamic> data,
      @JsonKey(name: 'read_at') DateTime? readAt,
      @JsonKey(name: 'created_at') DateTime createdAt});
}

/// @nodoc
class _$NotificationModelCopyWithImpl<$Res, $Val extends NotificationModel>
    implements $NotificationModelCopyWith<$Res> {
  _$NotificationModelCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? type = null,
    Object? data = null,
    Object? readAt = freezed,
    Object? createdAt = null,
  }) {
    return _then(_value.copyWith(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      type: null == type
          ? _value.type
          : type // ignore: cast_nullable_to_non_nullable
              as String,
      data: null == data
          ? _value.data
          : data // ignore: cast_nullable_to_non_nullable
              as Map<String, dynamic>,
      readAt: freezed == readAt
          ? _value.readAt
          : readAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      createdAt: null == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime,
    ) as $Val);
  }
}

/// @nodoc
abstract class _$$NotificationModelImplCopyWith<$Res>
    implements $NotificationModelCopyWith<$Res> {
  factory _$$NotificationModelImplCopyWith(_$NotificationModelImpl value,
          $Res Function(_$NotificationModelImpl) then) =
      __$$NotificationModelImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call(
      {String id,
      String type,
      Map<String, dynamic> data,
      @JsonKey(name: 'read_at') DateTime? readAt,
      @JsonKey(name: 'created_at') DateTime createdAt});
}

/// @nodoc
class __$$NotificationModelImplCopyWithImpl<$Res>
    extends _$NotificationModelCopyWithImpl<$Res, _$NotificationModelImpl>
    implements _$$NotificationModelImplCopyWith<$Res> {
  __$$NotificationModelImplCopyWithImpl(_$NotificationModelImpl _value,
      $Res Function(_$NotificationModelImpl) _then)
      : super(_value, _then);

  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? type = null,
    Object? data = null,
    Object? readAt = freezed,
    Object? createdAt = null,
  }) {
    return _then(_$NotificationModelImpl(
      id: null == id
          ? _value.id
          : id // ignore: cast_nullable_to_non_nullable
              as String,
      type: null == type
          ? _value.type
          : type // ignore: cast_nullable_to_non_nullable
              as String,
      data: null == data
          ? _value._data
          : data // ignore: cast_nullable_to_non_nullable
              as Map<String, dynamic>,
      readAt: freezed == readAt
          ? _value.readAt
          : readAt // ignore: cast_nullable_to_non_nullable
              as DateTime?,
      createdAt: null == createdAt
          ? _value.createdAt
          : createdAt // ignore: cast_nullable_to_non_nullable
              as DateTime,
    ));
  }
}

/// @nodoc
@JsonSerializable()
class _$NotificationModelImpl implements _NotificationModel {
  const _$NotificationModelImpl(
      {required this.id,
      required this.type,
      required final Map<String, dynamic> data,
      @JsonKey(name: 'read_at') this.readAt,
      @JsonKey(name: 'created_at') required this.createdAt})
      : _data = data;

  factory _$NotificationModelImpl.fromJson(Map<String, dynamic> json) =>
      _$$NotificationModelImplFromJson(json);

  @override
  final String id;
  @override
  final String type;
  final Map<String, dynamic> _data;
  @override
  Map<String, dynamic> get data {
    if (_data is EqualUnmodifiableMapView) return _data;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableMapView(_data);
  }

  @override
  @JsonKey(name: 'read_at')
  final DateTime? readAt;
  @override
  @JsonKey(name: 'created_at')
  final DateTime createdAt;

  @override
  String toString() {
    return 'NotificationModel(id: $id, type: $type, data: $data, readAt: $readAt, createdAt: $createdAt)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$NotificationModelImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.type, type) || other.type == type) &&
            const DeepCollectionEquality().equals(other._data, _data) &&
            (identical(other.readAt, readAt) || other.readAt == readAt) &&
            (identical(other.createdAt, createdAt) ||
                other.createdAt == createdAt));
  }

  @JsonKey(ignore: true)
  @override
  int get hashCode => Object.hash(runtimeType, id, type,
      const DeepCollectionEquality().hash(_data), readAt, createdAt);

  @JsonKey(ignore: true)
  @override
  @pragma('vm:prefer-inline')
  _$$NotificationModelImplCopyWith<_$NotificationModelImpl> get copyWith =>
      __$$NotificationModelImplCopyWithImpl<_$NotificationModelImpl>(
          this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$NotificationModelImplToJson(
      this,
    );
  }
}

abstract class _NotificationModel implements NotificationModel {
  const factory _NotificationModel(
          {required final String id,
          required final String type,
          required final Map<String, dynamic> data,
          @JsonKey(name: 'read_at') final DateTime? readAt,
          @JsonKey(name: 'created_at') required final DateTime createdAt}) =
      _$NotificationModelImpl;

  factory _NotificationModel.fromJson(Map<String, dynamic> json) =
      _$NotificationModelImpl.fromJson;

  @override
  String get id;
  @override
  String get type;
  @override
  Map<String, dynamic> get data;
  @override
  @JsonKey(name: 'read_at')
  DateTime? get readAt;
  @override
  @JsonKey(name: 'created_at')
  DateTime get createdAt;
  @override
  @JsonKey(ignore: true)
  _$$NotificationModelImplCopyWith<_$NotificationModelImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
