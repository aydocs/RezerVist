import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import 'package:package_info_plus/package_info_plus.dart';
import '../../../../features/auth/presentation/providers/auth_provider.dart';
import '../widgets/settings_tile.dart';
import '../widgets/settings_section.dart';

class SettingsScreen extends ConsumerStatefulWidget {
  const SettingsScreen({super.key});

  @override
  ConsumerState<SettingsScreen> createState() => _SettingsScreenState();
}

class _SettingsScreenState extends ConsumerState<SettingsScreen> {
  String _appVersion = '';

  @override
  void initState() {
    super.initState();
    _loadAppVersion();
  }

  Future<void> _loadAppVersion() async {
    try {
      final info = await PackageInfo.fromPlatform();
      if (mounted) {
        setState(() => _appVersion = '${info.version} (${info.buildNumber})');
      }
    } catch (_) {
      if (mounted) setState(() => _appVersion = '1.0.0');
    }
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authProvider);
    final user = authState.value;
    final notificationsEnabled = user?.notifications_enabled ?? true;

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          onPressed: () => Navigator.pop(context),
          icon: const Icon(Icons.arrow_back_ios_new_rounded,
              color: Color(0xFF1E293B), size: 20),
        ),
        title: Text(
          'Ayarlar',
          style: GoogleFonts.outfit(
            fontSize: 18,
            fontWeight: FontWeight.w700,
            color: const Color(0xFF1E293B),
          ),
        ),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SettingsSection(
              title: 'Genel',
              children: [
                // Language Switcher
                const SizedBox(height: 8),
                const SizedBox(height: 8),
                // Notifications
                SettingsTile(
                  icon: Icons.notifications_rounded,
                  iconColor: const Color(0xFFF59E0B),
                  title: 'Bildirimler',
                  subtitle: notificationsEnabled ? 'Açık' : 'Kapalı',
                  trailing: Switch.adaptive(
                    value: notificationsEnabled,
                    onChanged: (val) async {
                      try {
                        await ref.read(authProvider.notifier).updateSettings({
                          'notifications_enabled': val,
                        });
                      } catch (e) {
                        if (mounted) {
                          if (context.mounted) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              SnackBar(content: Text('Hata: ${e.toString()}')),
                            );
                          }
                        }
                      }
                    },
                    activeColor: const Color(0xFF7A1DD1),
                  ),
                ),
              ],
            ),

            const SizedBox(height: 28),
            const SizedBox(height: 28),
            SettingsSection(
              title: 'Hakkında',
              children: [
                // Terms
                SettingsTile(
                  icon: Icons.description_rounded,
                  iconColor: const Color(0xFF7A1DD1),
                  title: 'Kullanım Koşulları',
                  subtitle: 'Hizmet sözleşmesini görüntüle',
                  onTap: () => context.push('/settings/terms'),
                ),
                const SizedBox(height: 8),

                // Privacy
                SettingsTile(
                  icon: Icons.shield_rounded,
                  iconColor: const Color(0xFF7A1DD1),
                  title: 'Gizlilik Politikası',
                  subtitle: 'Veri koruma politikamız',
                  onTap: () => context.push('/settings/privacy'),
                ),
                const SizedBox(height: 8),

                // App Version
                SettingsTile(
                  icon: Icons.info_outline_rounded,
                  iconColor: const Color(0xFF94A3B8),
                  title: 'Uygulama Versiyonu',
                  subtitle:
                      _appVersion.isNotEmpty ? _appVersion : 'Yükleniyor...',
                ),
              ],
            ),

            const SizedBox(height: 40),

            // Branding
            Center(
              child: Column(
                children: [
                  Text(
                    'RezerVist.com',
                    style: GoogleFonts.outfit(
                      fontSize: 16,
                      fontWeight: FontWeight.w800,
                      color: const Color(0xFF7A1DD1),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    '© 2026 Tüm hakları saklıdır.',
                    style: GoogleFonts.outfit(
                      fontSize: 12,
                      color: const Color(0xFF94A3B8),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
