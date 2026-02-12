import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import '../providers/pos_provider.dart';

class OrderDetailScreen extends ConsumerWidget {
  final int resourceId;
  final String tableName;

  const OrderDetailScreen({
    super.key,
    required this.resourceId,
    required this.tableName,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final orderAsync = ref.watch(posOrderProvider(resourceId));

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: Text(
          tableName,
          style: GoogleFonts.outfit(
            fontWeight: FontWeight.w700,
            color: const Color(0xFF0F172A),
          ),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        iconTheme: const IconThemeData(color: Color(0xFF0F172A)),
      ),
      body: orderAsync.when(
        data: (order) {
          final items = (order['items'] as List?) ?? [];
          final totalAmount =
              double.tryParse(order['total_amount'].toString()) ?? 0.0;
          final paidAmount =
              double.tryParse(order['paid_amount'].toString()) ?? 0.0;
          final remainingAmount = totalAmount - paidAmount;

          return SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildFinancialSummary(
                    totalAmount, paidAmount, remainingAmount),
                const SizedBox(height: 24),
                Text(
                  'Sipariş Detayları',
                  style: GoogleFonts.outfit(
                    fontSize: 18,
                    fontWeight: FontWeight.w800,
                    color: const Color(0xFF0F172A),
                  ),
                ),
                const SizedBox(height: 12),
                ListView.separated(
                  physics: const NeverScrollableScrollPhysics(),
                  shrinkWrap: true,
                  itemCount: items.length,
                  separatorBuilder: (context, index) => const Divider(),
                  itemBuilder: (context, index) {
                    final item = items[index];
                    return _buildOrderItem(item);
                  },
                ),
              ],
            ),
          );
        },
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, s) => Center(child: Text('Hata: $e')),
      ),
    );
  }

  Widget _buildFinancialSummary(double total, double paid, double remaining) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        children: [
          _buildSummaryRow('Toplam Tutar', total, const Color(0xFF0F172A),
              isBold: true),
          const Divider(height: 24),
          _buildSummaryRow('Ödenen', paid, const Color(0xFF10B981)), // Green
          const SizedBox(height: 8),
          _buildSummaryRow('Kalan', remaining, const Color(0xFFEF4444),
              isBold: true), // Red
        ],
      ),
    );
  }

  Widget _buildSummaryRow(String label, double amount, Color color,
      {bool isBold = false}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: GoogleFonts.outfit(
            fontSize: 16,
            fontWeight: isBold ? FontWeight.w700 : FontWeight.w500,
            color: const Color(0xFF64748B),
          ),
        ),
        Text(
          '${amount.toStringAsFixed(2)} TL',
          style: GoogleFonts.outfit(
            fontSize: 18,
            fontWeight: FontWeight.w800,
            color: color,
          ),
        ),
      ],
    );
  }

  Widget _buildOrderItem(Map<String, dynamic> item) {
    final quantity = double.tryParse(item['quantity'].toString()) ?? 0;
    final unitPrice = double.tryParse(item['unit_price'].toString()) ?? 0;
    final totalPrice = double.tryParse(item['total_price'].toString()) ?? 0;
    final name = item['name'] ?? 'Ürün';
    final notes = item['notes'];
    final options = item['selected_options'];

    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
            decoration: BoxDecoration(
              color: const Color(0xFFF1F5F9),
              borderRadius: BorderRadius.circular(6),
            ),
            child: Text(
              '${quantity.toStringAsFixed(0)}x',
              style: GoogleFonts.outfit(
                fontWeight: FontWeight.w700,
                color: const Color(0xFF334155),
              ),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  name,
                  style: GoogleFonts.outfit(
                    fontWeight: FontWeight.w600,
                    fontSize: 16,
                    color: const Color(0xFF0F172A),
                  ),
                ),
                Text(
                  'Birim: ${unitPrice.toStringAsFixed(2)} TL',
                  style: GoogleFonts.outfit(
                    fontSize: 12,
                    color: const Color(0xFF64748B),
                  ),
                ),
                if (options != null && (options as List).isNotEmpty) ...[
                  const SizedBox(height: 4),
                  Text(
                    (options as List).join(', '),
                    style: GoogleFonts.outfit(
                      fontSize: 12,
                      color: const Color(0xFF64748B),
                    ),
                  ),
                ],
                if (notes != null && notes.toString().isNotEmpty) ...[
                  const SizedBox(height: 4),
                  Text(
                    'Not: $notes',
                    style: GoogleFonts.outfit(
                      fontSize: 12,
                      fontStyle: FontStyle.italic,
                      color: const Color(0xFFEF4444),
                    ),
                  ),
                ],
              ],
            ),
          ),
          Text(
            '${totalPrice.toStringAsFixed(2)} TL',
            style: GoogleFonts.outfit(
              fontWeight: FontWeight.w700,
              color: const Color(0xFF0F172A),
            ),
          ),
        ],
      ),
    );
  }
}
