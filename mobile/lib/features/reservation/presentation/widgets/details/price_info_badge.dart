import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class PriceInfoBadge extends StatelessWidget {
  final double pricePerPerson;
  final int totalPeople;

  const PriceInfoBadge({
    super.key,
    required this.pricePerPerson,
    required this.totalPeople,
  });

  @override
  Widget build(BuildContext context) {
    if (pricePerPerson <= 0) return const SizedBox.shrink();

    return Padding(
      padding: const EdgeInsets.only(top: 10),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        decoration: BoxDecoration(
          color: const Color(0xFFF3E8FF),
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: const Color(0xFF7A1DD1).withOpacity(0.15)),
        ),
        child: Row(
          children: [
            const Icon(Icons.info_outline_rounded,
                size: 16, color: Color(0xFF7A1DD1)),
            const SizedBox(width: 8),
            Expanded(
              child: Text(
                'Kişi başı ücret: ${pricePerPerson.toStringAsFixed(0)} TL — '
                'Toplam: ${(pricePerPerson * totalPeople).toStringAsFixed(0)} TL '
                '($totalPeople kişi)',
                style: GoogleFonts.outfit(
                  fontSize: 12,
                  color: const Color(0xFF7A1DD1),
                  fontWeight: FontWeight.w600,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
