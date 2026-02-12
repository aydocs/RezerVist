import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class ReservationWizardStepper extends StatelessWidget {
  final int currentStep;

  const ReservationWizardStepper({
    super.key,
    required this.currentStep,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(24, 16, 24, 8),
      child: Row(
        children: [
          _buildStepCircle(0, Icons.calendar_today_rounded, 'Detaylar'),
          _buildStepConnector(0),
          _buildStepCircle(1, Icons.restaurant_menu_rounded, 'Hizmetler'),
          _buildStepConnector(1),
          _buildStepCircle(2, Icons.payment_rounded, 'Ödeme'),
        ],
      ),
    );
  }

  Widget _buildStepCircle(int index, IconData icon, String label) {
    final isActive = index == currentStep;
    final isCompleted = index < currentStep;

    return Expanded(
      flex: 0,
      child: Column(
        children: [
          AnimatedContainer(
            duration: const Duration(milliseconds: 300),
            curve: Curves.easeInOut,
            width: isActive ? 44 : 36,
            height: isActive ? 44 : 36,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: isCompleted
                  ? const Color(0xFF22C55E)
                  : isActive
                      ? const Color(0xFF7A1DD1)
                      : const Color(0xFFF1F5F9),
              boxShadow: isActive
                  ? [
                      BoxShadow(
                        color: const Color(0xFF7A1DD1).withOpacity(0.3),
                        blurRadius: 12,
                        spreadRadius: 2,
                      )
                    ]
                  : null,
            ),
            child: Center(
              child: isCompleted
                  ? const Icon(Icons.check_rounded,
                      color: Colors.white, size: 20)
                  : Icon(icon,
                      color: isActive ? Colors.white : const Color(0xFF94A3B8),
                      size: isActive ? 20 : 16),
            ),
          ),
          const SizedBox(height: 6),
          Text(
            label,
            style: GoogleFonts.outfit(
              fontSize: 11,
              fontWeight:
                  isActive || isCompleted ? FontWeight.w700 : FontWeight.w500,
              color: isActive
                  ? const Color(0xFF7A1DD1)
                  : isCompleted
                      ? const Color(0xFF22C55E)
                      : const Color(0xFF94A3B8),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStepConnector(int afterIndex) {
    final isCompleted = afterIndex < currentStep;
    return Expanded(
      child: Padding(
        padding: const EdgeInsets.only(bottom: 20),
        child: AnimatedContainer(
          duration: const Duration(milliseconds: 400),
          height: 3,
          margin: const EdgeInsets.symmetric(horizontal: 4),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(2),
            color:
                isCompleted ? const Color(0xFF22C55E) : const Color(0xFFE2E8F0),
          ),
        ),
      ),
    );
  }
}
