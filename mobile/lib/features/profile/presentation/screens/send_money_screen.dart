import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import '../providers/wallet_provider.dart';

class SendMoneyScreen extends ConsumerStatefulWidget {
  const SendMoneyScreen({super.key});

  @override
  ConsumerState<SendMoneyScreen> createState() => _SendMoneyScreenState();
}

class _SendMoneyScreenState extends ConsumerState<SendMoneyScreen> {
  final TextEditingController _amountController = TextEditingController();
  final TextEditingController _searchController = TextEditingController();
  double _amount = 0.0;
  List<Map<String, dynamic>> _searchResults = [];
  Map<String, dynamic>? _selectedRecipient;
  bool _isSearching = false;
  bool _isLoading = false;
  Timer? _debounce;

  void _onSearchChanged(String query) {
    if (_debounce?.isActive ?? false) _debounce!.cancel();
    _debounce = Timer(const Duration(milliseconds: 500), () async {
      if (query.length < 2) {
        setState(() {
          _searchResults = [];
          _isSearching = false;
        });
        return;
      }

      setState(() => _isSearching = true);
      try {
        final results =
            await ref.read(walletRepositoryProvider).searchRecipients(query);
        setState(() {
          _searchResults = results;
          _isSearching = false;
        });
      } catch (e) {
        setState(() => _isSearching = false);
      }
    });
  }

  Future<void> _handleTransfer() async {
    if (_selectedRecipient == null || _amount <= 0) return;

    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Onay',
            style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
        content: Text(
          '${_selectedRecipient!['name']} kullanıcısına ₺${_amount.toStringAsFixed(2)} göndermek istediğinize emin misiniz?',
          style: GoogleFonts.outfit(),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child:
                Text('Vazgeç', style: GoogleFonts.outfit(color: Colors.grey)),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF7A1DD1)),
            child: Text('Evet, Gönder',
                style: GoogleFonts.outfit(color: Colors.white)),
          ),
        ],
      ),
    );

    if (confirm != true) return;

    setState(() => _isLoading = true);
    try {
      await ref.read(walletRepositoryProvider).transfer(
            _selectedRecipient!['id'],
            _amount,
          );

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Transfer başarıyla tamamlandı.')),
        );
        ref.invalidate(walletTransactionsProvider);
        Navigator.pop(context);
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Hata: ${e.toString()}')),
        );
      }
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  void dispose() {
    _debounce?.cancel();
    _amountController.dispose();
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text(
          'Para Gönder',
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
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Alıcı seçin',
              style: GoogleFonts.outfit(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                color: const Color(0xFF64748B),
              ),
            ),
            const SizedBox(height: 16),
            if (_selectedRecipient != null)
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: const Color(0xFFF8FAFC),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(
                      color: const Color(0xFF7A1DD1).withOpacity(0.3)),
                ),
                child: Row(
                  children: [
                    CircleAvatar(
                      backgroundColor: const Color(0xFF7A1DD1).withOpacity(0.1),
                      child: Text(_selectedRecipient!['name'][0],
                          style: const TextStyle(color: Color(0xFF7A1DD1))),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(_selectedRecipient!['name'],
                              style: GoogleFonts.outfit(
                                  fontWeight: FontWeight.bold)),
                          Text(_selectedRecipient!['phone'] ?? '',
                              style: GoogleFonts.outfit(
                                  fontSize: 12, color: Colors.grey)),
                        ],
                      ),
                    ),
                    IconButton(
                      icon: const Icon(Icons.close_rounded, color: Colors.red),
                      onPressed: () =>
                          setState(() => _selectedRecipient = null),
                    )
                  ],
                ),
              )
            else ...[
              TextField(
                controller: _searchController,
                onChanged: _onSearchChanged,
                decoration: InputDecoration(
                  hintText: 'İsim veya telefon numarası ara...',
                  prefixIcon: const Icon(Icons.search_rounded),
                  suffixIcon: _isSearching
                      ? const Padding(
                          padding: EdgeInsets.all(12.0),
                          child: CircularProgressIndicator(strokeWidth: 2),
                        )
                      : null,
                  contentPadding: const EdgeInsets.all(16),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(16),
                    borderSide: BorderSide(color: Colors.grey.shade200),
                  ),
                ),
              ),
              if (_searchResults.isNotEmpty)
                Container(
                  margin: const EdgeInsets.only(top: 8),
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
                  child: ListView.separated(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    itemCount: _searchResults.length,
                    separatorBuilder: (_, __) => const Divider(height: 1),
                    itemBuilder: (context, index) {
                      final user = _searchResults[index];
                      return ListTile(
                        leading: CircleAvatar(
                          backgroundColor:
                              const Color(0xFF7A1DD1).withOpacity(0.1),
                          child: Text(user['name'][0],
                              style: const TextStyle(color: Color(0xFF7A1DD1))),
                        ),
                        title: Text(user['name'],
                            style: GoogleFonts.outfit(
                                fontWeight: FontWeight.w600)),
                        subtitle: Text(user['phone'] ?? '',
                            style: GoogleFonts.outfit(fontSize: 12)),
                        onTap: () {
                          setState(() {
                            _selectedRecipient = user;
                            _searchResults = [];
                            _searchController.clear();
                          });
                        },
                      );
                    },
                  ),
                ),
            ],
            const SizedBox(height: 32),
            Text(
              'Tutar',
              style: GoogleFonts.outfit(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                color: const Color(0xFF64748B),
              ),
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _amountController,
              keyboardType:
                  const TextInputType.numberWithOptions(decimal: true),
              style:
                  GoogleFonts.outfit(fontSize: 24, fontWeight: FontWeight.w800),
              decoration: InputDecoration(
                hintText: '0.00',
                suffixText: 'TL',
                contentPadding: const EdgeInsets.all(20),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(16),
                  borderSide: BorderSide(color: Colors.grey.shade200),
                ),
              ),
              onChanged: (val) {
                setState(() {
                  _amount = double.tryParse(val) ?? 0.0;
                });
              },
            ),
            const SizedBox(height: 48),
            SizedBox(
              width: double.infinity,
              height: 60,
              child: ElevatedButton(
                onPressed:
                    (_amount > 0 && _selectedRecipient != null && !_isLoading)
                        ? _handleTransfer
                        : null,
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF7A1DD1),
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                ),
                child: _isLoading
                    ? const CircularProgressIndicator(color: Colors.white)
                    : Text(
                        'Gönder',
                        style: GoogleFonts.outfit(
                            fontSize: 18, fontWeight: FontWeight.w700),
                      ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
