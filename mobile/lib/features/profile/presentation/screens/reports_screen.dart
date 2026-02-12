import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
// Note: In a real scenario, we'd use fl_chart. For this implementation,
// I'll create a custom bar chart for the "Daily Trend" to match the UI precisely.

import '../providers/analytics_provider.dart';

class ReportsScreen extends ConsumerWidget {
  const ReportsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final weeklyTrend = ref.watch(weeklyTrendProvider);
    final topProducts = ref.watch(topProductsProvider);

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text(
          'Gelir Analizi',
          style: GoogleFonts.outfit(
            fontWeight: FontWeight.w700,
            fontSize: 20,
            color: const Color(0xFF0F172A),
          ),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: true,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded,
              color: Color(0xFF0F172A), size: 20),
          onPressed: () => Navigator.of(context).pop(),
        ),
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          ref.invalidate(weeklyTrendProvider);
          ref.invalidate(topProductsProvider);
        },
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          padding: const EdgeInsets.all(24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Haftalık Gelir Trendi',
                style: GoogleFonts.outfit(
                  fontSize: 18,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFF0F172A),
                ),
              ),
              const SizedBox(height: 16),
              weeklyTrend.when(
                data: (data) => _buildChartSection(data),
                loading: () => _buildLoadingSection(),
                error: (e, s) => _buildErrorSection('Trend yüklenemedi'),
              ),
              const SizedBox(height: 32),
              Text(
                'En Çok Satan Ürünler',
                style: GoogleFonts.outfit(
                  fontSize: 18,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFF0F172A),
                ),
              ),
              const SizedBox(height: 16),
              topProducts.when(
                data: (data) => _buildProductsSection(data),
                loading: () => _buildLoadingSection(),
                error: (e, s) => _buildErrorSection('Ürünler yüklenemedi'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildLoadingSection() {
    return Container(
      height: 200,
      width: double.infinity,
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC),
        borderRadius: BorderRadius.circular(24),
      ),
      child: const Center(
          child: CircularProgressIndicator(color: Color(0xFF7A1DD1))),
    );
  }

  Widget _buildErrorSection(String message) {
    return Container(
      height: 100,
      width: double.infinity,
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC),
        borderRadius: BorderRadius.circular(24),
      ),
      child: Center(
        child: Text(
          message,
          style: GoogleFonts.outfit(color: const Color(0xFFEF4444)),
        ),
      ),
    );
  }

  Widget _buildChartSection(List<Map<String, dynamic>> data) {
    return Container(
      height: 220,
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC),
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: const Color(0xFFF1F5F9)),
      ),
      child: data.isEmpty
          ? Center(
              child: Text(
                'Henüz trend verisi bulunmuyor',
                style: GoogleFonts.outfit(color: const Color(0xFF64748B)),
              ),
            )
          : Row(
              crossAxisAlignment: CrossAxisAlignment.end,
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: data.take(7).map((item) {
                final double revenue = (item['revenue'] ?? 0.0).toDouble();
                final String date = (item['date'] ?? '').toString();
                final double height = (revenue / 5000 * 150).clamp(10.0, 150.0);
                return Column(
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    Container(
                      width: 12,
                      height: height,
                      decoration: BoxDecoration(
                        color: const Color(0xFF7A1DD1),
                        borderRadius: BorderRadius.circular(6),
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      date.split('-').last,
                      style: GoogleFonts.outfit(
                          fontSize: 10, color: const Color(0xFF64748B)),
                    ),
                  ],
                );
              }).toList(),
            ),
    );
  }

  Widget _buildProductsSection(List<Map<String, dynamic>> data) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(8),
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC),
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: const Color(0xFFF1F5F9)),
      ),
      child: data.isEmpty
          ? Padding(
              padding: const EdgeInsets.all(20),
              child: Center(
                child: Text(
                  'Henüz ürün verisi bulunmuyor',
                  style: GoogleFonts.outfit(color: const Color(0xFF64748B)),
                ),
              ),
            )
          : Column(
              children: data.take(5).map((item) {
                return ListTile(
                  leading: Container(
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: const Color(0xFF7A1DD1).withOpacity(0.1),
                      shape: BoxShape.circle,
                    ),
                    child: const Icon(Icons.fastfood_rounded,
                        color: Color(0xFF7A1DD1), size: 20),
                  ),
                  title: Text(
                    item['name'] ?? 'Adsız Ürün',
                    style: GoogleFonts.outfit(
                        fontWeight: FontWeight.w600, fontSize: 14),
                  ),
                  subtitle: Text(
                    '${item['total_quantity'] ?? 0} Adet Satıldı',
                    style: GoogleFonts.outfit(
                        fontSize: 12, color: const Color(0xFF64748B)),
                  ),
                  trailing: Text(
                    '${(item['total_revenue'] ?? 0.0).toStringAsFixed(2)} TL',
                    style: GoogleFonts.outfit(
                        fontWeight: FontWeight.bold,
                        color: const Color(0xFF0F172A)),
                  ),
                );
              }).toList(),
            ),
    );
  }
}
