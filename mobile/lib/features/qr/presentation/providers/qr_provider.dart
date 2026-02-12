import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/repositories/qr_repository.dart';

final qrRepositoryProvider = Provider((ref) => QrRepository());

final qrSessionProvider = StateProvider<Map<String, dynamic>?>((ref) => null);

final qrMenuProvider =
    FutureProvider.family<Map<String, dynamic>, String>((ref, token) async {
  final repo = ref.watch(qrRepositoryProvider);
  return repo.getMenu(token);
});

final qrBillProvider =
    FutureProvider.family<Map<String, dynamic>, String>((ref, token) async {
  final repo = ref.watch(qrRepositoryProvider);
  return repo.getBill(token);
});
