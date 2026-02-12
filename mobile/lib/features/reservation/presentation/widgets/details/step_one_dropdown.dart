import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class StepOneDropdown extends StatelessWidget {
  final String value;
  final VoidCallback onTap;

  const StepOneDropdown({
    super.key,
    required this.value,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: const Color(0xFFE2E8F0)),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              value,
              style: GoogleFonts.outfit(
                fontSize: 16,
                fontWeight: FontWeight.w700,
                color: const Color(0xFF1E293B),
              ),
            ),
            const Icon(Icons.keyboard_arrow_down_rounded,
                color: Color(0xFF94A3B8)),
          ],
        ),
      ),
    );
  }
}
