import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/repositories/favorite_repository.dart';

final favoriteRepositoryProvider = Provider((ref) => FavoriteRepository());

// Provider to fetch user's favorites
final favoritesProvider =
    FutureProvider<List<Map<String, dynamic>>>((ref) async {
  final repo = ref.watch(favoriteRepositoryProvider);
  return repo.getFavorites();
});

// Set of favorite business IDs for quick lookup
final favoriteIdsProvider = FutureProvider<Set<int>>((ref) async {
  final favorites = await ref.watch(favoritesProvider.future);
  return favorites
      .map((f) => f['business_id'] as int? ?? f['business']?['id'] as int? ?? 0)
      .where((id) => id > 0)
      .toSet();
});
