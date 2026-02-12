import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

import '../../../home/domain/models/business.dart';

import 'payment/payment_summary_card.dart';
import 'payment/payment_method_selector.dart';
import 'payment/price_breakdown_card.dart';

class StepThreePayment extends StatelessWidget {
  final Business business;
  final DateTime selectedDate;
  final String selectedTime;
  final int guestCount;
  final Map<int, int> selectedMenuQuantities;
  final List<BusinessCategory> categories;
  final String selectedPaymentMethod;
  final ValueChanged<String> onPaymentMethodChanged;
  final ValueChanged<String> onSpecialRequestChanged;

  const StepThreePayment({
    super.key,
    required this.business,
    required this.selectedDate,
    required this.selectedTime,
    required this.guestCount,
    required this.selectedMenuQuantities,
    required this.categories,
    required this.selectedPaymentMethod,
    required this.onPaymentMethodChanged,
    required this.onSpecialRequestChanged,
  });

  @override
  Widget build(BuildContext context) {
    // --- Price Calculations ---
    final perPersonPrice = business.pricePerPerson ?? 0;
    final perPersonTotal = perPersonPrice * guestCount;

    double servicesTotal = 0;
    List<ServiceItem> serviceItems = [];

    for (var category in categories) {
      if (category.items != null) {
        for (var item in category.items!) {
          if (selectedMenuQuantities.containsKey(item.id)) {
            final qty = selectedMenuQuantities[item.id]!;
            final price = item.price * qty;
            servicesTotal += price;
            serviceItems.add(ServiceItem(
              name: item.name,
              quantity: qty,
              unitPrice: item.price,
              totalPrice: price,
            ));
          }
        }
      }
    }

    final grandTotal = perPersonTotal + servicesTotal;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // --- Reservation Summary Card ---
          PaymentSummaryCard(
            selectedDate: selectedDate,
            selectedTime: selectedTime,
            guestCount: guestCount,
            businessName: business.name,
          ),
          const SizedBox(height: 16),

          // --- Price Breakdown Card ---
          PriceBreakdownCard(
            perPersonPrice: perPersonPrice,
            perPersonTotal: perPersonTotal,
            guestCount: guestCount,
            serviceItems: serviceItems,
            servicesTotal: servicesTotal,
            grandTotal: grandTotal,
          ),
          const SizedBox(height: 16),

          // --- Payment Method ---
          PaymentMethodSelector(
            selectedPaymentMethod: selectedPaymentMethod,
            onPaymentMethodChanged: onPaymentMethodChanged,
          ),
          const SizedBox(height: 16),

          // --- Special Request ---
          _buildSpecialRequestField(),

          const SizedBox(height: 80),
        ],
      ),
    );
  }

  // --- Special Request ---
  Widget _buildSpecialRequestField() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Özel İstekler',
          style: GoogleFonts.outfit(
            fontSize: 16,
            fontWeight: FontWeight.w700,
            color: const Color(0xFF1E293B),
          ),
        ),
        const SizedBox(height: 10),
        TextField(
          style: GoogleFonts.outfit(color: Colors.black, fontSize: 14),
          decoration: InputDecoration(
            hintText: 'Alerjiler, masa tercihi, özel günler vb.',
            hintStyle: GoogleFonts.outfit(
                color: const Color(0xFF94A3B8), fontSize: 13),
            filled: true,
            fillColor: const Color(0xFFF8FAFC),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(14),
              borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(14),
              borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(14),
              borderSide: const BorderSide(color: Color(0xFF7A1DD1)),
            ),
            contentPadding: const EdgeInsets.all(14),
          ),
          maxLines: 2,
          onChanged: onSpecialRequestChanged,
        ),
      ],
    );
  }
}
