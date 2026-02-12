import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import '../providers/favorite_provider.dart';
import '../widgets/favorite_card.dart';

class FavoritesScreen extends ConsumerWidget {
  const FavoritesScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final favoritesAsync = ref.watch(favoritesProvider);

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.white,
        title: Text(
          'Favorilerim',
          style: GoogleFonts.outfit(
            fontSize: 22,
            fontWeight: FontWeight.w700,
            color: const Color(0xFF1E293B),
          ),
        ),
        centerTitle: false,
        iconTheme: const IconThemeData(color: Color(0xFF1E293B)),
      ),
      body: RefreshIndicator(
        color: const Color(0xFF7A1DD1),
        onRefresh: () async {
          ref.invalidate(favoritesProvider);
          // ref.invalidate(favoriteIdsProvider); // Removed if not needed or to simplify
        },
        child: favoritesAsync.when(
          loading: () => const Center(
            child: CircularProgressIndicator(color: Color(0xFF7A1DD1)),
          ),
          error: (e, _) => Center(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Icon(Icons.error_outline, color: Colors.red, size: 48),
                const SizedBox(height: 12),
                Text(
                  'Favoriler yüklenemedi',
                  style: GoogleFonts.outfit(
                      fontSize: 16, color: const Color(0xFF64748B)),
                ),
                const SizedBox(height: 16),
                ElevatedButton(
                  onPressed: () => ref.invalidate(favoritesProvider),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF7A1DD1),
                    foregroundColor: Colors.white,
                  ),
                  child: const Text('Tekrar Dene'),
                ),
              ],
            ),
          ),
          data: (favorites) {
            if (favorites.isEmpty) {
              return Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      Icons.favorite_outline,
                      size: 64,
                      color: const Color(0xFFCBD5E1),
                    ),
                    const SizedBox(height: 16),
                    Text(
                      'Henüz favori eklenmemiş',
                      style: GoogleFonts.outfit(
                        fontSize: 18,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF94A3B8),
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'İşletmeleri keşfet ve favorilerine ekle!',
                      style: GoogleFonts.outfit(
                          fontSize: 14, color: const Color(0xFF94A3B8)),
                    ),
                  ],
                ),
              );
            }

            return ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: favorites.length,
              itemBuilder: (context, index) {
                final favorite = favorites[index];
                final business = favorite['business'] as Map<String, dynamic>?;
                if (business == null) return const SizedBox.shrink();
                final businessId = business['id'] as int;
                final name = business['name'] ?? 'İşletme';

                return FavoriteCard(
                  favorite: favorite,
                  onTap: () {
                    context.push(
                        '/business-detail/$businessId?name=${Uri.encodeComponent(name)}');
                  },
                  onRemove: () async {
                    final repo = ref.read(favoriteRepositoryProvider);
                    await repo.toggleFavorite(businessId);
                    ref.invalidate(favoritesProvider);
                    ref.invalidate(favoriteIdsProvider);
                  },
                );
              },
            );
          },
        ),
      ),
    );
  }
}
