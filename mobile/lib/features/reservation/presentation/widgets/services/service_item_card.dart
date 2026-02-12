import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../../home/domain/models/business.dart';

class ServiceItemCard extends StatelessWidget {
  final MenuItem item;
  final int quantity;
  final Function(int) onQuantityChanged;

  const ServiceItemCard({
    super.key,
    required this.item,
    required this.quantity,
    required this.onQuantityChanged,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
      child: Container(
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: const Color(0xFFF8FAFC),
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: const Color(0xFFE2E8F0).withOpacity(0.5)),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Expanded(
              child: GestureDetector(
                behavior: HitTestBehavior.opaque,
                onTap: () => onQuantityChanged(quantity + 1),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      item.name,
                      style: GoogleFonts.outfit(
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF1E293B),
                      ),
                    ),
                    if (item.description != null)
                      Text(
                        item.description!,
                        style: GoogleFonts.outfit(
                          fontSize: 12,
                          color: const Color(0xFF64748B),
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    const SizedBox(height: 4),
                    Text(
                      '${item.price} TL',
                      style: GoogleFonts.outfit(
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF7A1DD1),
                      ),
                    ),
                  ],
                ),
              ),
            ),
            Container(
              height: 40,
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(8),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Material(
                    color: Colors.transparent,
                    child: InkWell(
                      onTap: quantity > 0
                          ? () => onQuantityChanged(quantity - 1)
                          : null,
                      borderRadius: const BorderRadius.only(
                        topLeft: Radius.circular(8),
                        bottomLeft: Radius.circular(8),
                      ),
                      child: Container(
                        width: 40,
                        height: 40,
                        alignment: Alignment.center,
                        child: Icon(
                          Icons.remove_rounded,
                          size: 20,
                          color: quantity > 0
                              ? const Color(0xFF94A3B8)
                              : Colors.grey[200],
                        ),
                      ),
                    ),
                  ),
                  SizedBox(
                    width: 32,
                    child: Text(
                      quantity.toString(),
                      textAlign: TextAlign.center,
                      style: GoogleFonts.outfit(
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF1E293B),
                        fontSize: 16,
                      ),
                    ),
                  ),
                  Material(
                    color: Colors.transparent,
                    child: InkWell(
                      onTap: () => onQuantityChanged(quantity + 1),
                      borderRadius: const BorderRadius.only(
                        topRight: Radius.circular(8),
                        bottomRight: Radius.circular(8),
                      ),
                      child: Container(
                        width: 40,
                        height: 40,
                        alignment: Alignment.center,
                        child: const Icon(
                          Icons.add_rounded,
                          size: 20,
                          color: Color(0xFF7A1DD1),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
