import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class ReservationLoadingDialog extends StatelessWidget {
  const ReservationLoadingDialog({super.key});

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Container(
        padding: const EdgeInsets.all(32),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          boxShadow: [
            BoxShadow(
              color: const Color(0xFF7A1DD1).withOpacity(0.15),
              blurRadius: 30,
              spreadRadius: 5,
            ),
          ],
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const SizedBox(
              width: 48,
              height: 48,
              child: CircularProgressIndicator(
                color: Color(0xFF7A1DD1),
                strokeWidth: 3,
              ),
            ),
            const SizedBox(height: 20),
            Text(
              'Rezervasyon oluşturuluyor...',
              style: GoogleFonts.outfit(
                fontSize: 14,
                fontWeight: FontWeight.w500,
                color: const Color(0xFF64748B),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
