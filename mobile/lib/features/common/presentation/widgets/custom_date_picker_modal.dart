import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';

class CustomDatePickerModal extends StatefulWidget {
  final DateTime initialDate;
  final Function(DateTime) onDateSelected;

  const CustomDatePickerModal({
    super.key,
    required this.initialDate,
    required this.onDateSelected,
  });

  @override
  State<CustomDatePickerModal> createState() => _CustomDatePickerModalState();
}

class _CustomDatePickerModalState extends State<CustomDatePickerModal> {
  late DateTime _selectedDate;
  late DateTime _currentMonth;

  @override
  void initState() {
    super.initState();
    _selectedDate = widget.initialDate;
    _currentMonth = DateTime(widget.initialDate.year, widget.initialDate.month);
  }

  @override
  Widget build(BuildContext context) {
    final now = DateTime.now();
    // Simple calendar logic for the current month
    final daysInMonth =
        DateTime(_currentMonth.year, _currentMonth.month + 1, 0).day;
    final firstDayOfMonth =
        DateTime(_currentMonth.year, _currentMonth.month, 1).weekday; // 1 = Mon

    final weekDays = ['PT', 'SA', 'ÇA', 'PE', 'CU', 'CT', 'PZ'];
    final monthName = DateFormat('MMMM yyyy', 'tr_TR').format(_currentMonth);

    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
      ),
      padding: const EdgeInsets.fromLTRB(24, 32, 24, 32),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Month Selector Header
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
                      _currentMonth =
                          DateTime(_currentMonth.year, _currentMonth.month - 1);
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
                      _currentMonth =
                          DateTime(_currentMonth.year, _currentMonth.month + 1);
                    });
                  },
                  icon: const Icon(Icons.chevron_right_rounded,
                      color: Color(0xFF7A1DD1), size: 28),
                ),
              ),
            ],
          ),
          const SizedBox(height: 24),
          // Weekdays
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
          // Days Grid
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
              final date =
                  DateTime(_currentMonth.year, _currentMonth.month, day);
              final isSelected = _selectedDate.year == date.year &&
                  _selectedDate.month == date.month &&
                  _selectedDate.day == date.day;

              final isToday = now.year == date.year &&
                  now.month == date.month &&
                  now.day == date.day;

              final isPast =
                  date.isBefore(DateTime(now.year, now.month, now.day));

              return InkWell(
                onTap: isPast
                    ? null
                    : () {
                        setState(() {
                          _selectedDate = date;
                        });
                        widget.onDateSelected(date);
                        Navigator.pop(context);
                      },
                borderRadius: BorderRadius.circular(12),
                child: Container(
                  decoration: isSelected
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
                      : (isToday
                          ? BoxDecoration(
                              border: Border.all(
                                  color: const Color(0xFF7A1DD1), width: 1),
                              borderRadius: BorderRadius.circular(12),
                            )
                          : null),
                  child: Center(
                    child: Text(
                      '$day',
                      style: GoogleFonts.outfit(
                        fontSize: 15,
                        fontWeight:
                            isSelected ? FontWeight.w700 : FontWeight.w600,
                        color: isSelected
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
