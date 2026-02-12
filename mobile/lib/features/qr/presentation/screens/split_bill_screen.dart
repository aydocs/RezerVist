import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import '../providers/qr_provider.dart';
import 'digital_receipt_screen.dart';

class SplitBillScreen extends ConsumerStatefulWidget {
  final String sessionToken;
  final Map<String, dynamic> bill;

  const SplitBillScreen({
    super.key,
    required this.sessionToken,
    required this.bill,
  });

  @override
  ConsumerState<SplitBillScreen> createState() => _SplitBillScreenState();
}

class _SplitBillScreenState extends ConsumerState<SplitBillScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  int _personCount = 2;
  final Set<int> _selectedItemIds = {};
  double _customAmount = 0;
  bool _isPaying = false;
  String _selectedPayMethod = 'wallet';

  double get _remaining =>
      ((widget.bill['total_amount'] as num?)?.toDouble() ?? 0) -
      ((widget.bill['paid_amount'] as num?)?.toDouble() ?? 0);

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 3, vsync: this);
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  Future<void> _payAmount(double amount) async {
    if (_isPaying || amount <= 0) return;
    setState(() => _isPaying = true);

    try {
      final repo = ref.read(qrRepositoryProvider);
      final result = await repo.payBill(
        widget.sessionToken,
        paymentMethod: _selectedPayMethod,
        amount: amount,
      );

      if (mounted) {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (_) => DigitalReceiptScreen(
              receipt: Map<String, dynamic>.from(result['receipt'] ?? {}),
            ),
          ),
        );
      }
    } catch (e) {
      setState(() => _isPaying = false);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('$e'), backgroundColor: Colors.red[400]),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final items = List<Map<String, dynamic>>.from(widget.bill['items'] ?? []);

    return Scaffold(
      backgroundColor: const Color(0xFF0F172A),
      appBar: AppBar(
        backgroundColor: const Color(0xFF0F172A),
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_rounded, color: Colors.white),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text('Hesap Böl',
            style: GoogleFonts.outfit(
                fontSize: 18,
                fontWeight: FontWeight.w700,
                color: Colors.white)),
        centerTitle: true,
        bottom: TabBar(
          controller: _tabController,
          indicatorColor: const Color(0xFF7A1DD1),
          labelColor: Colors.white,
          unselectedLabelColor: const Color(0xFF94A3B8),
          labelStyle:
              GoogleFonts.outfit(fontSize: 13, fontWeight: FontWeight.w600),
          tabs: const [
            Tab(text: 'Eşit Böl'),
            Tab(text: 'Ürüne Göre'),
            Tab(text: 'Özel Tutar'),
          ],
        ),
      ),
      body: TabBarView(
        controller: _tabController,
        children: [
          _buildEqualSplit(),
          _buildItemSplit(items),
          _buildCustomSplit(),
        ],
      ),
    );
  }

  // ── Tab 1: Equal Split ──

  Widget _buildEqualSplit() {
    final perPerson = _remaining / _personCount;

    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        children: [
          const SizedBox(height: 24),
          Text('Kişi Sayısı',
              style: GoogleFonts.outfit(
                  fontSize: 14, color: const Color(0xFF94A3B8))),
          const SizedBox(height: 16),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              _circleButton(Icons.remove, () {
                if (_personCount > 2) setState(() => _personCount--);
              }),
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 32),
                child: Text('$_personCount',
                    style: GoogleFonts.outfit(
                        fontSize: 48,
                        fontWeight: FontWeight.w800,
                        color: Colors.white)),
              ),
              _circleButton(Icons.add, () {
                if (_personCount < 20) setState(() => _personCount++);
              }),
            ],
          ),
          const SizedBox(height: 32),
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: const Color(0xFF1E293B),
              borderRadius: BorderRadius.circular(16),
            ),
            child: Column(
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text('Toplam Kalan',
                        style: GoogleFonts.outfit(
                            fontSize: 14, color: const Color(0xFF94A3B8))),
                    Text('${_remaining.toStringAsFixed(2)} TL',
                        style: GoogleFonts.outfit(
                            fontSize: 14, color: Colors.white)),
                  ],
                ),
                const SizedBox(height: 12),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text('Kişi Başı',
                        style: GoogleFonts.outfit(
                            fontSize: 18,
                            fontWeight: FontWeight.w700,
                            color: Colors.white)),
                    Text('${perPerson.toStringAsFixed(2)} TL',
                        style: GoogleFonts.outfit(
                            fontSize: 24,
                            fontWeight: FontWeight.w800,
                            color: const Color(0xFF7A1DD1))),
                  ],
                ),
              ],
            ),
          ),
          const Spacer(),
          _paymentMethodRow(),
          const SizedBox(height: 12),
          _payButton(perPerson,
              'Kendi Payımı Öde (${perPerson.toStringAsFixed(2)} TL)'),
        ],
      ),
    );
  }

  // ── Tab 2: By Item ──

  Widget _buildItemSplit(List<Map<String, dynamic>> items) {
    double selectedTotal = 0;
    for (final item in items) {
      if (_selectedItemIds.contains(item['id'] ?? items.indexOf(item))) {
        selectedTotal += (item['total_price'] as num?)?.toDouble() ?? 0;
      }
    }

    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('Ödeyeceğiniz ürünleri seçin:',
              style: GoogleFonts.outfit(
                  fontSize: 14, color: const Color(0xFF94A3B8))),
          const SizedBox(height: 12),
          Expanded(
            child: ListView.builder(
              itemCount: items.length,
              itemBuilder: (_, i) {
                final item = items[i];
                final itemId = item['id'] ?? i;
                final isSelected = _selectedItemIds.contains(itemId);

                return GestureDetector(
                  onTap: () {
                    setState(() {
                      if (isSelected) {
                        _selectedItemIds.remove(itemId);
                      } else {
                        _selectedItemIds.add(itemId as int);
                      }
                    });
                  },
                  child: Container(
                    margin: const EdgeInsets.only(bottom: 8),
                    padding: const EdgeInsets.all(14),
                    decoration: BoxDecoration(
                      color: isSelected
                          ? const Color(0xFF7A1DD1).withOpacity(0.15)
                          : const Color(0xFF1E293B),
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(
                        color: isSelected
                            ? const Color(0xFF7A1DD1)
                            : const Color(0xFF334155),
                      ),
                    ),
                    child: Row(
                      children: [
                        Icon(
                          isSelected
                              ? Icons.check_circle_rounded
                              : Icons.circle_outlined,
                          color: isSelected
                              ? const Color(0xFF7A1DD1)
                              : const Color(0xFF475569),
                          size: 22,
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Text(item['name'] ?? '',
                              style: GoogleFonts.outfit(
                                  fontSize: 14, color: Colors.white)),
                        ),
                        Text('x${item['quantity']}',
                            style: GoogleFonts.outfit(
                                fontSize: 12, color: const Color(0xFF94A3B8))),
                        const SizedBox(width: 12),
                        Text(
                            '${((item['total_price'] as num?)?.toDouble() ?? 0).toStringAsFixed(2)} TL',
                            style: GoogleFonts.outfit(
                                fontSize: 14,
                                fontWeight: FontWeight.w600,
                                color: Colors.white)),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
          const SizedBox(height: 12),
          Container(
            padding: const EdgeInsets.all(14),
            decoration: BoxDecoration(
              color: const Color(0xFF1E293B),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text('Seçilen Toplam',
                    style: GoogleFonts.outfit(
                        fontSize: 14, color: const Color(0xFF94A3B8))),
                Text('${selectedTotal.toStringAsFixed(2)} TL',
                    style: GoogleFonts.outfit(
                        fontSize: 18,
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF7A1DD1))),
              ],
            ),
          ),
          const SizedBox(height: 12),
          _paymentMethodRow(),
          const SizedBox(height: 12),
          _payButton(selectedTotal,
              'Seçilenleri Öde (${selectedTotal.toStringAsFixed(2)} TL)'),
        ],
      ),
    );
  }

  // ── Tab 3: Custom Amount ──

  Widget _buildCustomSplit() {
    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        children: [
          const SizedBox(height: 24),
          Text('Ödemek istediğiniz tutarı girin:',
              style: GoogleFonts.outfit(
                  fontSize: 14, color: const Color(0xFF94A3B8))),
          const SizedBox(height: 24),
          TextField(
            keyboardType: const TextInputType.numberWithOptions(decimal: true),
            style: GoogleFonts.outfit(
                fontSize: 32, fontWeight: FontWeight.w800, color: Colors.white),
            textAlign: TextAlign.center,
            decoration: InputDecoration(
              suffixText: ' TL',
              suffixStyle: GoogleFonts.outfit(
                  fontSize: 32,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF7A1DD1)),
              hintText: '0.00',
              hintStyle: GoogleFonts.outfit(
                  fontSize: 32,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF475569)),
              filled: true,
              fillColor: const Color(0xFF1E293B),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(16),
                borderSide: BorderSide.none,
              ),
            ),
            onChanged: (val) {
              setState(() => _customAmount = double.tryParse(val) ?? 0);
            },
          ),
          const SizedBox(height: 16),
          Text('Kalan: ${_remaining.toStringAsFixed(2)} TL',
              style: GoogleFonts.outfit(
                  fontSize: 14, color: const Color(0xFF94A3B8))),
          const Spacer(),
          _paymentMethodRow(),
          const SizedBox(height: 12),
          _payButton(
              _customAmount, 'Öde (${_customAmount.toStringAsFixed(2)} TL)'),
        ],
      ),
    );
  }

  // ── Shared Widgets ──

  Widget _paymentMethodRow() {
    return Row(
      children: [
        _payChip('wallet', 'Cüzdan', Icons.account_balance_wallet_rounded),
        const SizedBox(width: 8),
        _payChip('credit_card', 'Kredi Kartı', Icons.credit_card_rounded),
      ],
    );
  }

  Widget _payChip(String id, String label, IconData icon) {
    final sel = _selectedPayMethod == id;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _selectedPayMethod = id),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 12),
          decoration: BoxDecoration(
            color: sel
                ? const Color(0xFF7A1DD1).withOpacity(0.2)
                : const Color(0xFF1E293B),
            borderRadius: BorderRadius.circular(12),
            border: Border.all(
                color: sel ? const Color(0xFF7A1DD1) : const Color(0xFF334155)),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(icon,
                  size: 18,
                  color: sel ? const Color(0xFF7A1DD1) : Colors.white38),
              const SizedBox(width: 6),
              Text(label,
                  style: GoogleFonts.outfit(
                      fontSize: 13,
                      fontWeight: FontWeight.w600,
                      color: sel ? const Color(0xFF7A1DD1) : Colors.white38)),
            ],
          ),
        ),
      ),
    );
  }

  Widget _circleButton(IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 48,
        height: 48,
        decoration: BoxDecoration(
          color: const Color(0xFF1E293B),
          shape: BoxShape.circle,
          border: Border.all(color: const Color(0xFF334155)),
        ),
        child: Icon(icon, color: Colors.white, size: 24),
      ),
    );
  }

  Widget _payButton(double amount, String label) {
    return SizedBox(
      width: double.infinity,
      height: 52,
      child: ElevatedButton(
        onPressed: (_isPaying || amount <= 0) ? null : () => _payAmount(amount),
        style: ElevatedButton.styleFrom(
          backgroundColor: const Color(0xFF22C55E),
          disabledBackgroundColor: const Color(0xFF334155),
          shape:
              RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
          elevation: 0,
        ),
        child: _isPaying
            ? const SizedBox(
                width: 24,
                height: 24,
                child: CircularProgressIndicator(
                    color: Colors.white, strokeWidth: 2))
            : Text(label,
                style: GoogleFonts.outfit(
                    fontSize: 15,
                    fontWeight: FontWeight.w700,
                    color: Colors.white)),
      ),
    );
  }
}
