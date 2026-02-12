import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class GuestSelectorModal extends StatefulWidget {
  final int initialAdults;
  final int initialChildren;
  final Function(int adults, int children) onApply;

  const GuestSelectorModal({
    super.key,
    required this.initialAdults,
    required this.initialChildren,
    required this.onApply,
  });

  @override
  State<GuestSelectorModal> createState() => _GuestSelectorModalState();
}

class _GuestSelectorModalState extends State<GuestSelectorModal> {
  late int _adults;
  late int _children;

  @override
  void initState() {
    super.initState();
    _adults = widget.initialAdults;
    _children = widget.initialChildren;
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Misafir Sayısı',
                style: GoogleFonts.outfit(
                  fontSize: 20,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFF1E293B),
                ),
              ),
              IconButton(
                onPressed: () => Navigator.pop(context),
                icon: const Icon(Icons.close_rounded, color: Colors.grey),
              ),
            ],
          ),
          const SizedBox(height: 24),
          _buildCounterRow(
            'Yetişkin',
            '18 yaş ve üzeri',
            _adults,
            (val) => setState(() => _adults = val),
            minValue: 1,
            maxValue: 20,
          ),
          const Divider(height: 32, color: Color(0xFFF1F5F9)),
          _buildCounterRow(
            'Çocuk',
            '0-17 yaş arası',
            _children,
            (val) => setState(() => _children = val),
            minValue: 0,
            maxValue: 10,
          ),
          const SizedBox(height: 32),
          SizedBox(
            width: double.infinity,
            height: 56,
            child: ElevatedButton(
              onPressed: () {
                widget.onApply(_adults, _children);
                Navigator.pop(context);
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF7A1DD1),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(16),
                ),
                elevation: 0,
              ),
              child: Text(
                'Uygula',
                style: GoogleFonts.outfit(
                  fontSize: 16,
                  fontWeight: FontWeight.w700,
                  color: Colors.white,
                ),
              ),
            ),
          ),
          const SizedBox(height: 16),
        ],
      ),
    );
  }

  Widget _buildCounterRow(
    String label,
    String subLabel,
    int value,
    Function(int) onChanged, {
    int minValue = 0,
    int maxValue = 99,
  }) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              label,
              style: GoogleFonts.outfit(
                fontSize: 16,
                fontWeight: FontWeight.w700,
                color: const Color(0xFF1E293B),
              ),
            ),
            Text(
              subLabel,
              style: GoogleFonts.outfit(
                fontSize: 12,
                color: const Color(0xFF94A3B8),
              ),
            ),
          ],
        ),
        Row(
          children: [
            _buildIconButton(
              icon: Icons.remove,
              onPressed: value > minValue ? () => onChanged(value - 1) : null,
              color: const Color(0xFFF1F5F9),
              iconColor: const Color(0xFF64748B),
            ),
            SizedBox(
              width: 40,
              child: Text(
                value.toString(),
                textAlign: TextAlign.center,
                style: GoogleFonts.outfit(
                  fontSize: 18,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFF1E293B),
                ),
              ),
            ),
            _buildIconButton(
              icon: Icons.add,
              onPressed: value < maxValue ? () => onChanged(value + 1) : null,
              color: const Color(0xFFF3E8FF),
              iconColor: const Color(0xFF7A1DD1),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildIconButton({
    required IconData icon,
    required VoidCallback? onPressed,
    required Color color,
    required Color iconColor,
  }) {
    return Container(
      width: 40,
      height: 40,
      decoration: BoxDecoration(
        color: onPressed == null ? const Color(0xFFF8FAFC) : color,
        borderRadius: BorderRadius.circular(12),
      ),
      child: IconButton(
        onPressed: onPressed,
        icon: Icon(icon,
            size: 20,
            color: onPressed == null ? const Color(0xFFCBD5E1) : iconColor),
        padding: EdgeInsets.zero,
      ),
    );
  }
}
