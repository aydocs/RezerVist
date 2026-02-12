import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import '../../../home/domain/models/business.dart';
import '../../../home/presentation/providers/business_provider.dart';
import '../../../common/presentation/widgets/custom_date_picker_modal.dart';
import '../../../common/presentation/widgets/guest_selector_modal.dart';
import 'details/step_one_section_label.dart';
import 'details/step_one_dropdown.dart';
import 'details/price_info_badge.dart';
import 'details/time_slot_grid.dart';

class StepOneDetails extends ConsumerWidget {
  final Business business;
  final DateTime selectedDate;
  final int adults;
  final int children;
  final String? selectedTimeSlot;
  final Function(DateTime) onDateChanged;
  final Function(int adults, int children) onGuestsChanged;
  final Function(String) onTimeSlotChanged;

  const StepOneDetails({
    super.key,
    required this.business,
    required this.selectedDate,
    required this.adults,
    required this.children,
    required this.selectedTimeSlot,
    required this.onDateChanged,
    required this.onGuestsChanged,
    required this.onTimeSlotChanged,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    // If date is selected, we should fetch slots
    // In a real app, you might trigger this in a provider or state
    // For now, let's assume the provider handles fetching when parameters change
    final slotsAsync = ref.watch(availableSlotsProvider((
      businessId: business.id,
      date: DateFormat('yyyy-MM-dd').format(selectedDate)
    )));

    return SingleChildScrollView(
      padding: const EdgeInsets.all(24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          StepOneSectionLabel('TARİH SEÇİN'),
          const SizedBox(height: 8),
          StepOneDropdown(
            value: DateFormat('d MMMM yyyy', 'tr').format(selectedDate),
            onTap: () {
              showModalBottomSheet(
                context: context,
                backgroundColor: Colors.transparent,
                isScrollControlled: true,
                builder: (context) => CustomDatePickerModal(
                  initialDate: selectedDate,
                  onDateSelected: onDateChanged,
                ),
              );
            },
          ),
          const SizedBox(height: 24),
          StepOneSectionLabel('KİŞİ SAYISI'),
          const SizedBox(height: 8),
          StepOneDropdown(
            value: _formatGuestText(),
            onTap: () {
              showModalBottomSheet(
                context: context,
                backgroundColor: Colors.transparent,
                isScrollControlled: true,
                builder: (context) => GuestSelectorModal(
                  initialAdults: adults,
                  initialChildren: children,
                  onApply: onGuestsChanged,
                ),
              );
            },
          ),
          // Per-person price info badge
          PriceInfoBadge(
            pricePerPerson: business.pricePerPerson ?? 0,
            totalPeople: adults + children,
          ),
          const SizedBox(height: 24),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  StepOneSectionLabel('SAAT SEÇİN'),
                  if (slotsAsync is AsyncData<List<String>> &&
                      slotsAsync.value.isNotEmpty)
                    Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: const Color(0xFF7A1DD1).withOpacity(0.1),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        '${slotsAsync.value.length} Saat Müsait',
                        style: GoogleFonts.outfit(
                          fontSize: 12,
                          fontWeight: FontWeight.w700,
                          color: const Color(0xFF7A1DD1),
                        ),
                      ),
                    )
                ],
              ),
              const SizedBox(height: 16),
              const SizedBox(height: 16),
              TimeSlotGrid(
                slotsAsync: slotsAsync,
                selectedTimeSlot: selectedTimeSlot,
                onTimeSlotChanged: onTimeSlotChanged,
              ),
            ],
          ),
        ],
      ),
    );
  }

  String _formatGuestText() {
    if (children > 0) {
      return '$adults Yetişkin, $children Çocuk';
    }
    return '$adults Yetişkin';
  }
}
