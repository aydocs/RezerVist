import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class ServiceSubtotalBanner extends StatelessWidget {
  final int itemCount;
  final double subtotal;

  const ServiceSubtotalBanner({
    super.key,
    required this.itemCount,
    required this.subtotal,
  });

  @override
  Widget build(BuildContext context) {
    if (itemCount <= 0) return const SizedBox.shrink();

    return Container(
      margin: const EdgeInsets.fromLTRB(16, 12, 16, 0),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF7A1DD1), Color(0xFF9B4DFF)],
        ),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        children: [
          const Icon(Icons.shopping_bag_rounded, color: Colors.white, size: 18),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              '$itemCount ürün seçildi',
              style: GoogleFonts.outfit(
                color: Colors.white,
                fontSize: 13,
                fontWeight: FontWeight.w600,
              ),
            ),
          ),
          Text(
            '${subtotal.toStringAsFixed(0)} TL',
            style: GoogleFonts.outfit(
              color: Colors.white,
              fontSize: 16,
              fontWeight: FontWeight.w800,
            ),
          ),
        ],
      ),
    );
  }
}
