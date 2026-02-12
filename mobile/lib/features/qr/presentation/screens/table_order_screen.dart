import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../data/repositories/qr_repository.dart';
import '../providers/qr_provider.dart';

class TableOrderScreen extends ConsumerStatefulWidget {
  final String sessionToken;
  final String tableName;
  final String businessName;

  const TableOrderScreen({
    super.key,
    required this.sessionToken,
    required this.tableName,
    required this.businessName,
  });

  @override
  ConsumerState<TableOrderScreen> createState() => _TableOrderScreenState();
}

class _TableOrderScreenState extends ConsumerState<TableOrderScreen> {
  final List<Map<String, dynamic>> _cart = [];
  int _selectedCategoryIndex = 0;
  bool _showCart = false;
  bool _isSubmitting = false;

  double get _cartTotal {
    double total = 0;
    for (final item in _cart) {
      total += (item['price'] as num) * (item['quantity'] as int);
    }
    return total;
  }

  void _addToCart(Map<String, dynamic> menuItem) {
    setState(() {
      final existing =
          _cart.indexWhere((c) => c['menu_item_id'] == menuItem['id']);
      if (existing >= 0) {
        _cart[existing]['quantity'] = (_cart[existing]['quantity'] as int) + 1;
      } else {
        _cart.add({
          'menu_item_id': menuItem['id'],
          'name': menuItem['name'],
          'price': menuItem['price'],
          'quantity': 1,
        });
      }
    });
  }

  void _updateCartQuantity(int index, int delta) {
    setState(() {
      final newQty = (_cart[index]['quantity'] as int) + delta;
      if (newQty <= 0) {
        _cart.removeAt(index);
      } else {
        _cart[index]['quantity'] = newQty;
      }
    });
  }

