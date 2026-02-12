import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/repositories/analytics_repository.dart';

final analyticsRepositoryProvider = Provider((ref) => AnalyticsRepository());

final weeklyTrendProvider = FutureProvider((ref) async {
  final repository = ref.watch(analyticsRepositoryProvider);
  return repository.getWeeklyTrend();
});

final topProductsProvider = FutureProvider((ref) async {
  final repository = ref.watch(analyticsRepositoryProvider);
  return repository.getTopProducts();
});
