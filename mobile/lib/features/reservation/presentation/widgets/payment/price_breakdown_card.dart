import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class ServiceItem {
  final String name;
  final int quantity;
  final double unitPrice;
  final double totalPrice;

  ServiceItem({
    required this.name,
    required this.quantity,
    required this.unitPrice,
    required this.totalPrice,
  });
}

class PriceBreakdownCard extends StatelessWidget {
  final double perPersonPrice;
  final double perPersonTotal;
  final int guestCount;
  final List<ServiceItem> serviceItems;
  final double servicesTotal;
  final double grandTotal;

  const PriceBreakdownCard({
    super.key,
    required this.perPersonPrice,
    required this.perPersonTotal,
    required this.guestCount,
    required this.serviceItems,
    required this.servicesTotal,
    required this.grandTotal,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE2E8F0)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.03),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.receipt_long_rounded,
                  color: Color(0xFF7A1DD1), size: 20),
              const SizedBox(width: 8),
              Text(
                'Fiyat Detayı',
                style: GoogleFonts.outfit(
                  fontSize: 16,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFF1E293B),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),

          // Per-person cost
          if (perPersonPrice > 0) ...[
            _buildPriceRow(
              label: 'Kişi Başı Ücret',
              detail:
                  '${perPersonPrice.toStringAsFixed(0)} TL × $guestCount kişi',
              amount: perPersonTotal,
              color: const Color(0xFF3B82F6),
            ),
            const SizedBox(height: 8),
          ],

          // Service items
          if (serviceItems.isNotEmpty) ...[
            if (perPersonPrice > 0)
              const Padding(
                padding: EdgeInsets.symmetric(vertical: 6),
                child: Divider(height: 1, color: Color(0xFFF1F5F9)),
              ),
            ...serviceItems.map((item) => Padding(
                  padding: const EdgeInsets.only(bottom: 6),
                  child: _buildPriceRow(
                    label: item.name,
                    detail:
                        '${item.quantity}x ${item.unitPrice.toStringAsFixed(0)} TL',
                    amount: item.totalPrice,
                  ),
                )),
          ],

          if (serviceItems.isEmpty && perPersonPrice <= 0)
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 8),
              child: Text(
                'Henüz ücret kalemi bulunmuyor.',
                style: GoogleFonts.outfit(
                  color: const Color(0xFF94A3B8),
                  fontSize: 13,
                  fontStyle: FontStyle.italic,
                ),
              ),
            ),

          // Divider before total
          const Padding(
            padding: EdgeInsets.symmetric(vertical: 10),
            child: Divider(height: 1, color: Color(0xFFE2E8F0)),
          ),

          // Grand Total
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Toplam Tutar',
                style: GoogleFonts.outfit(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF1E293B),
                ),
              ),
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                decoration: BoxDecoration(
                  color: const Color(0xFFF3E8FF),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Text(
                  '${grandTotal.toStringAsFixed(0)} TL',
                  style: GoogleFonts.outfit(
                    fontSize: 22,
                    fontWeight: FontWeight.w900,
                    color: const Color(0xFF7A1DD1),
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildPriceRow({
    required String label,
    required String detail,
    required double amount,
    Color? color,
  }) {
    return Row(
      children: [
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                label,
                style: GoogleFonts.outfit(
                  fontSize: 13,
                  fontWeight: FontWeight.w500,
                  color: color ?? const Color(0xFF475569),
                ),
              ),
              Text(
                detail,
                style: GoogleFonts.outfit(
                  fontSize: 11,
                  color: const Color(0xFF94A3B8),
                ),
              ),
            ],
          ),
        ),
        Text(
          '${amount.toStringAsFixed(0)} TL',
          style: GoogleFonts.outfit(
            fontSize: 14,
            fontWeight: FontWeight.w700,
            color: const Color(0xFF1E293B),
          ),
        ),
      ],
    );
  }
}
