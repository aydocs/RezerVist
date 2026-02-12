import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class PaymentMethodSelector extends StatelessWidget {
  final String selectedPaymentMethod;
  final ValueChanged<String> onPaymentMethodChanged;

  const PaymentMethodSelector({
    super.key,
    required this.selectedPaymentMethod,
    required this.onPaymentMethodChanged,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Ödeme Yöntemi',
          style: GoogleFonts.outfit(
            fontSize: 16,
            fontWeight: FontWeight.w700,
            color: const Color(0xFF1E293B),
          ),
        ),
        const SizedBox(height: 12),
        _buildPaymentOption(
          value: 'card',
          label: 'Kredi / Banka Kartı',
          icon: Icons.credit_card_rounded,
          description: 'Online ödeme ile rezervasyonu tamamla',
        ),
        const SizedBox(height: 10),
        _buildPaymentOption(
          value: 'wallet',
          label: 'Cüzdan ile Ödeme',
          icon: Icons.account_balance_wallet_rounded,
          description: 'Mevcut bakiyeni kullanarak öde',
        ),
      ],
    );
  }

  Widget _buildPaymentOption({
    required String value,
    required String label,
    required IconData icon,
    required String description,
  }) {
    final isSelected = selectedPaymentMethod == value;

    return GestureDetector(
      onTap: () => onPaymentMethodChanged(value),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: isSelected ? const Color(0xFFF3E8FF) : Colors.white,
          border: Border.all(
            color:
                isSelected ? const Color(0xFF7A1DD1) : const Color(0xFFE2E8F0),
            width: isSelected ? 2 : 1,
          ),
          borderRadius: BorderRadius.circular(14),
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(10),
              decoration: BoxDecoration(
                color: isSelected ? Colors.white : const Color(0xFFF1F5F9),
                shape: BoxShape.circle,
              ),
              child: Icon(icon,
                  color: isSelected
                      ? const Color(0xFF7A1DD1)
                      : const Color(0xFF64748B),
                  size: 22),
            ),
            const SizedBox(width: 14),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    label,
                    style: GoogleFonts.outfit(
                      fontWeight: FontWeight.w600,
                      color: isSelected
                          ? const Color(0xFF7A1DD1)
                          : const Color(0xFF1E293B),
                      fontSize: 15,
                    ),
                  ),
                  Text(
                    description,
                    style: GoogleFonts.outfit(
                      color: const Color(0xFF64748B),
                      fontSize: 12,
                    ),
                  ),
                ],
              ),
            ),
            AnimatedSwitcher(
              duration: const Duration(milliseconds: 200),
              child: isSelected
                  ? const Icon(Icons.check_circle_rounded,
                      key: ValueKey('check'), color: Color(0xFF7A1DD1))
                  : const Icon(Icons.radio_button_unchecked_rounded,
                      key: ValueKey('uncheck'), color: Color(0xFFCBD5E1)),
            ),
          ],
        ),
      ),
    );
  }
}
