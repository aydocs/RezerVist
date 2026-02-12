import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class PrivacyPolicyScreen extends StatelessWidget {
  const PrivacyPolicyScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text(
          'Gizlilik Politikası',
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
              '1. Veri Toplama',
              'Kullanıcı deneyimini iyileştirmek ve güvenli ödeme işlemleri sağlamak adına adınız, e-posta adresiniz, iletişim bilgileriniz ve işlem geçmişiniz toplanmaktadır.',
            ),
            _buildSection(
              '2. Veri Kullanımı',
              'Toplanan veriler sadece hizmet sunumu, güvenlik doğrulamaları ve kullanıcı bilgilendirmesi amacıyla kullanılır. Üçüncü taraflarla izinsiz olarak paylaşılmaz.',
            ),
            _buildSection(
              '3. Veri Güvenliği',
              'Verileriniz en yüksek güvenlik standartları ve şifreleme yöntemleri ile korunmaktadır. Sunucularımız düzenli olarak güvenlik denetimlerinden geçmektedir.',
            ),
            _buildSection(
              '4. Çerez Politikası',
              'Uygulamamız, oturum yönetimi ve analiz amaçlı temel çerezler kullanabilir. Bu çerezler kullanıcının gizliliğini ihlal etmez.',
            ),
            _buildSection(
              '5. Haklarınız',
              'Kullanıcılar diledikleri zaman verilerine erişme, düzeltme veya silinmesini talep etme hakkına sahiptir. Bu talepler için bize ulaşabilirsiniz.',
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
              color: const Color(
                  0xFF10B981), // Greenish for privacy/security feel, or purple to match
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
