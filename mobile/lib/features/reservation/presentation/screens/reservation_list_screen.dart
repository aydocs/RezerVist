import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';

import '../providers/reservation_provider.dart';
import '../widgets/reservation_card.dart';
import '../widgets/reservation_list_empty_state.dart';

class ReservationListScreen extends ConsumerWidget {
  const ReservationListScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final reservationsAsync = ref.watch(myReservationsProvider);

    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        title: Text(
          'Randevularım',
          style: GoogleFonts.outfit(
            fontSize: 22,
            fontWeight: FontWeight.w700,
            color: Colors.black,
          ),
        ),
        centerTitle: false,
      ),
      body: RefreshIndicator(
        color: const Color(0xFF7A1DD1),
        onRefresh: () async {
          ref.invalidate(myReservationsProvider);
        },
        child: reservationsAsync.when(
          loading: () => const Center(
            child: CircularProgressIndicator(color: Color(0xFF7A1DD1)),
          ),
          error: (e, stack) => Center(
            child: Padding(
              padding: const EdgeInsets.all(24),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                   Icon(Icons.error_outline, color: Colors.red.shade400, size: 64),
                  const SizedBox(height: 16),
                  Text(
                    'Bir Hata Oluştu',
                    style: GoogleFonts.outfit(
                      fontSize: 20,
                      fontWeight: FontWeight.w700,
                      color: Colors.black,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    e.toString(),
                    textAlign: TextAlign.center,
                    style: GoogleFonts.outfit(
                      fontSize: 14,
                      color: Colors.black54,
                    ),
                  ),
                  const SizedBox(height: 24),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () => ref.invalidate(myReservationsProvider),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF7A1DD1),
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 16),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16),
                        ),
                      ),
                      child: const Text('Tekrar Dene'),
                    ),
                  ),
                ],
              ),
            ),
          ),
          data: (reservations) {
            if (reservations.isEmpty) {
              return const ReservationListEmptyState();
            }

            // Split into active and past
            final active = reservations
                .where((r) =>
                    r.status == 'confirmed' ||
                    r.status == 'pending' ||
                    r.status == 'pending_payment' ||
                    r.status == 'checked_in')
                .toList();
            final past = reservations
                .where((r) =>
                    r.status == 'completed' ||
                    r.status == 'cancelled' ||
                    r.status == 'no_show')
                .toList();

            return ListView(
              padding: const EdgeInsets.all(16),
              children: [
                if (active.isNotEmpty) ...[
                  _buildSectionHeader('Aktif Randevular', active.length),
                  const SizedBox(height: 8),
                  ...active.map(
                      (r) => ReservationCard(reservation: r, isActive: true)),
                  const SizedBox(height: 24),
                ],
                if (past.isNotEmpty) ...[
                  _buildSectionHeader('Geçmiş', past.length),
                  const SizedBox(height: 8),
                  ...past.map(
                      (r) => ReservationCard(reservation: r, isActive: false)),
                ],
                const SizedBox(height: 100),
              ],
            );
          },
        ),
      ),
    );
  }

  Widget _buildSectionHeader(String title, int count) {
    return Row(
      children: [
        Text(
          title,
          style: GoogleFonts.outfit(
            fontSize: 18,
            fontWeight: FontWeight.w700,
            color: Colors.black,
          ),
        ),
        const SizedBox(width: 8),
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
          decoration: BoxDecoration(
            color: const Color(0xFF7A1DD1).withOpacity(0.2),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Text(
            '$count',
            style: GoogleFonts.outfit(
              fontSize: 12,
              fontWeight: FontWeight.w700,
              color: const Color(0xFF7A1DD1),
            ),
          ),
        ),
      ],
    );
  }
}
