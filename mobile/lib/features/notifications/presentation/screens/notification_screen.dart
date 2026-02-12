import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import '../providers/notification_provider.dart';
import '../widgets/notification_item.dart';

class NotificationScreen extends ConsumerWidget {
  const NotificationScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final notificationsAsync = ref.watch(notificationsProvider);

    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: true,
        leading: IconButton(
          onPressed: () => context.pop(),
          icon: const Icon(Icons.arrow_back_ios_new_rounded,
              color: Color(0xFF1E293B), size: 20),
        ),
        title: Text(
          'Bildirimler',
          style: GoogleFonts.outfit(
            fontSize: 18,
            fontWeight: FontWeight.w700,
            color: const Color(0xFF1E293B),
          ),
        ),
        actions: [
          IconButton(
            onPressed: () {
              ref.read(notificationsProvider.notifier).markAllAsRead();
            },
            icon: const Icon(Icons.done_all_rounded, color: Color(0xFF7A1DD1)),
            tooltip: 'Tümünü Okundu İşaretle',
          ),
        ],
      ),
      body: notificationsAsync.when(
        data: (notifications) {
          if (notifications.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.notifications_off_outlined,
                      size: 64, color: Colors.grey[300]),
                  const SizedBox(height: 16),
                  Text(
                    'Henüz bildirim yok',
                    style: GoogleFonts.outfit(
                      fontSize: 16,
                      fontWeight: FontWeight.w600,
                      color: Colors.grey[400],
                    ),
                  ),
                ],
              ),
            );
          }

          return ListView.separated(
            padding: const EdgeInsets.all(16),
            itemCount: notifications.length,
            separatorBuilder: (context, index) => const SizedBox(height: 12),
            itemBuilder: (context, index) {
              final notification = notifications[index];
              final isRead = notification.readAt != null;

              return NotificationItem(
                notification: notification
                    .toJson(), // Assuming notification is a model, otherwise just pass it
                isRead: isRead,
                onTap: () {
                  if (!isRead) {
                    ref
                        .read(notificationsProvider.notifier)
                        .markAsRead(notification.id);
                  }
                  // Handle navigation if 'url' exists in data
                },
              );
            },
          );
        },
        loading: () => const Center(
          child: CircularProgressIndicator(color: Color(0xFF7A1DD1)),
        ),
        error: (err, stack) => Center(
          child: Text(
            'Hata oluştu: $err',
            style: GoogleFonts.outfit(color: Colors.red),
          ),
        ),
      ),
    );
  }
}
