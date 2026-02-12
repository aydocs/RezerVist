import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../domain/models/business.dart';

class BusinessCard extends StatelessWidget {
  final Business business;
  final VoidCallback onTap;

  const BusinessCard({super.key, required this.business, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(28),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.06),
            blurRadius: 25,
            offset: const Offset(0, 12),
          ),
        ],
      ),
      clipBehavior: Clip.antiAlias,
      child: InkWell(
        onTap: onTap,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image Section with Badges
            Stack(
              children: [
                Container(
                  height: 180,
                  width: double.infinity,
                  decoration: const BoxDecoration(color: Color(0xFFF1F5F9)),
                  child: Image.network(
                    business.fullImageUrl,
                    fit: BoxFit.cover,
                    errorBuilder: (context, error, stackTrace) {
                      return Container(
                        color: const Color(0xFFF1F5F9),
                        child: const Center(
                          child: Icon(Icons.image_not_supported_rounded,
                              color: Color(0xFF94A3B8), size: 48),
                        ),
                      );
                    },
                    loadingBuilder: (context, child, loadingProgress) {
                      if (loadingProgress == null) return child;
                      return Container(
                        color: const Color(0xFFF1F5F9),
                        child: const Center(
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            color: Color(0xFF7A1DD1),
                          ),
                        ),
                      );
                    },
                  ),
                ),
                // Rating - Top Right (Orange)
                Positioned(
                  top: 16,
                  right: 16,
                  child: Container(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                    decoration: BoxDecoration(
                      color: const Color(0xFFF59E0B), // Orange/Amber
                      borderRadius: BorderRadius.circular(100),
                      boxShadow: [
                        BoxShadow(
                            color: Colors.black.withOpacity(0.15),
                            blurRadius: 10,
                            offset: const Offset(0, 4)),
                      ],
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.star_rounded,
                            color: Colors.white, size: 14),
                        const SizedBox(width: 4),
                        Text(
                          '${business.rating ?? 5.0}/1',
                          style: GoogleFonts.outfit(
                              fontSize: 12,
                              fontWeight: FontWeight.w900,
                              color: Colors.white),
                        ),
                      ],
                    ),
                  ),
                ),
                // NEW Badge - Below Rating or Top Left
                Positioned(
                  top: 54,
                  right: 16,
                  child: Container(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                    decoration: BoxDecoration(
                      color: const Color(0xFF10B981), // Emerald/Green
                      borderRadius: BorderRadius.circular(100),
                    ),
                    child: Text(
                      'YENİ',
                      style: GoogleFonts.outfit(
                          fontSize: 10,
                          fontWeight: FontWeight.w900,
                          color: Colors.white,
                          letterSpacing: 0.5),
                    ),
                  ),
                ),
              ],
            ),
            // Content
            Padding(
              padding: const EdgeInsets.all(24.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Category Tag (Purple)
                  Container(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: const Color(0xFF7A1DD1).withOpacity(0.1),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(
                      'ÖNE ÇIKAN',
                      style: GoogleFonts.outfit(
                          color: const Color(0xFF7A1DD1),
                          fontSize: 10,
                          fontWeight: FontWeight.w900,
                          letterSpacing: 0.5),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    business.name,
                    style: GoogleFonts.outfit(
                      fontSize: 20,
                      fontWeight: FontWeight.w900,
                      color: const Color(0xFF0F172A),
                      letterSpacing: -0.5,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    business.description ??
                        'Şehrin en iyi noktalarından biri olarak sizlere eşsiz bir deneyim sunuyoruz.',
                    style: GoogleFonts.outfit(
                      color: const Color(0xFF64748B),
                      fontSize: 14,
                      height: 1.5,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 20),
                  const Divider(height: 1, color: Color(0xFFF1F5F9)),
                  const SizedBox(height: 16),
                  Row(
                    children: [
                      const Icon(Icons.location_on_rounded,
                          size: 16, color: Color(0xFF94A3B8)),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          business.address ?? 'Gaziantep, Şehitkamil',
                          style: GoogleFonts.outfit(
                              color: const Color(0xFF94A3B8),
                              fontSize: 13,
                              fontWeight: FontWeight.w600),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
