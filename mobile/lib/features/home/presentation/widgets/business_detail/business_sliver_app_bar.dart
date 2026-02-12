import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import 'package:share_plus/share_plus.dart';

// import '../../../providers/business_provider.dart'; // Circular dependency risk? No, provider is top level.
import '../../providers/business_provider.dart';
import '../../../domain/models/business.dart';

class BusinessSliverAppBar extends ConsumerWidget {
  final Business business;
  final bool isScrolled;

  const BusinessSliverAppBar({
    super.key,
    required this.business,
    required this.isScrolled,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return SliverAppBar(
      expandedHeight: 300,
      pinned: true,
      backgroundColor: Colors.white,
      elevation: isScrolled ? 4 : 0,
      shadowColor: Colors.black.withOpacity(0.05),
      title: isScrolled
          ? Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Container(
                  padding: const EdgeInsets.all(6),
                  decoration: BoxDecoration(
                    color: const Color(0xFF7A1DD1),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Icon(Icons.bolt_rounded,
                      color: Colors.white, size: 16),
                ),
                const SizedBox(width: 8),
                Text(
                  business.name,
                  style: GoogleFonts.outfit(
                    color: const Color(0xFF1E293B),
                    fontSize: 18,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ],
            )
          : null,
      centerTitle: true,
      leading: Container(
        margin: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: Colors.white.withOpacity(isScrolled ? 0 : 0.9),
          shape: BoxShape.circle,
        ),
        child: IconButton(
          icon: const Icon(Icons.arrow_back_rounded, color: Colors.black),
          onPressed: () => context.pop(),
        ),
      ),
      actions: [
        Container(
          margin: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: Colors.white.withOpacity(isScrolled ? 0 : 0.9),
            shape: BoxShape.circle,
          ),
          child: IconButton(
            icon: Icon(
              (business.isFavorite ?? false)
                  ? Icons.favorite_rounded
                  : Icons.favorite_border_rounded,
              color: (business.isFavorite ?? false) ? Colors.red : Colors.black,
            ),
            onPressed: () async {
              try {
                await ref
                    .read(businessRepositoryProvider)
                    .toggleFavorite(business.id);
                // Refresh business details to update UI
                ref.invalidate(businessDetailProvider(business.id));

                if (context.mounted) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(
                      content: Text((business.isFavorite ?? false)
                          ? 'Favorilerden çıkarıldı'
                          : 'Favorilere eklendi'),
                      duration: const Duration(seconds: 1),
                    ),
                  );
                }
              } catch (e) {
                if (context.mounted) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text('Hata: $e')),
                  );
                }
              }
            },
          ),
        ),
        Container(
          margin: const EdgeInsets.only(right: 8, top: 8, bottom: 8),
          decoration: BoxDecoration(
            color: Colors.white.withOpacity(isScrolled ? 0 : 0.9),
            shape: BoxShape.circle,
          ),
          child: IconButton(
            icon: const Icon(Icons.share_rounded, color: Colors.black),
            onPressed: () {
              Share.share(
                  '${business.name} - RezerVist\n${business.address ?? ""}\nhttps://rezervist.com/business/${business.id}'); // TODO: Use actual deep link if available
            },
          ),
        ),
      ],
      flexibleSpace: FlexibleSpaceBar(
        background: Stack(
          fit: StackFit.expand,
          children: [
            if (business.imageUrl != null)
              Image.network(
                business.imageUrl!,
                fit: BoxFit.cover,
                errorBuilder: (_, __, ___) =>
                    Container(color: Colors.grey[200]),
              )
            else
              Container(color: const Color(0xFF7A1DD1)),
            // Gradient Overlay
            Container(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                  colors: [
                    Colors.black.withOpacity(0.3),
                    Colors.transparent,
                    Colors.black.withOpacity(0.6),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
