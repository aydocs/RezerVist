import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class StepOneSectionLabel extends StatelessWidget {
  final String label;

  const StepOneSectionLabel(this.label, {super.key});

  @override
  Widget build(BuildContext context) {
    return Text(
      label,
      style: GoogleFonts.outfit(
        fontSize: 12,
        fontWeight: FontWeight.w700,
        color: const Color(0xFF94A3B8),
        letterSpacing: 1,
      ),
    );
  }
}
