import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class TermsBottomSheet extends StatefulWidget {
  final VoidCallback onAccepted;

  const TermsBottomSheet({super.key, required this.onAccepted});

  @override
  State<TermsBottomSheet> createState() => _TermsBottomSheetState();
}

class _TermsBottomSheetState extends State<TermsBottomSheet> {
  double scrollProgress = 0;
  bool isBottomReached = false;

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.9,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
      ),
      child: Column(
        children: [
          // Handle bar
          Padding(
            padding: const EdgeInsets.only(top: 12, bottom: 8),
            child: Container(
              width: 40,
              height: 4,
              decoration: BoxDecoration(
                color: const Color(0xFFE2E8F0),
                borderRadius: BorderRadius.circular(10),
              ),
            ),
          ),

          // Header
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 8),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Yasal Belgeler & Onay',
                  style: GoogleFonts.outfit(
                    fontSize: 24,
                    fontWeight: FontWeight.w900,
                    color: const Color(0xFF0F172A),
                  ),
                ),
                Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                  decoration: BoxDecoration(
                    color: const Color(0xFF7A1DD1).withOpacity(0.1),
                    borderRadius: BorderRadius.circular(100),
                  ),
                  child: Text(
                    '%${(scrollProgress * 100).toInt()}',
                    style: GoogleFonts.outfit(
                      fontWeight: FontWeight.w900,
                      color: const Color(0xFF7A1DD1),
                      fontSize: 14,
                    ),
                  ),
                ),
              ],
            ),
          ),

          const Divider(height: 1, color: Color(0xFFF1F5F9)),

          // Content
          Expanded(
            child: NotificationListener<ScrollNotification>(
              onNotification: (ScrollNotification notification) {
                setState(() {
                  scrollProgress = notification.metrics.pixels /
                      notification.metrics.maxScrollExtent;
                  scrollProgress = scrollProgress.clamp(0.0, 1.0);
                  if (scrollProgress >= 0.99) {
                    isBottomReached = true;
                  }
                });
                return true;
              },
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(24),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildWebDocumentTitle('1. KULLANIM KOŞULLARI'),
                    _buildWebDocumentText(
                        'Lütfen Rezervist platformunu kullanmadan önce bu sözleşmeyi dikkatlice okuyunuz. Bu siteyi kullanan ve alışveriş yapan müşterilerimiz aşağıdaki şartları kabul etmiş varsayılmaktadır.'),
                    _buildWebSectionHeader('1.1 Taraflar ve Tanımlar'),
                    _buildWebDocumentText(
                        'Bu sözleşme, Rezervist (PLATFORM) ile bu platformu kullanan kullanıcı (KULLANICI) arasında akdedilmiştir. PLATFORM, restoran ve etkinlik rezervasyon hizmetleri sunan bir aracı hizmet sağlayıcıdır.'),
                    _buildWebSectionHeader('1.2 Hizmetin Kapsamı'),
                    _buildWebDocumentText(
                        'Rezervist, kullanıcıların anlaşmalı işletmelerde rezervasyon yapmasını, işletmeleri incelemesini ve puanlamasını sağlayan bir dijital platformdur. Rezervist, hizmet sağlayıcı (restoran vb.) değildir; yalnızca kullanıcı ile işletme arasında rezervasyon köprüsü kurar.'),
                    _buildWebSectionHeader('1.3 Kullanıcı Yükümlülükleri'),
                    _buildWebBulletPoint(
                        'Kullanıcı, platforma üye olurken verdiği bilgilerin doğru olduğunu taahhüt eder.'),
                    _buildWebBulletPoint(
                        'Kullanıcı, oluşturduğu rezervasyonlara gitmeyeceğini (No-Show) en az 2 saat önceden bildirmekle yükümlüdür.'),
                    _buildWebBulletPoint(
                        'Kullanıcı, platform üzerinde genel ahlaka aykırı, yasa dışı veya rahatsız edici içerik paylaşamaz.'),
                    _buildWebSectionHeader('1.4 Ödeme ve Komisyon Şartları'),
                    _buildWebDocumentText(
                        'Platform Hizmet Bedeli: Yapılan her satış tutarı üzerinden %5 oranında platform hizmet bedeli kesilmektedir. Ödemeler Iyzico Marketplace altyapısı ile güvence altına alınmıştır.'),
                    const SizedBox(height: 32),
                    _buildWebDocumentTitle('2. GİZLİLİK POLİTİKASI (KVKK)'),
                    _buildWebDocumentText(
                        'Rezervist olarak kişisel verilerinizin güvenliğine ve gizliliğine önem veriyoruz. Bu politika, 6698 sayılı KVKK kapsamında verilerinizin nasıl işlendiğini açıklar.'),
                    _buildWebSectionHeader('2.1 İşlenen Kişisel Verileriniz'),
                    _buildWebBulletPoint('Kimlik Bilgileri: Ad, soyad.'),
                    _buildWebBulletPoint(
                        'İletişim Bilgileri: E-posta adresi, telefon numarası.'),
                    _buildWebBulletPoint(
                        'İşlem Bilgileri: Rezervasyon geçmişi, favoriler, yorumlar.'),
                    _buildWebSectionHeader('2.2 Kişisel Veri Toplama Yöntemi'),
                    _buildWebDocumentText(
                        'Verileriniz, internet sitemiz ve mobil uygulamamız üzerinden elektronik ortamda toplanmaktadır. SSL şifreleme ve güvenli sunucu altyapıları ile korunmaktadır.'),
                    const SizedBox(height: 40),
                    Center(
                      child: Text(
                        'Belgelerin sonuna geldiniz.',
                        style: GoogleFonts.outfit(
                            color: Colors.grey, fontSize: 13),
                      ),
                    ),
                    const SizedBox(height: 20),
                  ],
                ),
              ),
            ),
          ),

          // Footer Action
          AnimatedContainer(
            duration: const Duration(milliseconds: 500),
            curve: Curves.easeOutCubic,
            padding: const EdgeInsets.fromLTRB(24, 16, 24, 32),
            decoration: BoxDecoration(
              color: Colors.white,
              boxShadow: [
                if (isBottomReached)
                  BoxShadow(
                    color: Colors.black.withOpacity(0.05),
                    blurRadius: 20,
                    offset: const Offset(0, -5),
                  ),
              ],
            ),
            child: isBottomReached
                ? SizedBox(
                    width: double.infinity,
                    height: 56,
                    child: ElevatedButton(
                      onPressed: () {
                        widget.onAccepted();
                        Navigator.pop(context);
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF7A1DD1),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16),
                        ),
                        elevation: 0,
                      ),
                      child: Text(
                        'Okudum ve Onaylıyorum',
                        style: GoogleFonts.outfit(
                          color: Colors.white,
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  )
                : Container(
                    width: double.infinity,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    decoration: BoxDecoration(
                      color: const Color(0xFFF8FAFC),
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Center(
                      child: Text(
                        'Devam etmek için belgeleri sonuna kadar okuyun',
                        style: GoogleFonts.outfit(
                          color: const Color(0xFF94A3B8),
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                  ),
          ),
        ],
      ),
    );
  }

  Widget _buildWebDocumentTitle(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Text(
        text,
        style: GoogleFonts.outfit(
          fontSize: 18,
          fontWeight: FontWeight.w900,
          color: const Color(0xFF0F172A),
        ),
      ),
    );
  }

  Widget _buildWebSectionHeader(String text) {
    return Padding(
      padding: const EdgeInsets.only(top: 16, bottom: 8),
      child: Text(
        text,
        style: GoogleFonts.outfit(
          fontSize: 15,
          fontWeight: FontWeight.w700,
          color: const Color(0xFF1E293B),
        ),
      ),
    );
  }

  Widget _buildWebDocumentText(String text) {
    return Text(
      text,
      style: GoogleFonts.outfit(
        fontSize: 14,
        color: const Color(0xFF475569),
        height: 1.6,
      ),
    );
  }

  Widget _buildWebBulletPoint(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Padding(
            padding: EdgeInsets.only(top: 8),
            child: Icon(Icons.circle, size: 5, color: Color(0xFF7A1DD1)),
          ),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              text,
              style: GoogleFonts.outfit(
                fontSize: 14,
                color: const Color(0xFF475569),
                height: 1.6,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
