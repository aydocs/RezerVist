import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class ReservationListEmptyState extends StatelessWidget {
  const ReservationListEmptyState({super.key});

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.calendar_today_outlined,
            size: 64,
            color: Colors.grey.withOpacity(0.3),
          ),
          const SizedBox(height: 16),
          Text(
            'Henüz rezervasyonunuz yok',
            style: GoogleFonts.outfit(
              fontSize: 18,
              fontWeight: FontWeight.w600,
              color: Colors.black54,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'İşletmeleri keşfet ve hemen rezervasyon yap!',
            style: GoogleFonts.outfit(
              fontSize: 14,
              color: Colors.black38,
            ),
          ),
        ],
      ),
    );
  }
}
