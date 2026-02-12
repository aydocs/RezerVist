import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class ReservationWizardBottomBar extends StatelessWidget {
  final double grandTotal;
  final int currentStep;
  final VoidCallback onPrevStep;
  final VoidCallback onNextStep;
  final VoidCallback onSubmit;

  const ReservationWizardBottomBar({
    super.key,
    required this.grandTotal,
    required this.currentStep,
    required this.onPrevStep,
    required this.onNextStep,
    required this.onSubmit,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 12),
      decoration: BoxDecoration(
        color: Colors.white,
        border: Border(
          top: BorderSide(color: Colors.grey.shade100),
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.04),
            blurRadius: 10,
            offset: const Offset(0, -4),
          ),
        ],
      ),
      child: SafeArea(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            // Running total
            if (grandTotal > 0)
              Padding(
                padding: const EdgeInsets.only(bottom: 10),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'Tahmini Toplam',
                      style: GoogleFonts.outfit(
                        fontSize: 13,
                        color: const Color(0xFF64748B),
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                    Text(
                      '${grandTotal.toStringAsFixed(0)} TL',
                      style: GoogleFonts.outfit(
                        fontSize: 20,
                        fontWeight: FontWeight.w800,
                        color: const Color(0xFF7A1DD1),
                      ),
                    ),
                  ],
                ),
              ),
            // Navigation buttons
            Row(
              children: [
                // Back button (visible on step 1+)
                if (currentStep > 0)
                  Expanded(
                    flex: 2,
                    child: SizedBox(
                      height: 52,
                      child: OutlinedButton.icon(
                        onPressed: onPrevStep,
                        icon: const Icon(Icons.arrow_back_rounded, size: 18),
                        label: Text(
                          'Geri',
                          style: GoogleFonts.outfit(
                            fontSize: 15,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        style: OutlinedButton.styleFrom(
                          foregroundColor: const Color(0xFF64748B),
                          side: const BorderSide(color: Color(0xFFE2E8F0)),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(14),
                          ),
                        ),
                      ),
                    ),
                  ),
                if (currentStep > 0) const SizedBox(width: 12),
                // Forward / Submit button
                Expanded(
                  flex: 3,
                  child: SizedBox(
                    height: 52,
                    child: ElevatedButton(
                      onPressed: currentStep < 2 ? onNextStep : onSubmit,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: currentStep < 2
                            ? const Color(0xFF7A1DD1)
                            : const Color(0xFF22C55E),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(14),
                        ),
                        elevation: 0,
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Text(
                            currentStep < 2
                                ? 'Devam Et'
                                : 'Rezervasyonu Tamamla',
                            style: GoogleFonts.outfit(
                              fontSize: 15,
                              fontWeight: FontWeight.w700,
                              color: Colors.white,
                            ),
                          ),
                          if (currentStep < 2) ...[
                            const SizedBox(width: 6),
                            const Icon(Icons.arrow_forward_rounded,
                                size: 18, color: Colors.white),
                          ],
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
