import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/repositories/business_repository.dart';
import '../../domain/models/business.dart';
import '../../domain/models/menu.dart';

import '../../domain/models/business_filter.dart';

final businessRepositoryProvider = Provider((ref) => BusinessRepository());

final businessFilterProvider = StateProvider((ref) => const BusinessFilter());

final businessesProvider =
    StateNotifierProvider<BusinessesNotifier, AsyncValue<List<Business>>>(
        (ref) {
  final repository = ref.watch(businessRepositoryProvider);
  final filter = ref.watch(businessFilterProvider);
  return BusinessesNotifier(repository, filter);
});

class BusinessesNotifier extends StateNotifier<AsyncValue<List<Business>>> {
  final BusinessRepository _repository;
  final BusinessFilter _filter;

  BusinessesNotifier(this._repository, this._filter)
      : super(const AsyncLoading()) {
    loadBusinesses();
  }

  Future<void> loadBusinesses() async {
    state = const AsyncLoading();
    try {
      final businesses = await _repository.getBusinesses(
        query: _filter.query,
        categoryId: _filter.categoryId,
        minPrice: _filter.minPrice,
        maxPrice: _filter.maxPrice,
        distance: _filter.distance,
        lat: _filter.lat,
        lng: _filter.lng,
      );
      state = AsyncData(businesses);
    } catch (e, st) {
      state = AsyncError(e, st);
    }
  }
}

final businessDetailProvider =
    FutureProvider.autoDispose.family<Business, int>((ref, id) async {
  final repository = ref.watch(businessRepositoryProvider);
  return repository.getBusiness(id);
});

final businessMenusProvider =
    FutureProvider.autoDispose.family<List<Menu>, int>((ref, businessId) async {
  // Fetch menus directly using the repository method

  final repository = ref.watch(businessRepositoryProvider);
  return repository.getMenus(businessId);
});

final availableSlotsProvider = FutureProvider.autoDispose
    .family<List<String>, ({int businessId, String date})>((ref, params) async {
  final repository = ref.watch(businessRepositoryProvider);
  return repository.getAvailableSlots(params.businessId, params.date);
});
