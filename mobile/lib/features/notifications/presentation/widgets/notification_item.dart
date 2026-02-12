import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class NotificationItem extends StatelessWidget {
  final Map<String, dynamic> notification;
  final bool isRead;
  final VoidCallback onTap;

  const NotificationItem({
    super.key,
    required this.notification,
    required this.isRead,
    required this.onTap,
  });

  String _timeAgo(DateTime dateTime) {
    final now = DateTime.now();
    final local = dateTime.toLocal();
    final diff = now.difference(local);

    if (diff.inSeconds < 60) return 'Az önce';
    if (diff.inMinutes < 60) return '${diff.inMinutes} dk önce';
    if (diff.inHours < 24) return '${diff.inHours} saat önce';
    if (diff.inDays < 7) return '${diff.inDays} gün önce';
    if (diff.inDays < 30) return '${(diff.inDays / 7).floor()} hafta önce';
    return '${(diff.inDays / 30).floor()} ay önce';
  }

  @override
  Widget build(BuildContext context) {
    final data = notification['data'] as Map<String, dynamic>? ?? {};
    final title = data['title'] ?? 'Bildirim';
    final message = data['message'] ?? data['line1'] ?? 'İçerik yok';
    final createdAt =
        DateTime.tryParse(notification['created_at'] ?? '') ?? DateTime.now();

    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: isRead ? Colors.white : const Color(0xFFF5F3FF),
          borderRadius: BorderRadius.circular(16),
          border: Border.all(
            color: isRead
                ? const Color(0xFFE2E8F0)
                : const Color(0xFF7A1DD1).withOpacity(0.2),
          ),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.02),
              blurRadius: 10,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              padding: const EdgeInsets.all(10),
              decoration: BoxDecoration(
                color: isRead
                    ? const Color(0xFFF1F5F9)
                    : const Color(0xFF7A1DD1).withOpacity(0.1),
                shape: BoxShape.circle,
              ),
              child: Icon(
                isRead
                    ? Icons.notifications_none_rounded
                    : Icons.notifications_active_rounded,
                color:
                    isRead ? const Color(0xFF94A3B8) : const Color(0xFF7A1DD1),
                size: 20,
              ),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Expanded(
                        child: Text(
                          title,
                          style: GoogleFonts.outfit(
                            fontSize: 15,
                            fontWeight:
                                isRead ? FontWeight.w600 : FontWeight.w700,
                            color: const Color(0xFF1E293B),
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                      Text(
                        _timeAgo(createdAt),
                        style: GoogleFonts.outfit(
                          fontSize: 12,
                          fontWeight: FontWeight.w500,
                          color: const Color(0xFF94A3B8),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 4),
                  Text(
                    message,
                    style: GoogleFonts.outfit(
                      fontSize: 13,
                      fontWeight: FontWeight.w400,
                      color: const Color(0xFF64748B),
                      height: 1.4,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
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
