import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/repositories/reservation_repository.dart';
import '../../domain/models/reservation.dart';

final reservationRepositoryProvider =
    Provider((ref) => ReservationRepository());

// Provider to fetch user's reservations list
final myReservationsProvider = FutureProvider<List<Reservation>>((ref) async {
  final repo = ref.watch(reservationRepositoryProvider);
  return repo.getMyReservations();
});

final reservationControllerProvider = StateNotifierProvider<
    ReservationController, AsyncValue<Map<String, dynamic>?>>((ref) {
  return ReservationController(ref.watch(reservationRepositoryProvider));
});

class ReservationController
    extends StateNotifier<AsyncValue<Map<String, dynamic>?>> {
  final ReservationRepository _repository;

  ReservationController(this._repository) : super(const AsyncData(null));

  Future<void> createReservation({
    required int businessId,
    required String reservationDate,
    required String reservationTime,
    required int guestCount,
    required String paymentMethod,
    List<Map<String, dynamic>>? services,
    String? specialRequests,
  }) async {
    state = const AsyncLoading();
    try {
      final result = await _repository.createReservation(
        businessId: businessId,
        reservationDate: reservationDate,
        reservationTime: reservationTime,
        guestCount: guestCount,
        paymentMethod: paymentMethod,
        services: services,
        specialRequests: specialRequests,
      );
      state = AsyncData(result);
    } catch (e, st) {
      state = AsyncError(e, st);
    }
  }
}
