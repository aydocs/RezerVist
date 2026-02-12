import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class DigitalReceiptScreen extends StatelessWidget {
  final Map<String, dynamic> receipt;

  const DigitalReceiptScreen({super.key, required this.receipt});

  @override
  Widget build(BuildContext context) {
    final items = List<Map<String, dynamic>>.from(receipt['items'] ?? []);
    final businessName = receipt['business_name'] ?? '';
    final table = receipt['table'] ?? '';
    final date = receipt['date'] ?? '';
    final total = (receipt['total'] as num?)?.toDouble() ?? 0;
    final paid = (receipt['paid'] as num?)?.toDouble() ?? 0;
    final method = receipt['method'] ?? '';

    return Scaffold(
      backgroundColor: const Color(0xFF0F172A),
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.close_rounded, color: Colors.white),
          onPressed: () {
            Navigator.of(context).popUntil((route) => route.isFirst);
          },
        ),
        title: Text(
          'Dijital Fiş',
          style: GoogleFonts.outfit(
            fontSize: 18,
            fontWeight: FontWeight.w700,
            color: Colors.white,
          ),
        ),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            // Success badge
            Container(
              width: 80,
              height: 80,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                gradient: const LinearGradient(
                  colors: [Color(0xFF22C55E), Color(0xFF10B981)],
                ),
                boxShadow: [
                  BoxShadow(
                    color: const Color(0xFF22C55E).withOpacity(0.3),
                    blurRadius: 24,
                  ),
                ],
              ),
              child: const Icon(Icons.check_rounded,
                  color: Colors.white, size: 48),
            ),
            const SizedBox(height: 16),
            Text(
              'Ödeme Başarılı! ✨',
              style: GoogleFonts.outfit(
                fontSize: 24,
                fontWeight: FontWeight.w800,
                color: Colors.white,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              'Afiyet olsun!',
              style: GoogleFonts.outfit(
                fontSize: 14,
                color: const Color(0xFF94A3B8),
              ),
            ),
            const SizedBox(height: 32),

            // Receipt card
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: const Color(0xFF1E293B),
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: const Color(0xFF334155)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Business info
                  Center(
                    child: Column(
                      children: [
                        Text(
                          businessName,
                          style: GoogleFonts.outfit(
                            fontSize: 20,
                            fontWeight: FontWeight.w700,
                            color: Colors.white,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          '$table • $date',
                          style: GoogleFonts.outfit(
                            fontSize: 13,
                            color: const Color(0xFF94A3B8),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 20),

                  // Dashed divider
                  Row(
                    children: List.generate(
                        40,
                        (i) => Expanded(
                              child: Container(
                                color: i.isEven
                                    ? const Color(0xFF475569)
                                    : Colors.transparent,
                                height: 1,
                              ),
                            )),
                  ),
                  const SizedBox(height: 16),

                  // Items
                  ...items.map((item) => Padding(
                        padding: const EdgeInsets.symmetric(vertical: 4),
                        child: Row(
                          children: [
                            Expanded(
                              child: Text(
                                '${item['name']}',
                                style: GoogleFonts.outfit(
                                    fontSize: 14, color: Colors.white),
                              ),
                            ),
                            Text(
                              'x${item['qty']}',
                              style: GoogleFonts.outfit(
                                  fontSize: 12, color: const Color(0xFF94A3B8)),
                            ),
                            const SizedBox(width: 16),
                            Text(
                              '${(item['price'] as num).toStringAsFixed(2)} TL',
                              style: GoogleFonts.outfit(
                                fontSize: 14,
                                fontWeight: FontWeight.w600,
                                color: Colors.white,
                              ),
                            ),
                          ],
                        ),
                      )),

                  const SizedBox(height: 16),
                  // Dashed divider
                  Row(
                    children: List.generate(
                        40,
                        (i) => Expanded(
                              child: Container(
                                color: i.isEven
                                    ? const Color(0xFF475569)
                                    : Colors.transparent,
                                height: 1,
                              ),
                            )),
                  ),
                  const SizedBox(height: 16),

                  // Totals
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text('Toplam',
                          style: GoogleFonts.outfit(
                              fontSize: 14, color: const Color(0xFF94A3B8))),
                      Text('${total.toStringAsFixed(2)} TL',
                          style: GoogleFonts.outfit(
                              fontSize: 14, color: Colors.white)),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text('Ödenen',
                          style: GoogleFonts.outfit(
                              fontSize: 16,
                              fontWeight: FontWeight.w700,
                              color: Colors.white)),
                      Text(
                        '${paid.toStringAsFixed(2)} TL',
                        style: GoogleFonts.outfit(
                            fontSize: 22,
                            fontWeight: FontWeight.w800,
                            color: const Color(0xFF22C55E)),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text('Ödeme Yöntemi',
                          style: GoogleFonts.outfit(
                              fontSize: 13, color: const Color(0xFF94A3B8))),
                      Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 10, vertical: 4),
                        decoration: BoxDecoration(
                          color: const Color(0xFF7A1DD1).withOpacity(0.15),
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(method,
                            style: GoogleFonts.outfit(
                                fontSize: 12,
                                fontWeight: FontWeight.w600,
                                color: const Color(0xFF7A1DD1))),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Verified badge
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: const Color(0xFF22C55E).withOpacity(0.1),
                borderRadius: BorderRadius.circular(14),
                border:
                    Border.all(color: const Color(0xFF22C55E).withOpacity(0.3)),
              ),
              child: Row(
                children: [
                  const Icon(Icons.verified_rounded,
                      color: Color(0xFF22C55E), size: 24),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Text(
                      'Bu fiş ödeme kanıtı olarak garson veya kasaya gösterilebilir.',
                      style: GoogleFonts.outfit(
                          fontSize: 13, color: const Color(0xFF22C55E)),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 32),

            // Back button
            SizedBox(
              width: double.infinity,
              height: 52,
              child: ElevatedButton(
                onPressed: () =>
                    Navigator.of(context).popUntil((route) => route.isFirst),
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF1E293B),
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16)),
                  elevation: 0,
                ),
                child: Text('Ana Sayfaya Dön',
                    style: GoogleFonts.outfit(
                        fontSize: 16,
                        fontWeight: FontWeight.w600,
                        color: Colors.white)),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
