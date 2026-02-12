import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import '../providers/pos_provider.dart';

class AnalyticsScreen extends ConsumerWidget {
  const AnalyticsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final weeklyAsync = ref.watch(weeklyTrendProvider);
    final topProductsAsync = ref.watch(topProductsProvider);

    return Scaffold(
      backgroundColor: const Color(0xFF0F172A),
      appBar: AppBar(
        backgroundColor: const Color(0xFF0F172A),
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_rounded, color: Colors.white),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          'Gelir Analizi',
          style: GoogleFonts.outfit(
            fontSize: 20,
            fontWeight: FontWeight.w700,
            color: Colors.white,
          ),
        ),
      ),
      body: RefreshIndicator(
        color: const Color(0xFF7A1DD1),
        onRefresh: () async {
          ref.invalidate(weeklyTrendProvider);
          ref.invalidate(topProductsProvider);
        },
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            // Weekly Revenue Trend
            _buildSectionTitle('Haftalık Gelir Trendi'),
            const SizedBox(height: 12),
            weeklyAsync.when(
              loading: () => _buildLoadingCard(),
              error: (e, _) => _buildErrorCard('Haftalık trend yüklenemedi'),
              data: (data) => _buildWeeklyChart(data),
            ),
            const SizedBox(height: 24),

            // Top Products
            _buildSectionTitle('En Çok Satan Ürünler'),
            const SizedBox(height: 12),
            topProductsAsync.when(
              loading: () => _buildLoadingCard(),
              error: (e, _) => _buildErrorCard('Ürün verileri yüklenemedi'),
              data: (data) => _buildTopProductsList(data),
            ),
            const SizedBox(height: 80),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Text(
      title,
      style: GoogleFonts.outfit(
        fontSize: 18,
        fontWeight: FontWeight.w700,
        color: Colors.white,
      ),
    );
  }

  Widget _buildLoadingCard() {
    return Container(
      height: 180,
      decoration: BoxDecoration(
        color: const Color(0xFF1E293B),
        borderRadius: BorderRadius.circular(16),
      ),
      child: const Center(
        child: CircularProgressIndicator(color: Color(0xFF7A1DD1)),
      ),
    );
  }

  Widget _buildErrorCard(String message) {
    return Container(
      height: 100,
      decoration: BoxDecoration(
        color: const Color(0xFF1E293B),
        borderRadius: BorderRadius.circular(16),
      ),
      child: Center(
        child: Text(message, style: GoogleFonts.outfit(color: Colors.white54)),
      ),
    );
  }

  Widget _buildWeeklyChart(List<Map<String, dynamic>> data) {
    if (data.isEmpty) {
      return _buildErrorCard('Bu hafta henüz veri yok');
    }

    double maxRevenue = 0;
    for (final d in data) {
      final revenue = double.tryParse(d['revenue']?.toString() ?? '0') ?? 0;
      if (revenue > maxRevenue) maxRevenue = revenue;
    }
    if (maxRevenue == 0) maxRevenue = 1;

    // Total revenue for week
    double totalRevenue = 0;
    int totalOrders = 0;
    for (final d in data) {
      totalRevenue += double.tryParse(d['revenue']?.toString() ?? '0') ?? 0;
      totalOrders += (d['order_count'] as num?)?.toInt() ?? 0;
    }

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF1E293B), Color(0xFF1A1F3A)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFF7A1DD1).withOpacity(0.2)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Summary row
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    '${totalRevenue.toStringAsFixed(0)} TL',
                    style: GoogleFonts.outfit(
                      fontSize: 28,
                      fontWeight: FontWeight.w800,
                      color: const Color(0xFF22C55E),
                    ),
                  ),
                  Text(
                    'Toplam Gelir (7 gün)',
                    style:
                        GoogleFonts.outfit(fontSize: 12, color: Colors.white38),
                  ),
                ],
              ),
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                decoration: BoxDecoration(
                  color: const Color(0xFF7A1DD1).withOpacity(0.15),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  '$totalOrders sipariş',
                  style: GoogleFonts.outfit(
                    fontSize: 13,
                    fontWeight: FontWeight.w600,
                    color: const Color(0xFF7A1DD1),
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 24),

          // Bar chart
          SizedBox(
            height: 120,
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.end,
              children: data.map((d) {
                final revenue =
                    double.tryParse(d['revenue']?.toString() ?? '0') ?? 0;
                final date = d['date']?.toString() ?? '';
                final ratio = revenue / maxRevenue;
                String dayLabel = '';
                try {
                  dayLabel =
                      DateFormat('EEE', 'tr_TR').format(DateTime.parse(date));
                } catch (_) {
                  dayLabel = date.length > 5 ? date.substring(5) : date;
                }

                return Expanded(
                  child: Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 3),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.end,
                      children: [
                        Text(
                          '${revenue.toStringAsFixed(0)} TL',
                          style: GoogleFonts.outfit(
                              fontSize: 9, color: Colors.white38),
                        ),
                        const SizedBox(height: 4),
                        Container(
                          height: (ratio * 80).clamp(4, 80),
                          decoration: BoxDecoration(
                            gradient: LinearGradient(
                              colors: [
                                const Color(0xFF7A1DD1),
                                const Color(0xFF7A1DD1).withOpacity(0.6),
                              ],
                              begin: Alignment.topCenter,
                              end: Alignment.bottomCenter,
                            ),
                            borderRadius: BorderRadius.circular(6),
                          ),
                        ),
                        const SizedBox(height: 6),
                        Text(
                          dayLabel,
                          style: GoogleFonts.outfit(
                              fontSize: 10, color: Colors.white54),
                        ),
                      ],
                    ),
                  ),
                );
              }).toList(),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTopProductsList(List<Map<String, dynamic>> data) {
    if (data.isEmpty) {
      return _buildErrorCard('Ürün verisi bulunamadı');
    }

    return Container(
      decoration: BoxDecoration(
        color: const Color(0xFF1E293B),
        borderRadius: BorderRadius.circular(16),
      ),
      child: Column(
        children: data.asMap().entries.map((entry) {
          final index = entry.key;
          final product = entry.value;
          final name = product['name'] ?? '-';
          final totalRevenue =
              double.tryParse(product['total_revenue']?.toString() ?? '0') ?? 0;
          final totalQuantity =
              (product['total_quantity'] as num?)?.toInt() ?? 0;

          return Column(
            children: [
              Padding(
                padding:
                    const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                child: Row(
                  children: [
                    // Rank badge
                    Container(
                      width: 28,
                      height: 28,
                      decoration: BoxDecoration(
                        color: index < 3
                            ? const Color(0xFF7A1DD1).withOpacity(0.2)
                            : Colors.white.withOpacity(0.05),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Center(
                        child: Text(
                          '${index + 1}',
                          style: GoogleFonts.outfit(
                            fontSize: 12,
                            fontWeight: FontWeight.w700,
                            color: index < 3
                                ? const Color(0xFF7A1DD1)
                                : Colors.white38,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(width: 12),

                    // Product name
                    Expanded(
                      child: Text(
                        name,
                        style: GoogleFonts.outfit(
                          fontSize: 14,
                          fontWeight: FontWeight.w600,
                          color: Colors.white,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),

                    // Quantity
                    Text(
                      '$totalQuantity adet',
                      style: GoogleFonts.outfit(
                          fontSize: 12, color: Colors.white38),
                    ),
                    const SizedBox(width: 12),

                    // Revenue
                    Text(
                      '${totalRevenue.toStringAsFixed(0)} TL',
                      style: GoogleFonts.outfit(
                        fontSize: 14,
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF22C55E),
                      ),
                    ),
                  ],
                ),
              ),
              if (index < data.length - 1)
                Divider(
                  height: 1,
                  color: Colors.white.withOpacity(0.05),
                  indent: 56,
                ),
            ],
          );
        }).toList(),
      ),
    );
  }
}
