import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class HomeInteractiveField extends StatelessWidget {
  final String label;
  final String hint;
  final IconData icon;
  final Color iconColor;
  final VoidCallback onTap;

  const HomeInteractiveField({
    super.key,
    required this.label,
    required this.hint,
    required this.icon,
    required this.iconColor,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 20),
        decoration: BoxDecoration(
          border: Border.all(color: const Color(0xFFF1F5F9)),
          borderRadius: BorderRadius.circular(20),
        ),
        child: Row(
          children: [
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    label,
                    style: GoogleFonts.outfit(
                      fontSize: 11,
                      fontWeight: FontWeight.w800,
                      color: const Color(0xFF94A3B8),
                      letterSpacing: 0.5,
                    ),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    hint,
                    style: GoogleFonts.outfit(
                      fontSize: 16,
                      fontWeight: FontWeight.w700,
                      color: const Color(0xFF1E293B),
                    ),
                  ),
                ],
              ),
            ),
            Icon(icon, color: iconColor, size: 24),
          ],
        ),
      ),
    );
  }
}
