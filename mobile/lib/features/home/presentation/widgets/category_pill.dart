import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class CategoryPill extends StatelessWidget {
  final String name;
  final IconData icon;
  final bool isActive;
  final VoidCallback? onTap;

  const CategoryPill({
    super.key,
    required this.name,
    required this.icon,
    required this.isActive,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(right: 12),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: onTap,
          borderRadius: BorderRadius.circular(16),
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            decoration: BoxDecoration(
              color: isActive ? const Color(0xFF7A1DD1) : Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(
                color: isActive
                    ? const Color(0xFF7A1DD1)
                    : const Color(0xFFE2E8F0),
              ),
              boxShadow: isActive
                  ? [
                      BoxShadow(
                        color: const Color(0xFF7A1DD1).withOpacity(0.3),
                        blurRadius: 12,
                        offset: const Offset(0, 4),
                      ),
                    ]
                  : [
                      BoxShadow(
                        color: Colors.black.withOpacity(0.02),
                        blurRadius: 4,
                        offset: const Offset(0, 2),
                      ),
                    ],
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(
                  icon,
                  size: 20,
                  color: isActive ? Colors.white : const Color(0xFF64748B),
                ),
                const SizedBox(width: 8),
                Text(
                  name,
                  style: GoogleFonts.outfit(
                    fontSize: 14,
                    fontWeight: isActive ? FontWeight.w700 : FontWeight.w600,
                    color: isActive ? Colors.white : const Color(0xFF64748B),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
