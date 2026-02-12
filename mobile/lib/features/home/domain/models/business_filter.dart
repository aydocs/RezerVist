class BusinessFilter {
  final String? query;
  final int? categoryId;
  final double? minPrice;
  final double? maxPrice;
  final double? distance;
  final double? lat;
  final double? lng;

  const BusinessFilter({
    this.query,
    this.categoryId,
    this.minPrice,
    this.maxPrice,
    this.distance,
    this.lat,
    this.lng,
  });

  BusinessFilter copyWith({
    String? query,
    int? categoryId,
    double? minPrice,
    double? maxPrice,
    double? distance,
    double? lat,
    double? lng,
  }) {
    return BusinessFilter(
      query: query ?? this.query,
      categoryId: categoryId ?? this.categoryId,
      minPrice: minPrice ?? this.minPrice,
      maxPrice: maxPrice ?? this.maxPrice,
      distance: distance ?? this.distance,
      lat: lat ?? this.lat,
      lng: lng ?? this.lng,
    );
  }

  BusinessFilter clear() {
    return const BusinessFilter();
  }
}
