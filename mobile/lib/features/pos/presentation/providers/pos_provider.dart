import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/repositories/pos_repository.dart';

final posRepositoryProvider = Provider((ref) => PosRepository());

final posSummaryProvider = FutureProvider<Map<String, dynamic>>((ref) async {
  final repository = ref.watch(posRepositoryProvider);
  return repository.getDailySummary();
});

final posTablesProvider =
    FutureProvider<List<Map<String, dynamic>>>((ref) async {
  final repository = ref.watch(posRepositoryProvider);
  return repository.getTables();
});

final posOccupancyProvider = FutureProvider<int>((ref) async {
  final repository = ref.watch(posRepositoryProvider);
  return repository.getOccupancy();
});

final posOrderProvider =
    FutureProvider.family<Map<String, dynamic>, int>((ref, resourceId) async {
  final repository = ref.watch(posRepositoryProvider);
  return repository.getOrder(resourceId);
});

final weeklyTrendProvider =
    FutureProvider<List<Map<String, dynamic>>>((ref) async {
  final repository = ref.watch(posRepositoryProvider);
  return repository.getWeeklyTrend();
});

final topProductsProvider =
    FutureProvider<List<Map<String, dynamic>>>((ref) async {
  final repository = ref.watch(posRepositoryProvider);
  return repository.getTopProducts();
});

final hourlySalesProvider =
    FutureProvider<List<Map<String, dynamic>>>((ref) async {
  final repository = ref.watch(posRepositoryProvider);
  return repository.getHourlySales();
});
