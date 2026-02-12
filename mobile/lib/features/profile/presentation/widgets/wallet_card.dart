import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';

class WalletCard extends StatelessWidget {
  final double balance;
  final int points;
  final String userName;

  const WalletCard({
    super.key,
    required this.balance,
    required this.points,
    required this.userName,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () => context.push('/wallet'),
      child: Container(
        width: double.infinity,
        height: 240,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(24),
          boxShadow: [
            BoxShadow(
              color: const Color(0xFF7A1DD1).withOpacity(0.35),
              blurRadius: 30,
              offset: const Offset(0, 15),
              spreadRadius: -5,
            ),
          ],
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(24),
          child: Stack(
            children: [
              // Realistic Card Background with Texture/Pattern
              Container(
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    colors: [
                      Color(0xFF7A1DD1),
                      Color(0xFF4C0099),
                      Color(0xFF330066)
                    ],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                ),
              ),
              // Decorative shapes for realism
              Positioned(
                right: -50,
                top: -50,
                child: Container(
                  width: 250,
                  height: 250,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    gradient: RadialGradient(
                      colors: [
                        Colors.white.withOpacity(0.1),
                        Colors.transparent,
                      ],
                    ),
                  ),
                ),
              ),
              // Card Content
              Padding(
                padding: const EdgeInsets.all(24),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        _buildLogo(),
                        _buildPointsBadge(points),
                      ],
                    ),
                    const SizedBox(height: 16),
                    // The "Chip"
                    _buildChip(),
                    const Spacer(),
                    // Balance / Card Number Area
                    Text(
                      '${balance.toStringAsFixed(2)} TL',
                      style: GoogleFonts.outfit(
                        fontSize: 32,
                        fontWeight: FontWeight.w900,
                        color: Colors.white,
                        letterSpacing: 1.5,
                        shadows: [
                          Shadow(
                            color: Colors.black.withOpacity(0.3),
                            offset: const Offset(0, 2),
                            blurRadius: 4,
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 12),
                    // Footer: Card Holder & "Card Number" style
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      crossAxisAlignment: CrossAxisAlignment.end,
                      children: [
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'KART SAHİBİ',
                              style: GoogleFonts.outfit(
                                fontSize: 10,
                                color: Colors.white.withOpacity(0.6),
                                fontWeight: FontWeight.w600,
                                letterSpacing: 1,
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              userName.toUpperCase(),
                              style: GoogleFonts.outfit(
                                fontSize: 14,
                                color: Colors.white,
                                fontWeight: FontWeight.w700,
                                letterSpacing: 0.5,
                              ),
                            ),
                          ],
                        ),
                        Text(
                          '**** 4242',
                          style: GoogleFonts.outfit(
                            fontSize: 14,
                            color: Colors.white.withOpacity(0.8),
                            fontWeight: FontWeight.w500,
                            letterSpacing: 2,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildLogo() {
    return Text(
      'REZERVIST',
      style: GoogleFonts.outfit(
        fontSize: 18,
        fontWeight: FontWeight.w900,
        color: Colors.white,
        letterSpacing: 2,
      ),
    );
  }

  Widget _buildChip() {
    return Container(
      width: 45,
      height: 35,
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [
            const Color(0xFFFFD700).withOpacity(0.8),
            const Color(0xFFDAA520),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(8),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.2),
            blurRadius: 4,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Stack(
        children: [
          // Realistic chip internal lines
          ...List.generate(
              3,
              (i) => Positioned(
                    left: 10.0 + (i * 10),
                    top: 0,
                    bottom: 0,
                    child: Container(width: 0.5, color: Colors.black12),
                  )),
          ...List.generate(
              2,
              (i) => Positioned(
                    top: 10.0 + (i * 10),
                    left: 0,
                    right: 0,
                    child: Container(height: 0.5, color: Colors.black12),
                  )),
        ],
      ),
    );
  }

  Widget _buildPointsBadge(int points) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.15),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.white.withOpacity(0.1)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          const Icon(Icons.stars_rounded, color: Color(0xFFFBBF24), size: 16),
          const SizedBox(width: 4),
          Text(
            '$points P',
            style: GoogleFonts.outfit(
              fontSize: 12,
              fontWeight: FontWeight.w800,
              color: Colors.white,
            ),
          ),
        ],
      ),
    );
  }
}
