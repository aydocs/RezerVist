import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'home_interactive_field.dart';
import 'home_search_button.dart';

class HomeSearchCard extends StatelessWidget {
  final String locationText;
  final DateTime selectedDate;
  final int guestCount;
  final int childCount;
  final VoidCallback onLocationTap;
  final VoidCallback onDateTap;
  final VoidCallback onGuestTap;
  final VoidCallback onSearch;

  const HomeSearchCard({
    super.key,
    required this.locationText,
    required this.selectedDate,
    required this.guestCount,
    required this.childCount,
    required this.onLocationTap,
    required this.onDateTap,
    required this.onGuestTap,
    required this.onSearch,
  });

  @override
  Widget build(BuildContext context) {
    final turkishDateFormat = DateFormat('d MMMM yyyy', 'tr_TR');

    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(32),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF7A1DD1).withOpacity(0.08),
            blurRadius: 40,
            offset: const Offset(0, 20),
          ),
        ],
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          HomeInteractiveField(
            label: 'NEREYE?',
            hint: locationText.isEmpty
                ? 'İlçe, semt, mahalle veya mekan...'
                : locationText,
            icon: Icons.search_rounded,
            iconColor: const Color(0xFF7A1DD1),
            onTap: onLocationTap,
          ),
          const SizedBox(height: 16),
          HomeInteractiveField(
            label: 'GİRİŞ TARİHİ',
            hint: turkishDateFormat.format(selectedDate),
            icon: Icons.calendar_month_rounded,
            iconColor: const Color(0xFF7A1DD1),
            onTap: onDateTap,
          ),
          const SizedBox(height: 16),
          HomeInteractiveField(
            label: 'KİŞİ SAYISI',
            hint:
                '$guestCount Yetişkin${childCount > 0 ? ', $childCount Çocuk' : ''}',
            icon: Icons.people_alt_rounded,
            iconColor: const Color(0xFF7A1DD1),
            onTap: onGuestTap,
          ),
          const SizedBox(height: 24),
          HomeSearchButton(
            onPressed: onSearch,
          ),
        ],
      ),
    );
  }
}
