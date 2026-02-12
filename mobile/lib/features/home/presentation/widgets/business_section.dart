import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import '../../domain/models/business.dart';
import 'business_card.dart';

class BusinessSection extends StatelessWidget {
  final String title;
  final IconData icon;
  final Color iconColor;
  final List<Business> businesses;

  const BusinessSection({
    super.key,
    required this.title,
    required this.icon,
    required this.iconColor,
    required this.businesses,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Row(
                children: [
                  Icon(icon, color: iconColor, size: 28),
                  const SizedBox(width: 12),
                  Text(
                    title,
                    style: GoogleFonts.outfit(
                      fontSize: 20,
                      fontWeight: FontWeight.w800,
                      color: const Color(0xFF1E293B),
                    ),
                  ),
                ],
              ),
              TextButton(
                onPressed: () {
                  // TODO: Implement view all navigation
                },
                child: Text(
                  'Tümü',
                  style: GoogleFonts.outfit(
                    fontSize: 14,
                    fontWeight: FontWeight.w700,
                    color: const Color(0xFF7A1DD1),
                  ),
                ),
              ),
            ],
          ),
        ),
        SizedBox(
          height: 440, // Increased height to prevent overflow in BusinessCard
          child: ListView.builder(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: businesses.length,
            itemBuilder: (context, index) {
              return SizedBox(
                width: 300,
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 8),
                  child: BusinessCard(
                    business: businesses[index],
                    onTap: () {
                      context.pushNamed(
                        'businessDetail',
                        pathParameters: {'id': businesses[index].id.toString()},
                        queryParameters: {'name': businesses[index].name},
                      );
                    },
                  ),
                ),
              );
            },
          ),
        ),
      ],
    );
  }
}
