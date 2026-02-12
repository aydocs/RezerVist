import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../../core/services/permission_service.dart';

class MainScreen extends ConsumerStatefulWidget {
  final Widget child;

  const MainScreen({
    super.key,
    required this.child,
  });

  @override
  ConsumerState<MainScreen> createState() => _MainScreenState();
}

class _MainScreenState extends ConsumerState<MainScreen> {
  @override
  void initState() {
    super.initState();
    // Check and request location permission on load
    WidgetsBinding.instance.addPostFrameCallback((_) {
      PermissionService.checkAndRequestLocation();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      extendBody: true,
      body: widget.child,
      floatingActionButton: FloatingActionButton(
        onPressed: () => context.push('/qr-scan'),
        backgroundColor: const Color(0xFF7A1DD1),
        elevation: 8,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        child: const Icon(Icons.qr_code_scanner_rounded,
            color: Colors.white, size: 28),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,
      bottomNavigationBar: NavigationBarTheme(
        data: NavigationBarThemeData(
          labelTextStyle: WidgetStateProperty.resolveWith((states) {
            if (states.contains(WidgetState.selected)) {
              return GoogleFonts.outfit(
                fontSize: 12,
                fontWeight: FontWeight.w700,
                color: const Color(0xFF7A1DD1),
              );
            }
            return GoogleFonts.outfit(
              fontSize: 12,
              fontWeight: FontWeight.w500,
              color: const Color(0xFF475569),
            );
          }),
        ),
        child: NavigationBar(
          selectedIndex: _calculateSelectedIndex(context),
          onDestinationSelected: (index) => _onItemTapped(index, context),
          backgroundColor: Colors.white,
          elevation: 0,
          surfaceTintColor: Colors.white,
          indicatorColor: Colors.transparent,
          labelBehavior: NavigationDestinationLabelBehavior.alwaysShow,
          destinations: const [
            NavigationDestination(
              icon: Icon(Icons.explore_outlined, color: Color(0xFF475569)),
              selectedIcon: Icon(Icons.explore, color: Color(0xFF7A1DD1)),
              label: 'Keşfet',
            ),
            NavigationDestination(
              icon: Icon(Icons.search_rounded, color: Color(0xFF475569)),
              selectedIcon:
                  Icon(Icons.search_rounded, color: Color(0xFF7A1DD1)),
              label: 'Arama',
            ),
            NavigationDestination(
              icon:
                  Icon(Icons.calendar_today_outlined, color: Color(0xFF475569)),
              selectedIcon:
                  Icon(Icons.calendar_today_rounded, color: Color(0xFF7A1DD1)),
              label: 'Randevu',
            ),
            NavigationDestination(
              icon:
                  Icon(Icons.person_outline_rounded, color: Color(0xFF475569)),
              selectedIcon:
                  Icon(Icons.person_rounded, color: Color(0xFF7A1DD1)),
              label: 'Profil',
            ),
          ],
        ),
      ),
    );
  }

  int _calculateSelectedIndex(BuildContext context) {
    final String location = GoRouterState.of(context).uri.toString();
    if (location.startsWith('/home')) return 0;
    if (location.startsWith('/search')) return 1;
    if (location.startsWith('/reservations')) return 2;
    if (location.startsWith('/profile')) return 3;
    return 0;
  }

  void _onItemTapped(int index, BuildContext context) {
    switch (index) {
      case 0:
        context.go('/home');
        break;
      case 1:
        context.go('/search');
        break;
      case 2:
        context.go('/reservations');
        break;
      case 3:
        context.go('/profile');
        break;
    }
  }
}