  Future<void> _submitOrder() async {
    if (_cart.isEmpty || _isSubmitting) return;
    setState(() => _isSubmitting = true);

    try {
      final repo = ref.read(qrRepositoryProvider);
      final items = _cart
          .map((c) => {
                'menu_item_id': c['menu_item_id'],
                'quantity': c['quantity'],
              })
          .toList();

      await repo.submitOrder(widget.sessionToken, items);

      setState(() {
        _cart.clear();
        _showCart = false;
        _isSubmitting = false;
      });

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Siparişiniz alındı! 🎉'),
            backgroundColor: Color(0xFF22C55E),
          ),
        );
      }

      ref.invalidate(qrBillProvider(widget.sessionToken));
    } catch (e) {
      setState(() => _isSubmitting = false);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('$e'), backgroundColor: Colors.red[400]),
        );
      }
    }
  }

  void _showBill() async {
    try {
      final repo = ref.read(qrRepositoryProvider);
      final bill = await repo.getBill(widget.sessionToken);
      if (!mounted) return;

      showModalBottomSheet(
        context: context,
        isScrollControlled: true,
        backgroundColor: Colors.transparent,
        builder: (ctx) => _BillSheet(
          bill: bill,
          sessionToken: widget.sessionToken,
          repo: repo,
          onPaid: () {
            Navigator.pop(ctx);
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(
                content: Text('Ödeme başarılı! ✨'),
                backgroundColor: Color(0xFF22C55E),
              ),
            );
          },
        ),
      );
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('$e'), backgroundColor: Colors.red[400]),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final menuAsync = ref.watch(qrMenuProvider(widget.sessionToken));

    return Scaffold(
      backgroundColor: const Color(0xFF0F172A),
      appBar: AppBar(
        backgroundColor: const Color(0xFF0F172A),
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_rounded, color: Colors.white),
          onPressed: () => Navigator.pop(context),
        ),
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              widget.businessName,
              style: GoogleFonts.outfit(
                fontSize: 16,
                fontWeight: FontWeight.w700,
                color: Colors.white,
              ),
            ),
            Text(
              widget.tableName,
              style: GoogleFonts.outfit(
                fontSize: 12,
                color: const Color(0xFF94A3B8),
              ),
            ),
          ],
        ),
        actions: [
          IconButton(
            onPressed: _showBill,
            icon: const Icon(Icons.receipt_long_rounded,
                color: Color(0xFF7A1DD1)),
            tooltip: 'Hesabı Gör',
          ),
        ],
      ),
      body: menuAsync.when(
        loading: () => const Center(
          child: CircularProgressIndicator(color: Color(0xFF7A1DD1)),
        ),
        error: (e, _) => Center(
          child: Text('Menü yüklenemedi: $e',
              style: GoogleFonts.outfit(color: Colors.white54)),
        ),
        data: (menuData) {
          final categories =
              List<Map<String, dynamic>>.from(menuData['categories'] ?? []);

          if (categories.isEmpty) {
            return Center(
              child: Text('Menü boş',
                  style:
                      GoogleFonts.outfit(color: Colors.white54, fontSize: 16)),
            );
          }

          return Column(
            children: [
              // Category tabs
              SizedBox(
                height: 48,
                child: ListView.builder(
                  scrollDirection: Axis.horizontal,
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  itemCount: categories.length,
                  itemBuilder: (context, i) {
                    final cat = categories[i];
                    final isSelected = i == _selectedCategoryIndex;
                    return Padding(
                      padding: const EdgeInsets.only(right: 8),
                      child: ChoiceChip(
                        label: Text(cat['name'] ?? ''),
                        selected: isSelected,
                        onSelected: (_) =>
                            setState(() => _selectedCategoryIndex = i),
                        selectedColor: const Color(0xFF7A1DD1),
                        backgroundColor: const Color(0xFF1E293B),
                        labelStyle: GoogleFonts.outfit(
                          color: isSelected ? Colors.white : Colors.white54,
                          fontWeight: FontWeight.w600,
                          fontSize: 13,
                        ),
                        side: BorderSide.none,
                      ),
                    );
                  },
                ),
              ),
              const SizedBox(height: 12),

              // Menu items
              Expanded(
                child: _buildMenuItems(categories),
              ),
            ],
          );
        },
      ),

      // Cart FAB / bottom bar
      bottomNavigationBar: _cart.isEmpty
          ? null
          : _showCart
              ? _buildExpandedCart()
              : _buildCartBar(),
    );
  }

  Widget _buildMenuItems(List<Map<String, dynamic>> categories) {
    if (_selectedCategoryIndex >= categories.length) return const SizedBox();
    final items = List<Map<String, dynamic>>.from(
        categories[_selectedCategoryIndex]['items'] ?? []);

    return ListView.builder(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      itemCount: items.length,
      itemBuilder: (context, i) {
        final item = items[i];
        final price = (item['price'] as num?)?.toDouble() ?? 0;
        final cartItem =
            _cart.where((c) => c['menu_item_id'] == item['id']).firstOrNull;
        final inCartQty = cartItem != null ? cartItem['quantity'] as int : 0;

        return Container(
          margin: const EdgeInsets.only(bottom: 12),
          padding: const EdgeInsets.all(14),
          decoration: BoxDecoration(
            color: const Color(0xFF1E293B),
            borderRadius: BorderRadius.circular(14),
            border: inCartQty > 0
                ? Border.all(color: const Color(0xFF7A1DD1).withOpacity(0.4))
                : null,
          ),
          child: Row(
            children: [
              // Image placeholder
              Container(
                width: 56,
                height: 56,
                decoration: BoxDecoration(
                  color: const Color(0xFF334155),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: item['image'] != null
                    ? ClipRRect(
                        borderRadius: BorderRadius.circular(12),
                        child: Image.network(item['image'], fit: BoxFit.cover),
                      )
                    : const Icon(Icons.restaurant_rounded,
                        color: Color(0xFF7A1DD1)),
              ),
              const SizedBox(width: 14),

              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      item['name'] ?? '',
                      style: GoogleFonts.outfit(
                        fontSize: 15,
                        fontWeight: FontWeight.w600,
                        color: Colors.white,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    if (item['description'] != null)
                      Text(
                        item['description'],
                        style: GoogleFonts.outfit(
                          fontSize: 11,
                          color: Colors.white38,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    const SizedBox(height: 4),
                    Text(
                      '${price.toStringAsFixed(2)} TL',
                      style: GoogleFonts.outfit(
                        fontSize: 15,
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF22C55E),
                      ),
                    ),
                  ],
                ),
              ),

              // Add / quantity controls
              if (inCartQty == 0)
                GestureDetector(
                  onTap: () => _addToCart(item),
                  child: Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: const Color(0xFF7A1DD1),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: const Icon(Icons.add_rounded,
                        color: Colors.white, size: 20),
                  ),
                )
              else
                Row(
                  children: [
                    _miniButton(Icons.remove, () {
                      final idx = _cart
                          .indexWhere((c) => c['menu_item_id'] == item['id']);
                      if (idx >= 0) _updateCartQuantity(idx, -1);
                    }),
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 8),
                      child: Text(
                        '$inCartQty',
                        style: GoogleFonts.outfit(
                          fontSize: 15,
                          fontWeight: FontWeight.w700,
                          color: Colors.white,
                        ),
                      ),
                    ),
                    _miniButton(Icons.add, () => _addToCart(item)),
                  ],
                ),
            ],
          ),
        );
      },
    );
  }

  Widget _miniButton(IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(6),
        decoration: BoxDecoration(
          color: const Color(0xFF334155),
          borderRadius: BorderRadius.circular(8),
        ),
        child: Icon(icon, color: Colors.white, size: 16),
      ),
    );
  }

  Widget _buildCartBar() {
    return GestureDetector(
      onTap: () => setState(() => _showCart = true),
      child: Container(
        margin: const EdgeInsets.all(16),
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 14),
        decoration: BoxDecoration(
          color: const Color(0xFF7A1DD1),
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
                color: const Color(0xFF7A1DD1).withOpacity(0.3),
                blurRadius: 20),
          ],
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.2),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Text(
                '${_cart.fold<int>(0, (sum, item) => sum + (item['quantity'] as int))}',
                style: GoogleFonts.outfit(
                    fontSize: 14,
                    fontWeight: FontWeight.w700,
                    color: Colors.white),
              ),
            ),
            const SizedBox(width: 12),
            Text(
              'Sepeti Gör',
              style: GoogleFonts.outfit(
                  fontSize: 15,
                  fontWeight: FontWeight.w600,
                  color: Colors.white),
            ),
            const Spacer(),
            Text(
              '${_cartTotal.toStringAsFixed(2)} TL',
              style: GoogleFonts.outfit(
                  fontSize: 16,
                  fontWeight: FontWeight.w700,
                  color: Colors.white),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildExpandedCart() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: const BoxDecoration(
        color: Color(0xFF1E293B),
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Sepet',
                  style: GoogleFonts.outfit(
                      fontSize: 18,
                      fontWeight: FontWeight.w700,
                      color: Colors.white)),
              IconButton(
                onPressed: () => setState(() => _showCart = false),
                icon: const Icon(Icons.keyboard_arrow_down_rounded,
                    color: Colors.white),
              ),
            ],
          ),
          const SizedBox(height: 8),
          ConstrainedBox(
            constraints: const BoxConstraints(maxHeight: 200),
            child: ListView.builder(
              shrinkWrap: true,
              itemCount: _cart.length,
              itemBuilder: (_, i) {
                final item = _cart[i];
                return Padding(
                  padding: const EdgeInsets.symmetric(vertical: 6),
                  child: Row(
                    children: [
                      Expanded(
                        child: Text(item['name'],
                            style: GoogleFonts.outfit(
                                fontSize: 14, color: Colors.white)),
                      ),
                      _miniButton(
                          Icons.remove, () => _updateCartQuantity(i, -1)),
                      Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 8),
                        child: Text('${item['quantity']}',
                            style: GoogleFonts.outfit(
                                fontSize: 14,
                                fontWeight: FontWeight.w700,
                                color: Colors.white)),
                      ),
                      _miniButton(Icons.add, () => _updateCartQuantity(i, 1)),
                      const SizedBox(width: 12),
                      Text(
                        '${((item['price'] as num) * (item['quantity'] as int)).toStringAsFixed(2)} TL',
                        style: GoogleFonts.outfit(
                            fontSize: 14,
                            fontWeight: FontWeight.w700,
                            color: const Color(0xFF22C55E)),
                      ),
                    ],
                  ),
                );
              },
            ),
          ),
          const SizedBox(height: 12),
          // Total & submit
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Toplam',
                  style:
                      GoogleFonts.outfit(fontSize: 16, color: Colors.white54)),
              Text('${_cartTotal.toStringAsFixed(2)} TL',
                  style: GoogleFonts.outfit(
                      fontSize: 20,
                      fontWeight: FontWeight.w800,
                      color: Colors.white)),
            ],
          ),
          const SizedBox(height: 12),
          SizedBox(
            width: double.infinity,
            height: 52,
            child: ElevatedButton(
              onPressed: _isSubmitting ? null : _submitOrder,
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF7A1DD1),
                disabledBackgroundColor: const Color(0xFF334155),
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16)),
                elevation: 0,
              ),
              child: _isSubmitting
                  ? const SizedBox(
                      width: 24,
                      height: 24,
                      child: CircularProgressIndicator(
                          color: Colors.white, strokeWidth: 2))
                  : Text('Sipariş Ver 🍽️',
                      style: GoogleFonts.outfit(
                          fontSize: 16,
                          fontWeight: FontWeight.w700,
                          color: Colors.white)),
            ),
          ),
        ],
      ),
    );
  }
}

