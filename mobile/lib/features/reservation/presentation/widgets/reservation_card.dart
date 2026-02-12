import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import '../../domain/models/reservation.dart';

class ReservationCard extends StatelessWidget {
  final Reservation reservation;
  final bool isActive;

  const ReservationCard({
    super.key,
    required this.reservation,
    required this.isActive,
  });

  @override
  Widget build(BuildContext context) {
    final statusInfo = _getStatusInfo(reservation.status);
    String formattedDate = '';
    String formattedTime = '';

    if (reservation.startTime != null) {
      try {
        final dt = DateTime.parse(reservation.startTime!);
        formattedDate = DateFormat('d MMMM yyyy', 'tr_TR').format(dt);
        formattedTime = DateFormat('HH:mm', 'tr_TR').format(dt);
      } catch (_) {
        formattedDate = reservation.startTime ?? '-';
      }
    }

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        gradient: isActive
            ? const LinearGradient(
                colors: [Color(0xFF1E293B), Color(0xFF1A1F3A)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              )
            : null,
        color: isActive ? null : const Color(0xFF1E293B).withOpacity(0.5),
        borderRadius: BorderRadius.circular(16),
        border: isActive
            ? Border.all(
                color: const Color(0xFF7A1DD1).withOpacity(0.3), width: 1)
            : null,
      ),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header: Business name + status badge
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Expanded(
                  child: Text(
                    reservation.businessName ??
                        'İşletme #${reservation.businessId}',
                    style: GoogleFonts.outfit(
                      fontSize: 16,
                      fontWeight: FontWeight.w700,
                      color: isActive ? Colors.white : Colors.white60,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
                const SizedBox(width: 8),
                Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: statusInfo.color.withOpacity(0.15),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    statusInfo.label,
                    style: GoogleFonts.outfit(
                      fontSize: 11,
                      fontWeight: FontWeight.w700,
                      color: statusInfo.color,
                    ),
                  ),
                ),
              ],
            ),

            const SizedBox(height: 12),

            // Info rows
            Row(
              children: [
                _buildInfoChip(
                    Icons.calendar_today_outlined, formattedDate, isActive),
                const SizedBox(width: 16),
                _buildInfoChip(
                    Icons.access_time_rounded, formattedTime, isActive),
                const SizedBox(width: 16),
                _buildInfoChip(Icons.people_outline,
                    '${reservation.guestCount} kişi', isActive),
              ],
            ),

            // Resource name
            if (reservation.resourceName != null) ...[
              const SizedBox(height: 8),
              Row(
                children: [
                  Icon(Icons.table_bar_outlined,
                      size: 14,
                      color: isActive ? Colors.white38 : Colors.white24),
                  const SizedBox(width: 4),
                  Text(
                    reservation.resourceName!,
                    style: GoogleFonts.outfit(
                      fontSize: 12,
                      color: isActive ? Colors.white38 : Colors.white24,
                    ),
                  ),
                ],
              ),
            ],

            // Note
            if (reservation.note != null && reservation.note!.isNotEmpty) ...[
              const SizedBox(height: 8),
              Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Icon(Icons.note_outlined,
                      size: 14,
                      color: isActive ? Colors.white38 : Colors.white24),
                  const SizedBox(width: 4),
                  Expanded(
                    child: Text(
                      reservation.note!,
                      style: GoogleFonts.outfit(
                        fontSize: 12,
                        fontStyle: FontStyle.italic,
                        color: isActive ? Colors.white38 : Colors.white24,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                ],
              ),
            ],

            // Price
            if (reservation.totalAmount != null &&
                reservation.totalAmount! > 0) ...[
              const SizedBox(height: 12),
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                decoration: BoxDecoration(
                  color: const Color(0xFF22C55E).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Text(
                  '${reservation.totalAmount!.toStringAsFixed(2)} TL',
                  style: GoogleFonts.outfit(
                    fontSize: 14,
                    fontWeight: FontWeight.w700,
                    color: const Color(0xFF22C55E),
                  ),
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }

  Widget _buildInfoChip(IconData icon, String text, bool isActive) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon,
            size: 14,
            color: isActive ? const Color(0xFF7A1DD1) : Colors.white24),
        const SizedBox(width: 4),
        Text(
          text,
          style: GoogleFonts.outfit(
            fontSize: 12,
            fontWeight: FontWeight.w500,
            color: isActive ? Colors.white70 : Colors.white38,
          ),
        ),
      ],
    );
  }

  _StatusInfo _getStatusInfo(String status) {
    switch (status) {
      case 'confirmed':
        return _StatusInfo('Onaylandı', const Color(0xFF22C55E));
      case 'pending':
        return _StatusInfo('Bekliyor', const Color(0xFFF59E0B));
      case 'pending_payment':
        return _StatusInfo('Ödeme Bekliyor', const Color(0xFFF97316));
      case 'checked_in':
        return _StatusInfo('Giriş Yapıldı', const Color(0xFF3B82F6));
      case 'completed':
        return _StatusInfo('Tamamlandı', const Color(0xFF6B7280));
      case 'cancelled':
        return _StatusInfo('İptal', const Color(0xFFEF4444));
      case 'no_show':
        return _StatusInfo('Gelmedi', const Color(0xFFEF4444));
      default:
        return _StatusInfo(status, const Color(0xFF6B7280));
    }
  }
}

class _StatusInfo {
  final String label;
  final Color color;
  _StatusInfo(this.label, this.color);
}
