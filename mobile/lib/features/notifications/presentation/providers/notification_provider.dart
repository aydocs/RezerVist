import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/models/notification_model.dart';
import '../../data/repositories/notification_repository.dart';

final notificationRepositoryProvider =
    Provider((ref) => NotificationRepository());

final notificationsProvider = StateNotifierProvider<NotificationNotifier,
    AsyncValue<List<NotificationModel>>>((ref) {
  return NotificationNotifier(ref.watch(notificationRepositoryProvider));
});

class NotificationNotifier
    extends StateNotifier<AsyncValue<List<NotificationModel>>> {
  final NotificationRepository _repository;

  NotificationNotifier(this._repository) : super(const AsyncValue.loading()) {
    fetchNotifications();
  }

  Future<void> fetchNotifications() async {
    try {
      state = const AsyncValue.loading();
      final notifications = await _repository.getNotifications();
      state = AsyncValue.data(notifications);
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }

  Future<void> markAsRead(String id) async {
    try {
      await _repository.markAsRead(id);
      // Optimistic update
      state.whenData((notifications) {
        final updated = notifications.map((n) {
          if (n.id == id) {
            // Return a new instance with readAt set to now (mocking)
            // or simply we can re-fetch. Re-fetching is safer for sync.
            return n;
          }
          return n;
        }).toList();
        state = AsyncValue.data(updated);
      });
      await fetchNotifications(); // Refresh to be sure
    } catch (e) {
      // Handle error
    }
  }

  Future<void> markAllAsRead() async {
    try {
      await _repository.markAllAsRead();
      await fetchNotifications();
    } catch (e) {
      // Handle error
    }
  }

  int get unreadCount {
    return state.maybeWhen(
      data: (notifications) =>
          notifications.where((n) => n.readAt == null).length,
      orElse: () => 0,
    );
  }
}