/// Bill bottom sheet with payment
class _BillSheet extends StatefulWidget {
  final Map<String, dynamic> bill;
  final String sessionToken;
  final QrRepository repo;
  final VoidCallback onPaid;

  const _BillSheet({
    required this.bill,
    required this.sessionToken,
    required this.repo,
    required this.onPaid,
  });

  @override
  State<_BillSheet> createState() => _BillSheetState();
}

class _BillSheetState extends State<_BillSheet> {
  bool _isPaying = false;
  String _selectedMethod = 'wallet';

  @override
  Widget build(BuildContext context) {
    final items = List<Map<String, dynamic>>.from(widget.bill['items'] ?? []);
    final total = (widget.bill['total_amount'] as num?)?.toDouble() ?? 0;
    final paid = (widget.bill['paid_amount'] as num?)?.toDouble() ?? 0;
    final remaining = total - paid;

    return Container(
      padding: EdgeInsets.only(
        bottom: MediaQuery.of(context).viewInsets.bottom + 24,
        top: 24,
        left: 24,
        right: 24,
      ),
      decoration: const BoxDecoration(
        color: Color(0xFF1E293B),
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Center(
            child: Container(
                width: 40,
                height: 4,
                decoration: BoxDecoration(
                    color: const Color(0xFF475569),
                    borderRadius: BorderRadius.circular(2))),
          ),
          const SizedBox(height: 20),
          Text('Hesap Özeti',
              style: GoogleFonts.outfit(
                  fontSize: 20,
                  fontWeight: FontWeight.w700,
                  color: Colors.white)),
          Text('${widget.bill['business_name']} — ${widget.bill['table_name']}',
              style: GoogleFonts.outfit(fontSize: 13, color: Colors.white38)),
          const SizedBox(height: 16),

          // Items
          ConstrainedBox(
            constraints: const BoxConstraints(maxHeight: 200),
            child: ListView.builder(
              shrinkWrap: true,
              itemCount: items.length,
              itemBuilder: (_, i) {
                final item = items[i];
                return Padding(
                  padding: const EdgeInsets.symmetric(vertical: 4),
                  child: Row(
                    children: [
                      Expanded(
                          child: Text('${item['name']}',
                              style: GoogleFonts.outfit(
                                  fontSize: 14, color: Colors.white))),
                      Text('x${item['quantity']}',
                          style: GoogleFonts.outfit(
                              fontSize: 12, color: Colors.white38)),
                      const SizedBox(width: 12),
                      Text(
                          '${(item['total_price'] as num).toStringAsFixed(2)} TL',
                          style: GoogleFonts.outfit(
                              fontSize: 14,
                              fontWeight: FontWeight.w600,
                              color: Colors.white)),
                    ],
                  ),
                );
              },
            ),
          ),
          const Divider(color: Color(0xFF334155), height: 24),

          // Totals
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Toplam',
                  style:
                      GoogleFonts.outfit(fontSize: 14, color: Colors.white54)),
              Text('${total.toStringAsFixed(2)} TL',
                  style: GoogleFonts.outfit(fontSize: 14, color: Colors.white)),
            ],
          ),
          if (paid > 0)
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text('Ödenen',
                    style: GoogleFonts.outfit(
                        fontSize: 14, color: Colors.white54)),
                Text('${paid.toStringAsFixed(2)} TL',
                    style: GoogleFonts.outfit(
                        fontSize: 14, color: const Color(0xFF22C55E))),
              ],
            ),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Kalan',
                  style: GoogleFonts.outfit(
                      fontSize: 18,
                      fontWeight: FontWeight.w700,
                      color: Colors.white)),
              Text('${remaining.toStringAsFixed(2)} TL',
                  style: GoogleFonts.outfit(
                      fontSize: 22,
                      fontWeight: FontWeight.w800,
                      color: const Color(0xFF7A1DD1))),
            ],
          ),

          if (remaining > 0) ...[
            const SizedBox(height: 16),
            // Payment method selector
            Row(
              children: [
                _payMethodChip(
                    'wallet', 'Cüzdan', Icons.account_balance_wallet_rounded),
                const SizedBox(width: 8),
                _payMethodChip(
                    'credit_card', 'Kredi Kartı', Icons.credit_card_rounded),
              ],
            ),
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              height: 52,
              child: ElevatedButton(
                onPressed: _isPaying ? null : _pay,
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF22C55E),
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16)),
                  elevation: 0,
                ),
                child: _isPaying
                    ? const SizedBox(
                        width: 24,
                        height: 24,
                        child: CircularProgressIndicator(
                            color: Colors.white, strokeWidth: 2))
                    : Text('${remaining.toStringAsFixed(2)} TL Öde',
                        style: GoogleFonts.outfit(
                            fontSize: 16,
                            fontWeight: FontWeight.w700,
                            color: Colors.white)),
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _payMethodChip(String id, String label, IconData icon) {
    final selected = _selectedMethod == id;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _selectedMethod = id),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 12),
          decoration: BoxDecoration(
            color: selected
                ? const Color(0xFF7A1DD1).withOpacity(0.2)
                : const Color(0xFF0F172A),
            borderRadius: BorderRadius.circular(12),
            border: Border.all(
                color: selected
                    ? const Color(0xFF7A1DD1)
                    : const Color(0xFF334155)),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(icon,
                  size: 18,
                  color: selected ? const Color(0xFF7A1DD1) : Colors.white38),
              const SizedBox(width: 6),
              Text(label,
                  style: GoogleFonts.outfit(
                      fontSize: 13,
                      fontWeight: FontWeight.w600,
                      color:
                          selected ? const Color(0xFF7A1DD1) : Colors.white38)),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _pay() async {
    setState(() => _isPaying = true);
    try {
      await widget.repo
          .payBill(widget.sessionToken, paymentMethod: _selectedMethod);
      widget.onPaid();
    } catch (e) {
      setState(() => _isPaying = false);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('$e'), backgroundColor: Colors.red[400]),
        );
      }
    }
  }
}
