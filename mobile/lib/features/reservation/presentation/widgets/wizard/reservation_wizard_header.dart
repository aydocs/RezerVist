import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../../home/domain/models/business.dart';

class ReservationWizardHeader extends StatelessWidget {
  final int currentStep;
  final VoidCallback onBack;
  final Business business;

  const ReservationWizardHeader({
    super.key,
    required this.currentStep,
    required this.onBack,
    required this.business,
  });

  @override
  Widget build(BuildContext context) {
    final stepTitles = ['Detaylar', 'Hizmetler', 'Ödeme'];

    return Container(
      padding: const EdgeInsets.fromLTRB(8, 12, 16, 0),
      child: Row(
        children: [
          IconButton(
            onPressed: onBack,
            icon: Icon(
              currentStep > 0 ? Icons.arrow_back_rounded : Icons.close_rounded,
              color: const Color(0xFF64748B),
            ),
          ),
          const SizedBox(width: 4),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Rezervasyon Yap',
                  style: GoogleFonts.outfit(
                    fontSize: 20,
                    fontWeight: FontWeight.w800,
                    color: const Color(0xFF1E293B),
                  ),
                ),
                Text(
                  'Adım ${currentStep + 1}/3 — ${stepTitles[currentStep]}',
                  style: GoogleFonts.outfit(
                    fontSize: 13,
                    color: const Color(0xFF94A3B8),
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          ),
          // Business mini badge
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
            decoration: BoxDecoration(
              color: const Color(0xFFF3E8FF),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Text(
              business.name.length > 15
                  ? '${business.name.substring(0, 15)}...'
                  : business.name,
              style: GoogleFonts.outfit(
                fontSize: 11,
                fontWeight: FontWeight.w600,
                color: const Color(0xFF7A1DD1),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
