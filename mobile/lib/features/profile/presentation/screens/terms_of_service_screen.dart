import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class TermsOfServiceScreen extends StatelessWidget {
  const TermsOfServiceScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text(
          'Kullanım Koşulları',
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
            _buildSection(
              '1. Kabul Edilme Şartları',
              'RezerVist uygulamasını kullanarak, bu kullanım koşullarını tamamen kabul etmiş sayılırsınız. Eğer bu şartları kabul etmiyorsanız, lütfen uygulamayı kullanmayınız.',
            ),
            _buildSection(
              '2. Hizmet Tanımı',
              'RezerVist, işletmeler için rezervasyon, POS ve cüzdan yönetimi sağlayan bir platformdur. Hizmetlerimiz sürekli olarak güncellenmekte ve geliştirilmektedir.',
            ),
            _buildSection(
              '3. Kullanıcı Sorumlulukları',
              'Kullanıcılar, hesap bilgilerinin gizliliğinden ve hesapları üzerinden yapılan tüm işlemlerden sorumludur. Hatalı veya yanıltıcı bilgi girişi hesabın askıya alınmasına neden olabilir.',
            ),
            _buildSection(
              '4. Ödemeler ve Cüzdan',
              'Cüzdan bakiyesi üzerinden yapılan işlemler anlık olarak işlenir. Yüklenen bakiyelerin iadesi, belirtilen kampanya ve iade şartlarına tabidir.',
            ),
            _buildSection(
              '5. Fikri Mülkiyet',
              'Uygulama içerisindeki tüm tasarımlar, yazılımlar ve logolar RezerVist\'e aittir. İzinsiz kullanımı veya kopyalanması kesinlikle yasaktır.',
            ),
            const SizedBox(height: 24),
            Text(
              'Son Güncelleme: 12 Şubat 2026',
              style: GoogleFonts.outfit(
                fontSize: 12,
                color: const Color(0xFF64748B),
                fontStyle: FontStyle.italic,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSection(String title, String content) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: GoogleFonts.outfit(
              fontSize: 18,
              fontWeight: FontWeight.w700,
              color: const Color(0xFF7A1DD1),
            ),
          ),
          const SizedBox(height: 12),
          Text(
            content,
            style: GoogleFonts.outfit(
              fontSize: 14,
              color: const Color(0xFF334155),
              height: 1.6,
            ),
          ),
        ],
      ),
    );
  }
}
