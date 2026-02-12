import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../auth/presentation/providers/auth_provider.dart';
import '../../../pos/presentation/screens/owner_dashboard_screen.dart';
import '../widgets/profile_header.dart';
import '../widgets/wallet_card.dart';
import '../widgets/profile_menu_tile.dart';
import '../widgets/profile_menu_section.dart';

class ProfileScreen extends ConsumerWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final authState = ref.watch(authProvider);
    final user = authState.value;

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 16),
              Text(
                'Profil',
                style: GoogleFonts.outfit(
                  fontSize: 28,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF0F172A),
                ),
              ),
              const SizedBox(height: 24),
              if (user != null) ...[
                ProfileHeader(user: user),
                const SizedBox(height: 16),
                WalletCard(
                  balance: user.balance ?? 0.0,
                  points: user.points ?? 0,
                  userName: user.name ?? 'Kullanıcı',
                ),
                const SizedBox(height: 24),
                ProfileMenuSection(
                  children: [
                    if (user.role == 'business')
                      ProfileMenuTile(
                        icon: Icons.store_mall_directory_rounded,
                        title: 'İşletme Paneli',
                        subtitle: 'POS ve İstatistikler',
                        color: const Color(0xFF7A1DD1),
                        onTap: () {
                          Navigator.of(context).push(
                            MaterialPageRoute(
                              builder: (context) =>
                                  const OwnerDashboardScreen(),
                            ),
                          );
                        },
                      ),
                    ProfileMenuTile(
                      icon: Icons.calendar_month_rounded,
                      title: 'Randevularım',
                      subtitle: 'Geçmiş ve gelecek randevular',
                      color: const Color(0xFF7A1DD1),
                      onTap: () => context.push('/reservations'),
                    ),
                    ProfileMenuTile(
                      icon: Icons.favorite_rounded,
                      title: 'Favorilerim',
                      subtitle: 'Kayıtlı mekanlarını görüntüle',
                      color: const Color(0xFF7A1DD1),
                      onTap: () => context.push('/favorites'),
                    ),
                    ProfileMenuTile(
                      icon: Icons.notifications_rounded,
                      title: 'Bildirimler',
                      subtitle: 'Bildirimlerini görüntüle',
                      color: const Color(0xFF7A1DD1),
                      onTap: () => context.push('/notifications'),
                    ),
                    ProfileMenuTile(
                      icon: Icons.settings_rounded,
                      title: 'Ayarlar',
                      subtitle: 'Dil, bildirim ve uygulama tercihleri',
                      color: const Color(0xFF64748B),
                      onTap: () => context.push('/settings'),
                    ),
                    ProfileMenuTile(
                      icon: Icons.logout_rounded,
                      title: 'Çıkış Yap',
                      subtitle: 'Hesabından güvenle çık',
                      color: const Color(0xFFEF4444),
                      onTap: () {
                        ref.read(authProvider.notifier).logout();
                        context.go('/login');
                      },
                      isDestructive: true,
                    ),
                  ],
                ),
              ] else
                const Center(child: CircularProgressIndicator()),
            ],
          ),
        ),
      ),
    );
  }
}
