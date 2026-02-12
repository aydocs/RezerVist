import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class TopupScreen extends StatefulWidget {
  const TopupScreen({super.key});

  @override
  State<TopupScreen> createState() => _TopupScreenState();
}

class _TopupScreenState extends State<TopupScreen> {
  final TextEditingController _amountController = TextEditingController();
  double _selectedAmount = 0.0;

  final List<double> _quickAmounts = [50, 100, 250, 500, 1000];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text(
          'Bakiye Yükle',
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
              'Yüklemek istediğiniz tutarı seçin',
              style: GoogleFonts.outfit(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                color: const Color(0xFF64748B),
              ),
            ),
            const SizedBox(height: 24),
            Wrap(
              spacing: 12,
              runSpacing: 12,
              children: _quickAmounts
                  .map((amount) => _buildAmountChip(amount))
                  .toList(),
            ),
            const SizedBox(height: 32),
            Text(
              'Veya özel bir tutar girin',
              style: GoogleFonts.outfit(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                color: const Color(0xFF64748B),
              ),
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _amountController,
              keyboardType: TextInputType.number,
              style:
                  GoogleFonts.outfit(fontSize: 20, fontWeight: FontWeight.w700),
              decoration: InputDecoration(
                hintText: '0.00',
                suffixText: 'TL',
                contentPadding: const EdgeInsets.all(20),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(16),
                  borderSide: BorderSide(color: Colors.grey.shade200),
                ),
                enabledBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(16),
                  borderSide: BorderSide(color: Colors.grey.shade200),
                ),
                focusedBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(16),
                  borderSide: const BorderSide(color: Color(0xFF7A1DD1)),
                ),
              ),
              onChanged: (val) {
                setState(() {
                  _selectedAmount = double.tryParse(val) ?? 0.0;
                });
              },
            ),
            const SizedBox(height: 48),
            SizedBox(
              width: double.infinity,
              height: 60,
              child: ElevatedButton(
                onPressed: _selectedAmount > 0
                    ? () {
                        // Logic for initiating topup
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(
                              content: Text('Yükleme başlatılıyor...')),
                        );
                      }
                    : null,
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF7A1DD1),
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  elevation: 0,
                ),
                child: Text(
                  'Güvenli Ödeme Yap',
                  style: GoogleFonts.outfit(
                    fontSize: 18,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAmountChip(double amount) {
    bool isSelected = _selectedAmount == amount;
    return InkWell(
      onTap: () {
        setState(() {
          _selectedAmount = amount;
          _amountController.text = amount.toInt().toString();
        });
      },
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
        decoration: BoxDecoration(
          color: isSelected ? const Color(0xFF7A1DD1) : Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: isSelected ? const Color(0xFF7A1DD1) : Colors.grey.shade200,
          ),
        ),
        child: Text(
          '${amount.toInt()} TL',
          style: GoogleFonts.outfit(
            color: isSelected ? Colors.white : const Color(0xFF0F172A),
            fontWeight: FontWeight.w700,
            fontSize: 16,
          ),
        ),
      ),
    );
  }
}
