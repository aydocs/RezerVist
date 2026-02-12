import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../domain/models/business.dart';

class BusinessMenusSection extends StatelessWidget {
  final Business business;

  const BusinessMenusSection({
    super.key,
    required this.business,
  });

  @override
  Widget build(BuildContext context) {
    // If we have categories from business model, use them
    final categories = business.categories ?? [];
    if (categories.isEmpty) return const SizedBox.shrink();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24),
          child: Text(
            'Menü ve Hizmetler',
            style: GoogleFonts.outfit(
              fontSize: 18,
              fontWeight: FontWeight.w700,
              color: const Color(0xFF1E293B),
            ),
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          height: 180,
          child: ListView.separated(
            padding: const EdgeInsets.symmetric(horizontal: 24),
            scrollDirection: Axis.horizontal,
            itemCount: categories.length,
            separatorBuilder: (_, __) => const SizedBox(width: 16),
            itemBuilder: (context, index) {
              final category = categories[index];
              final firstItem = category.items?.isNotEmpty == true
                  ? category.items!.first
                  : null;
              return TweenAnimationBuilder<double>(
                tween: Tween(begin: 0.0, end: 1.0),
                duration: Duration(milliseconds: 400 + (index * 100)),
                builder: (context, value, child) {
                  return Transform.translate(
                    offset: Offset(20 * (1 - value), 0),
                    child: Opacity(
                      opacity: value,
                      child: child,
                    ),
                  );
                },
                child: GestureDetector(
                  onTap: () {
                    showModalBottomSheet(
                      context: context,
                      isScrollControlled: true,
                      backgroundColor: Colors.transparent,
                      builder: (context) =>
                          _MenuCategoryModal(category: category),
                    );
                  },
                  child: Container(
                    width: 140,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.02),
                          blurRadius: 10,
                          offset: const Offset(0, 4),
                        ),
                      ],
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Expanded(
                          child: Container(
                            decoration: BoxDecoration(
                              borderRadius: const BorderRadius.vertical(
                                  top: Radius.circular(16)),
                              color: Colors.grey[100],
                              image: firstItem?.imageUrl != null
                                  ? DecorationImage(
                                      image: NetworkImage(firstItem!.imageUrl!),
                                      fit: BoxFit.cover,
                                    )
                                  : null,
                            ),
                            child: firstItem?.imageUrl == null
                                ? Center(
                                    child: Icon(Icons.restaurant_menu_rounded,
                                        color: Colors.grey[400]))
                                : null,
                          ),
                        ),
                        Padding(
                          padding: const EdgeInsets.all(12),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                category.name,
                                style: GoogleFonts.outfit(
                                  fontSize: 14,
                                  fontWeight: FontWeight.w700,
                                  color: const Color(0xFF1E293B),
                                ),
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                              ),
                              const SizedBox(height: 2),
                              Text(
                                '${category.items?.length ?? 0} Seçenek',
                                style: GoogleFonts.outfit(
                                  fontSize: 12,
                                  color: const Color(0xFF94A3B8),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              );
            },
          ),
        ),
        const SizedBox(height: 24),
      ],
    );
  }
}

class _MenuCategoryModal extends StatelessWidget {
  final BusinessCategory category;

  const _MenuCategoryModal({required this.category});

  @override
  Widget build(BuildContext context) {
    return Container(
      constraints: BoxConstraints(
        maxHeight: MediaQuery.of(context).size.height * 0.85,
      ),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 40,
            height: 4,
            margin: const EdgeInsets.symmetric(vertical: 12),
            decoration: BoxDecoration(
              color: Colors.grey[300],
              borderRadius: BorderRadius.circular(2),
            ),
          ),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
            child: Row(
              children: [
                Expanded(
                  child: Text(
                    category.name,
                    style: GoogleFonts.outfit(
                      fontSize: 20,
                      fontWeight: FontWeight.w700,
                      color: const Color(0xFF1E293B),
                    ),
                  ),
                ),
                IconButton(
                  onPressed: () => Navigator.pop(context),
                  icon: const Icon(Icons.close_rounded),
                ),
              ],
            ),
          ),
          const Divider(height: 1),
          if (category.items?.isEmpty ?? true)
            Padding(
              padding: const EdgeInsets.all(32),
              child: Text(
                'Bu kategoride henüz ürün yok.',
                style: GoogleFonts.outfit(
                  color: const Color(0xFF64748B),
                  fontSize: 16,
                ),
                textAlign: TextAlign.center,
              ),
            )
          else
            Flexible(
              child: ListView.separated(
                padding: const EdgeInsets.all(20),
                shrinkWrap: true,
                itemCount: category.items!.length,
                separatorBuilder: (_, __) => const SizedBox(height: 16),
                itemBuilder: (context, index) {
                  final item = category.items![index];
                  return Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        width: 80,
                        height: 80,
                        decoration: BoxDecoration(
                          color: Colors.grey[100],
                          borderRadius: BorderRadius.circular(12),
                          image: item.imageUrl != null
                              ? DecorationImage(
                                  image: NetworkImage(item.imageUrl!),
                                  fit: BoxFit.cover,
                                )
                              : null,
                        ),
                        child: item.imageUrl == null
                            ? Icon(Icons.restaurant_menu_rounded,
                                color: Colors.grey[400])
                            : null,
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              item.name,
                              style: GoogleFonts.outfit(
                                fontSize: 16,
                                fontWeight: FontWeight.w600,
                                color: const Color(0xFF1E293B),
                              ),
                            ),
                            if (item.description != null) ...[
                              const SizedBox(height: 4),
                              Text(
                                item.description!,
                                style: GoogleFonts.outfit(
                                  fontSize: 13,
                                  color: const Color(0xFF64748B),
                                ),
                                maxLines: 2,
                                overflow: TextOverflow.ellipsis,
                              ),
                            ],
                            const SizedBox(height: 8),
                            Text(
                              '${item.price.toStringAsFixed(2)} TL',
                              style: GoogleFonts.outfit(
                                fontSize: 16,
                                fontWeight: FontWeight.w700,
                                color: const Color(0xFF7A1DD1),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  );
                },
              ),
            ),
        ],
      ),
    );
  }
}
