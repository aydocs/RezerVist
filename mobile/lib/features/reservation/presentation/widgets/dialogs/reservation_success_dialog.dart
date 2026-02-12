import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class ReservationSuccessDialog extends StatelessWidget {
  final String title;
  final String content;
  final VoidCallback onConfirm;

  const ReservationSuccessDialog({
    super.key,
    required this.title,
    required this.content,
    required this.onConfirm,
  });

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      title: Row(
        children: [
          const Icon(Icons.check_circle_rounded,
              color: Color(0xFF22C55E), size: 28),
          const SizedBox(width: 12),
          Text(title, style: GoogleFonts.outfit(fontWeight: FontWeight.w700)),
        ],
      ),
      content: Text(content, style: GoogleFonts.outfit()),
      actions: [
        TextButton(
          onPressed: onConfirm,
          child: Text(
            'Tamam',
            style: GoogleFonts.outfit(
              color: const Color(0xFF7A1DD1),
              fontWeight: FontWeight.w600,
            ),
          ),
        ),
      ],
    );
  }
}
