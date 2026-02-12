import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../domain/models/business.dart';

class BusinessInfoSection extends StatelessWidget {
  final Business business;

  const BusinessInfoSection({
    super.key,
    required this.business,
  });

  @override
  Widget build(BuildContext context) {
    return TweenAnimationBuilder<double>(
      tween: Tween(begin: 0.0, end: 1.0),
      duration: const Duration(milliseconds: 600),
      curve: Curves.easeOutQuart,
      builder: (context, value, child) {
        return Transform.translate(
          offset: Offset(0, 30 * (1 - value)),
          child: Opacity(
            opacity: value,
            child: child,
          ),
        );
      },
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        business.name,
                        style: GoogleFonts.outfit(
                          fontSize: 28,
                          fontWeight: FontWeight.w800,
                          color: const Color(0xFF1E293B),
                          height: 1.2,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.location_on_rounded,
                              size: 16, color: Color(0xFF64748B)),
                          const SizedBox(width: 4),
                          Expanded(
                            child: Text(
                              business.address ?? 'Adres bilgisi yok',
                              style: GoogleFonts.outfit(
                                fontSize: 14,
                                color: const Color(0xFF64748B),
                              ),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                  decoration: BoxDecoration(
                    color: const Color(0xFFF1F5F9),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.star_rounded,
                          color: Color(0xFFF59E0B), size: 18),
                      const SizedBox(width: 4),
                      Text(
                        business.rating?.toString() ?? 'N/A',
                        style: GoogleFonts.outfit(
                          fontSize: 14,
                          fontWeight: FontWeight.w800,
                          color: const Color(0xFF1E293B),
                        ),
                      ),
                      Text(
                        ' (${business.reviewsCount ?? 0})',
                        style: GoogleFonts.outfit(
                          fontSize: 12,
                          color: const Color(0xFF94A3B8),
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 24),
            Text(
              'Hakkında',
              style: GoogleFonts.outfit(
                fontSize: 18,
                fontWeight: FontWeight.w700,
                color: const Color(0xFF1E293B),
              ),
            ),
            const SizedBox(height: 12),
            Text(
              business.description ?? 'Açıklama bulunmuyor.',
              style: GoogleFonts.outfit(
                fontSize: 15,
                color: const Color(0xFF64748B),
                height: 1.6,
              ),
            ),
            const SizedBox(height: 24),
            if (business.amenities != null &&
                business.amenities!.isNotEmpty) ...[
              Text(
                'Olanaklar',
                style: GoogleFonts.outfit(
                  fontSize: 18,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFF1E293B),
                ),
              ),
              const SizedBox(height: 12),
              Wrap(
                spacing: 8,
                runSpacing: 8,
                children: business.amenities!.map((amenity) {
                  return Container(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                    decoration: BoxDecoration(
                      color: const Color(0xFFF8FAFC),
                      borderRadius: BorderRadius.circular(10),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                    ),
                    child: Text(
                      amenity,
                      style: GoogleFonts.outfit(
                        fontSize: 13,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF475569),
                      ),
                    ),
                  );
                }).toList(),
              ),
            ],
          ],
        ),
      ),
    );
  }
}
