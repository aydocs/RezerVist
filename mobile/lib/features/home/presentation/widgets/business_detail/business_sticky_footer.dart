import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../domain/models/business.dart';
import '../../../../reservation/presentation/widgets/reservation_wizard_modal.dart';

class BusinessStickyFooter extends StatelessWidget {
  final Business business;

  const BusinessStickyFooter({
    super.key,
    required this.business,
  });

  void _onReservationPressed(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      enableDrag: true,
      builder: (context) => ReservationWizardModal(
        business: business,
        categories: business.categories ?? [],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Positioned(
      bottom: 0,
      left: 0,
      right: 0,
      child: Container(
        padding: const EdgeInsets.all(24),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 20,
              offset: const Offset(0, -5),
            ),
          ],
        ),
        child: SafeArea(
          top: false,
          child: Row(
            children: [
              Expanded(
                child: SizedBox(
                  height: 56,
                  child: ElevatedButton(
                    onPressed: () => _onReservationPressed(context),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF7A1DD1),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(16),
                      ),
                      elevation: 0,
                      shadowColor: Colors.transparent,
                    ),
                    child: Text(
                      'Randevu Oluştur',
                      style: GoogleFonts.outfit(
                        fontSize: 18,
                        fontWeight: FontWeight.w700,
                        color: Colors.white,
                      ),
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
