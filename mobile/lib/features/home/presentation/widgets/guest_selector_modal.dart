import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class GuestSelectorModal extends StatefulWidget {
  final int initialGuestCount;
  final int initialChildCount;
  final Function(int guests, int children) onApply;

  const GuestSelectorModal({
    super.key,
    required this.initialGuestCount,
    required this.initialChildCount,
    required this.onApply,
  });

  @override
  State<GuestSelectorModal> createState() => _GuestSelectorModalState();
}

class _GuestSelectorModalState extends State<GuestSelectorModal> {
  late int _guestCount;
  late int _childCount;

  @override
  void initState() {
    super.initState();
    _guestCount = widget.initialGuestCount;
    _childCount = widget.initialChildCount;
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
      ),
      padding: const EdgeInsets.fromLTRB(24, 32, 24, 24),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          _buildGuestTypeRow(
            title: 'Yetişkin',
            subtitle: '18 yaş ve üzeri',
            count: _guestCount,
            onDecrement:
                _guestCount > 1 ? () => setState(() => _guestCount--) : null,
            onIncrement:
                _guestCount < 20 ? () => setState(() => _guestCount++) : null,
          ),
          const SizedBox(height: 32),
          _buildGuestTypeRow(
            title: 'Çocuk',
            subtitle: '0-17 yaş arası',
            count: _childCount,
            onDecrement:
                _childCount > 0 ? () => setState(() => _childCount--) : null,
            onIncrement:
                _childCount < 10 ? () => setState(() => _childCount++) : null,
          ),
          const SizedBox(height: 40),
          SafeArea(
            child: Padding(
              padding: const EdgeInsets.only(bottom: 16),
              child: SizedBox(
                width: double.infinity,
                height: 60,
                child: Container(
                  decoration: BoxDecoration(
                    boxShadow: [
                      BoxShadow(
                        color: const Color(0xFF7A1DD1).withOpacity(0.24),
                        blurRadius: 20,
                        offset: const Offset(0, 10),
                      ),
                    ],
                  ),
                  child: ElevatedButton(
                    onPressed: () {
                      widget.onApply(_guestCount, _childCount);
                      Navigator.pop(context);
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF7A1DD1),
                      foregroundColor: Colors.white,
                      elevation: 0,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(20),
                      ),
                    ),
                    child: Text(
                      'Uygula',
                      style: GoogleFonts.outfit(
                        fontSize: 18,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildGuestTypeRow({
    required String title,
    required String subtitle,
    required int count,
    VoidCallback? onDecrement,
    VoidCallback? onIncrement,
  }) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: GoogleFonts.outfit(
                fontSize: 18,
                fontWeight: FontWeight.w700,
                color: const Color(0xFF0F172A),
              ),
            ),
            const SizedBox(height: 2),
            Text(
              subtitle,
              style: GoogleFonts.outfit(
                fontSize: 13,
                fontWeight: FontWeight.w500,
                color: const Color(0xFF94A3B8),
              ),
            ),
          ],
        ),
        Row(
          children: [
            _guestCounterButton(
              icon: Icons.remove_rounded,
              onPressed: onDecrement,
            ),
            SizedBox(
              width: 44,
              child: Center(
                child: Text(
                  '$count',
                  style: GoogleFonts.outfit(
                    fontSize: 18,
                    fontWeight: FontWeight.w700,
                    color: const Color(0xFF1E293B),
                  ),
                ),
              ),
            ),
            _guestCounterButton(
              icon: Icons.add_rounded,
              onPressed: onIncrement,
            ),
          ],
        ),
      ],
    );
  }

  Widget _guestCounterButton(
      {required IconData icon, VoidCallback? onPressed}) {
    return Container(
      decoration: BoxDecoration(
        color: onPressed == null
            ? const Color(0xFFF1F5F9)
            : const Color(0xFF7A1DD1).withOpacity(0.1),
        borderRadius: BorderRadius.circular(12),
      ),
      child: IconButton(
        icon: Icon(icon,
            color: onPressed == null
                ? const Color(0xFF94A3B8)
                : const Color(0xFF7A1DD1)),
        onPressed: onPressed,
      ),
    );
  }
}
