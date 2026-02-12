import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';

class TimeSlotGrid extends StatelessWidget {
  final AsyncValue<List<String>> slotsAsync;
  final String? selectedTimeSlot;
  final Function(String) onTimeSlotChanged;

  const TimeSlotGrid({
    super.key,
    required this.slotsAsync,
    required this.selectedTimeSlot,
    required this.onTimeSlotChanged,
  });

  @override
  Widget build(BuildContext context) {
    return slotsAsync.when(
      data: (slots) {
        if (slots.isEmpty) {
          return _buildPlaceholderMessage();
        }
        return GridView.builder(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisCount: 3,
            mainAxisSpacing: 12,
            crossAxisSpacing: 12,
            childAspectRatio: 2.5,
          ),
          itemCount: slots.length,
          itemBuilder: (context, index) {
            final time = slots[index];
            final isSelected = time == selectedTimeSlot;
            return InkWell(
              onTap: () => onTimeSlotChanged(time),
              borderRadius: BorderRadius.circular(12),
              child: Container(
                alignment: Alignment.center,
                decoration: BoxDecoration(
                  color: isSelected ? const Color(0xFF7A1DD1) : Colors.white,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(
                    color: isSelected
                        ? const Color(0xFF7A1DD1)
                        : const Color(0xFFE2E8F0),
                  ),
                ),
                child: Text(
                  time,
                  style: GoogleFonts.outfit(
                    fontWeight: FontWeight.w700,
                    color: isSelected ? Colors.white : const Color(0xFF1E293B),
                  ),
                ),
              ),
            );
          },
        );
      },
      error: (_, __) => const Text('Saatler yüklenemedi.'),
      loading: () => const Center(
        child: CircularProgressIndicator(color: Color(0xFF7A1DD1)),
      ),
    );
  }

  Widget _buildPlaceholderMessage() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(32),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(
            color: const Color(0xFFE2E8F0), style: BorderStyle.solid),
      ),
      child: Column(
        children: [
          Icon(Icons.access_time_rounded, size: 48, color: Colors.grey[300]),
          const SizedBox(height: 16),
          Text(
            'Uygun saat bulunamadı',
            style: GoogleFonts.outfit(
              fontSize: 14,
              color: Colors.grey[400],
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }
}
