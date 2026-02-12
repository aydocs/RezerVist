import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import '../providers/pos_provider.dart';
import 'order_details_screen.dart';
import 'analytics_screen.dart';

class OwnerDashboardScreen extends ConsumerWidget {
  const OwnerDashboardScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final summaryAsync = ref.watch(posSummaryProvider);
    final tablesAsync = ref.watch(posTablesProvider);

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: Text(
          'İşletme Paneli',
          style: GoogleFonts.outfit(
            fontWeight: FontWeight.w700,
            color: const Color(0xFF0F172A),
          ),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        iconTheme: const IconThemeData(color: Color(0xFF0F172A)),
        actions: [
          IconButton(
            onPressed: () {
              Navigator.push(context,
                  MaterialPageRoute(builder: (_) => const AnalyticsScreen()));
            },
            icon: const Icon(Icons.bar_chart_rounded, color: Color(0xFF7A1DD1)),
            tooltip: 'Gelir Analizi',
          ),
          IconButton(
            onPressed: () {
              ref.invalidate(posSummaryProvider);
              ref.invalidate(posTablesProvider);
            },
            icon: const Icon(Icons.refresh_rounded),
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          await Future.wait([
            ref.refresh(posSummaryProvider.future),
            ref.refresh(posTablesProvider.future),
          ]);
        },
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildSummaryCards(summaryAsync),
              const SizedBox(height: 24),
              Text(
                'Masa Durumu',
                style: GoogleFonts.outfit(
                  fontSize: 18,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF0F172A),
                ),
              ),
              const SizedBox(height: 12),
              _buildTablesGrid(tablesAsync),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildSummaryCards(AsyncValue<Map<String, dynamic>> summaryAsync) {
    return summaryAsync.when(
      data: (data) {
        final totalSales = data['total_sales'] ?? 0;
        final orderCount = data['order_count'] ?? 0;
        final cashTotal = data['cash_total'] ?? 0;
        final cardTotal = data['card_total'] ?? 0;

        return Column(
          children: [
            Row(
              children: [
                Expanded(
                  child: _MetricCard(
                    title: 'Toplam Ciro',
                    value: '${totalSales.toStringAsFixed(2)} TL',
                    icon: Icons.attach_money_rounded,
                    color: const Color(0xFF7A1DD1),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _MetricCard(
                    title: 'Sipariş',
                    value: '$orderCount',
                    icon: Icons.receipt_long_rounded,
                    color: const Color(0xFFF59E0B),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: _MetricCard(
                    title: 'Nakit',
                    value: '${cashTotal.toStringAsFixed(2)} TL',
                    icon: Icons.money_rounded,
                    color: const Color(0xFF10B981),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _MetricCard(
                    title: 'Kredi Kartı',
                    value: '${cardTotal.toStringAsFixed(2)} TL',
                    icon: Icons.credit_card_rounded,
                    color: const Color(0xFF3B82F6),
                  ),
                ),
              ],
            ),
          ],
        );
      },
      loading: () => const Center(child: CircularProgressIndicator()),
      error: (e, s) => Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: const Color(0xFFEF4444), width: 1.5),
        ),
        child: Text(
          'Veri yüklenemedi: $e',
          style: GoogleFonts.outfit(
            color: const Color(0xFFEF4444), // Red
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }

  Widget _buildTablesGrid(AsyncValue<List<Map<String, dynamic>>> tablesAsync) {
    return tablesAsync.when(
      data: (tables) {
        if (tables.isEmpty) {
          return const Center(child: Text('Masa bulunamadı.'));
        }

        return GridView.builder(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisCount: 3,
            crossAxisSpacing: 12,
            mainAxisSpacing: 12,
            childAspectRatio: 1.0,
          ),
          itemCount: tables.length,
          itemBuilder: (context, index) {
            final table = tables[index];
            final status = table['status'] ?? 'empty';
            final name = table['name'] ?? 'Masa ${table['id']}';
            final order = table['order'];

            Color bgColor;
            Color textColor;

            switch (status) {
              case 'occupied':
                bgColor = const Color(0xFFFEE2E2); // Red background
                textColor = const Color(0xFFDC2626);
                break;
              case 'reserved':
                bgColor = const Color(0xFFFEF3C7); // Yellow background
                textColor = const Color(0xFFD97706);
                break;
              case 'empty':
              default:
                bgColor = Colors.white;
                textColor = const Color(0xFF64748B);
                break;
            }

            return GestureDetector(
              onTap: () {
                if (status == 'occupied') {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => OrderDetailScreen(
                        resourceId: table['id'],
                        tableName: name,
                      ),
                    ),
                  );
                }
              },
              child: Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: bgColor,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(
                    color: status == 'empty'
                        ? const Color(0xFFE2E8F0)
                        : Colors.transparent,
                  ),
                  boxShadow: [
                    if (status !=
                        'occupied') // Reduce shadow for occupied to look clickable but not floating too much
                      BoxShadow(
                        color: textColor.withOpacity(0.05),
                        blurRadius: 4,
                        offset: const Offset(0, 2),
                      ),
                    if (status == 'occupied')
                      BoxShadow(
                        color: textColor.withOpacity(0.15),
                        blurRadius: 8,
                        offset: const Offset(0, 4),
                      ),
                  ],
                ),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      name,
                      style: GoogleFonts.outfit(
                        fontSize: 14,
                        fontWeight: FontWeight.w700,
                        color: textColor,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 4),
                    if (status == 'occupied' && order != null)
                      Text(
                        '${(order['remaining_amount'] ?? 0).toStringAsFixed(0)} TL',
                        style: GoogleFonts.outfit(
                          fontSize: 13,
                          fontWeight: FontWeight.w800,
                          color: textColor,
                        ),
                      ),
                    if (status == 'empty')
                      Icon(Icons.table_restaurant_rounded,
                          size: 20, color: textColor.withOpacity(0.5)),
                    if (status == 'reserved')
                      Text(
                        'Rezerve',
                        style: GoogleFonts.outfit(
                          fontSize: 10,
                          fontWeight: FontWeight.w600,
                          color: textColor,
                        ),
                      ),
                  ],
                ),
              ),
            );
          },
        );
      },
      loading: () => const Center(child: CircularProgressIndicator()),
      error: (e, s) => Center(child: Text('Masalar yüklenemedi: $e')),
    );
  }
}

class _MetricCard extends StatelessWidget {
  final String title;
  final String value;
  final IconData icon;
  final Color color;

  const _MetricCard({
    required this.title,
    required this.value,
    required this.icon,
    required this.color,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.04),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: color.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Icon(icon, color: color, size: 18),
              ),
              const Spacer(),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            value,
            style: GoogleFonts.outfit(
              fontSize: 20,
              fontWeight: FontWeight.w800,
              color: const Color(0xFF0F172A),
            ),
          ),
          const SizedBox(height: 4),
          Text(
            title,
            style: GoogleFonts.outfit(
              fontSize: 12,
              fontWeight: FontWeight.w600,
              color: const Color(0xFF64748B),
            ),
          ),
        ],
      ),
    );
  }
}
