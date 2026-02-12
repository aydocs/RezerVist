import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';

class HomeHeader extends StatelessWidget {
  const HomeHeader({super.key});

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: const Color(0xFF7A1DD1),
                borderRadius: BorderRadius.circular(12),
              ),
              child:
                  const Icon(Icons.bolt_rounded, color: Colors.white, size: 20),
            ),
            const SizedBox(width: 12),
            Text(
              'RezerVist',
              style: GoogleFonts.outfit(
                fontSize: 22,
                fontWeight: FontWeight.w900,
                color: const Color(0xFF1E293B),
                letterSpacing: -0.5,
              ),
            ),
          ],
        ),
        Stack(
          children: [
            IconButton(
              onPressed: () {
                context.push('/notifications');
              },
              icon: Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: const Icon(Icons.notifications_none_rounded,
                    color: Color(0xFF64748B)),
              ),
            ),
            Positioned(
              right: 12,
              top: 12,
              child: Container(
                width: 8,
                height: 8,
                decoration: BoxDecoration(
                  color: const Color(0xFFEF4444),
                  shape: BoxShape.circle,
                  border: Border.all(color: Colors.white, width: 1.5),
                ),
              ),
            ),
          ],
        ),
      ],
    );
  }
}
