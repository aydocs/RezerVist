import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';

class FavoriteCard extends ConsumerWidget {
  final Map<String, dynamic> favorite;
  final VoidCallback onTap;
  final VoidCallback onRemove;

  const FavoriteCard({
    super.key,
    required this.favorite,
    required this.onTap,
    required this.onRemove,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final business = favorite['business'] as Map<String, dynamic>?;
    if (business == null) return const SizedBox.shrink();

    final name = business['name'] ?? 'İşletme';
    final category = business['category'] ?? '';
    final address = business['address'] ?? '';
    // Use full image url if available, otherwise fallback
    final image =
        business['image_url'] as String? ?? business['logo'] as String?;

    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            offset: const Offset(0, 4),
            blurRadius: 12,
          ),
        ],
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Material(
        color: Colors.transparent,
        borderRadius: BorderRadius.circular(16),
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap: onTap,
          child: Padding(
            padding: const EdgeInsets.all(12),
            child: Row(
              children: [
                // Start: Business Image
                Container(
                  width: 80,
                  height: 80,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(12),
                    color: const Color(0xFFF1F5F9),
                  ),
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(12),
                    child: image != null && image.isNotEmpty
                        ? Image.network(
                            image.startsWith('http')
                                ? image
                                : 'http://10.0.2.2:8000/storage/$image', // Simple fix for relative paths in dev
                            fit: BoxFit.cover,
                            errorBuilder: (_, __, ___) => const Icon(
                              Icons.storefront_rounded,
                              color: Color(0xFF94A3B8),
                              size: 32,
                            ),
                          )
                        : const Icon(
                            Icons.storefront_rounded,
                            color: Color(0xFF94A3B8),
                            size: 32,
                          ),
                  ),
                ),
                // End: Business Image

                const SizedBox(width: 16),

                // Info
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        name,
                        style: GoogleFonts.outfit(
                          fontSize: 16,
                          fontWeight: FontWeight.w700,
                          color: const Color(0xFF1E293B),
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                      if (category.isNotEmpty) ...[
                        const SizedBox(height: 4),
                        Text(
                          category,
                          style: GoogleFonts.outfit(
                            fontSize: 13,
                            color: const Color(0xFF7A1DD1),
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ],
                      if (address.isNotEmpty) ...[
                        const SizedBox(height: 4),
                        Text(
                          address,
                          style: GoogleFonts.outfit(
                            fontSize: 12,
                            color: const Color(0xFF64748B),
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ],
                    ],
                  ),
                ),

                // Favorite Button
                IconButton(
                  onPressed: onRemove,
                  icon: const Icon(
                    Icons.favorite_rounded,
                    color: Color(0xFFEF4444),
                    size: 24,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
