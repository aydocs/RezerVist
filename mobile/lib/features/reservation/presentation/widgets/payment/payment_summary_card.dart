import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';

class PaymentSummaryCard extends StatelessWidget {
  final DateTime selectedDate;
  final String selectedTime;
  final int guestCount;
  final String businessName;

  const PaymentSummaryCard({
    super.key,
    required this.selectedDate,
    required this.selectedTime,
    required this.guestCount,
    required this.businessName,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Column(
        children: [
          _buildInfoRow(
            icon: Icons.calendar_today_rounded,
            iconColor: const Color(0xFF7A1DD1),
            title:
                DateFormat('d MMMM yyyy, EEEE', 'tr_TR').format(selectedDate),
            subtitle: 'Saat: $selectedTime',
          ),
          const Padding(
            padding: EdgeInsets.symmetric(vertical: 10),
            child: Divider(height: 1, color: Color(0xFFE2E8F0)),
          ),
          _buildInfoRow(
            icon: Icons.people_rounded,
            iconColor: const Color(0xFF3B82F6),
            title: '$guestCount Kişi',
            subtitle: businessName,
          ),
        ],
      ),
    );
  }

  Widget _buildInfoRow({
    required IconData icon,
    required Color iconColor,
    required String title,
    required String subtitle,
  }) {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: iconColor.withOpacity(0.1),
            borderRadius: BorderRadius.circular(10),
          ),
          child: Icon(icon, color: iconColor, size: 18),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title,
                style: GoogleFonts.outfit(
                  fontWeight: FontWeight.w600,
                  fontSize: 14,
                  color: const Color(0xFF1E293B),
                ),
              ),
              Text(
                subtitle,
                style: GoogleFonts.outfit(
                  fontSize: 12,
                  color: const Color(0xFF64748B),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}
