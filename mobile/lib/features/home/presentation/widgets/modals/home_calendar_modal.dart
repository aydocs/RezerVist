import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';

class HomeCalendarModal extends StatefulWidget {
  final DateTime initialDate;
  final Function(DateTime) onDateSelected;

  const HomeCalendarModal({
    super.key,
    required this.initialDate,
    required this.onDateSelected,
  });

  @override
  State<HomeCalendarModal> createState() => _HomeCalendarModalState();
}

class _HomeCalendarModalState extends State<HomeCalendarModal> {
  late DateTime _selectedDate;

  @override
  void initState() {
    super.initState();
    _selectedDate = widget.initialDate;
  }

  @override
  Widget build(BuildContext context) {
    final now = DateTime.now();
    final daysInMonth =
        DateTime(_selectedDate.year, _selectedDate.month + 1, 0).day;
    final firstDayOfMonth =
        DateTime(_selectedDate.year, _selectedDate.month, 1).weekday; // 1 = Mon

    final weekDays = ['PT', 'SA', 'ÇA', 'PE', 'CU', 'CT', 'PZ'];
    final monthName = DateFormat('MMMM yyyy', 'tr_TR').format(_selectedDate);

    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
      ),
      padding: const EdgeInsets.fromLTRB(24, 32, 24, 32),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                decoration: BoxDecoration(
                  color: const Color(0xFFF1F5F9),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: IconButton(
                  onPressed: () {
                    setState(() {
                      _selectedDate =
                          DateTime(_selectedDate.year, _selectedDate.month - 1);
                    });
                  },
                  icon: const Icon(Icons.chevron_left_rounded,
                      color: Color(0xFF7A1DD1), size: 28),
                ),
              ),
              Text(
                monthName,
                style: GoogleFonts.outfit(
                  fontSize: 20,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF1E293B),
                ),
              ),
              Container(
                decoration: BoxDecoration(
                  color: const Color(0xFFF1F5F9),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: IconButton(
                  onPressed: () {
                    setState(() {
                      _selectedDate =
                          DateTime(_selectedDate.year, _selectedDate.month + 1);
                    });
                  },
                  icon: const Icon(Icons.chevron_right_rounded,
                      color: Color(0xFF7A1DD1), size: 28),
                ),
              ),
            ],
          ),
          const SizedBox(height: 24),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: weekDays
                .map((day) => Text(
                      day,
                      style: GoogleFonts.outfit(
                        fontSize: 13,
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF94A3B8),
                      ),
                    ))
                .toList(),
          ),
          const SizedBox(height: 16),
          GridView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 7,
              mainAxisSpacing: 8,
              crossAxisSpacing: 8,
            ),
            itemCount: daysInMonth + (firstDayOfMonth - 1),
            itemBuilder: (context, index) {
              if (index < firstDayOfMonth - 1) return const SizedBox();

              final day = index - (firstDayOfMonth - 2);
              final isSelected = _selectedDate.day == day &&
                  _selectedDate.month == widget.initialDate.month;
              // Note: logic for 'isSelected' was slightly approximated in original,
              // here checking equality more strictly against internal state.
              // Actually, simply checking checks against _selectedDate which updates on nav is fine.
              // But original logic compared to `_selectedDate` state which updated on nav, so it highlight
              // the day in the VIEWED month.
              final isDaySelected = _selectedDate.day == day;

              final isPast =
                  DateTime(_selectedDate.year, _selectedDate.month, day)
                      .isBefore(DateTime(now.year, now.month, now.day));

              return InkWell(
                onTap: isPast
                    ? null
                    : () {
                        setState(() {
                          _selectedDate = DateTime(
                              _selectedDate.year, _selectedDate.month, day);
                        });
                        widget.onDateSelected(_selectedDate);
                        Navigator.pop(context);
                      },
                borderRadius: BorderRadius.circular(12),
                child: Container(
                  decoration: isDaySelected
                      ? BoxDecoration(
                          color: const Color(0xFF7A1DD1),
                          borderRadius: BorderRadius.circular(12),
                          boxShadow: [
                            BoxShadow(
                              color: const Color(0xFF7A1DD1).withOpacity(0.3),
                              blurRadius: 10,
                              offset: const Offset(0, 4),
                            ),
                          ],
                        )
                      : null,
                  child: Center(
                    child: Text(
                      '$day',
                      style: GoogleFonts.outfit(
                        fontSize: 15,
                        fontWeight:
                            isDaySelected ? FontWeight.w700 : FontWeight.w600,
                        color: isDaySelected
                            ? Colors.white
                            : (isPast
                                ? const Color(0xFFCBD5E1)
                                : const Color(0xFF1E293B)),
                      ),
                    ),
                  ),
                ),
              );
            },
          ),
        ],
      ),
    );
  }
}
