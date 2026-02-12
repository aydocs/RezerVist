import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class SettingsSection extends StatelessWidget {
  final String title;
  final List<Widget> children;

  const SettingsSection({
    super.key,
    required this.title,
    required this.children,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: GoogleFonts.outfit(
            fontSize: 13,
            fontWeight: FontWeight.w700,
            color: const Color(0xFF94A3B8),
            letterSpacing: 0.5,
          ),
        ),
        const SizedBox(height: 10),
        ...children,
      ],
    );
  }
}
