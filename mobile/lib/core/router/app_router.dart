import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../features/auth/presentation/providers/auth_provider.dart';
import '../../features/auth/presentation/screens/login_screen.dart';
import '../../features/auth/presentation/screens/register_screen.dart';
import '../../features/home/presentation/screens/home_screen.dart';
import '../../features/home/presentation/screens/main_screen.dart';
import '../../features/home/presentation/screens/business_detail_screen.dart';
import '../../features/profile/presentation/screens/profile_screen.dart';
import '../../features/notifications/presentation/screens/notification_screen.dart';
import '../../features/home/presentation/screens/search_screen.dart';
import '../../features/reservation/presentation/screens/reservation_list_screen.dart';
import '../../features/favorites/presentation/screens/favorites_screen.dart';
import '../../features/profile/presentation/screens/wallet_screen.dart';
import '../../features/profile/presentation/screens/topup_screen.dart';
import '../../features/profile/presentation/screens/send_money_screen.dart';
import '../../features/profile/presentation/screens/reports_screen.dart';
import '../../features/profile/presentation/screens/settings_screen.dart';
import '../../features/profile/presentation/screens/terms_of_service_screen.dart';
import '../../features/profile/presentation/screens/privacy_policy_screen.dart';
import '../../features/qr/presentation/screens/qr_scanner_screen.dart';

final _rootNavigatorKey = GlobalKey<NavigatorState>();

final routerProvider = Provider<GoRouter>((ref) {
  return GoRouter(
    navigatorKey: _rootNavigatorKey,
    initialLocation: '/login',
    refreshListenable: AuthNotifierListenable(ref.read(authProvider.notifier)),
    redirect: (context, state) {
      final authState = ref.read(authProvider);

      // If auth is still initializing, don't redirect yet
      if (authState.isLoading) {
        return null;
      }

      final isLoggedIn = authState.value != null;
      final isLoggingIn = state.uri.toString() == '/login';
      final isRegistering = state.uri.toString() == '/register';

      if (!isLoggedIn && !isLoggingIn && !isRegistering) {
        return '/login';
      }

      if (isLoggedIn && (isLoggingIn || isRegistering)) {
        return '/home';
      }

      return null;
    },
    routes: [
      GoRoute(
        path: '/login',
        builder: (context, state) => const LoginScreen(),
      ),
      GoRoute(
        path: '/register',
        builder: (context, state) => const RegisterScreen(),
      ),
      ShellRoute(
        builder: (context, state, child) => MainScreen(child: child),
        routes: [
          GoRoute(
            path: '/home',
            builder: (context, state) => const HomeScreen(),
          ),
          GoRoute(
            path: '/search',
            builder: (context, state) {
              final extra = state.extra as Map<String, dynamic>?;
              return SearchScreen(
                query: extra?['query'] as String?,
                date: extra?['date'] as String?,
                guests: extra?['guests'] as int?,
              );
            },
          ),
          GoRoute(
            path: '/notifications',
            builder: (context, state) => const NotificationScreen(),
          ),
          GoRoute(
            path: '/reservations',
            builder: (context, state) => const ReservationListScreen(),
          ),
          GoRoute(
            path: '/profile',
            builder: (context, state) => const ProfileScreen(),
          ),
          GoRoute(
            path: '/wallet',
            builder: (context, state) => const WalletScreen(),
            routes: [
              GoRoute(
                path: 'topup',
                builder: (context, state) => const TopupScreen(),
              ),
              GoRoute(
                path: 'send',
                builder: (context, state) => const SendMoneyScreen(),
              ),
              GoRoute(
                path: 'reports',
                builder: (context, state) => const ReportsScreen(),
              ),
            ],
          ),
          GoRoute(
            path: '/settings',
            builder: (context, state) => const SettingsScreen(),
            routes: [
              GoRoute(
                path: 'terms',
                builder: (context, state) => const TermsOfServiceScreen(),
              ),
              GoRoute(
                path: 'privacy',
                builder: (context, state) => const PrivacyPolicyScreen(),
              ),
            ],
          ),
          GoRoute(
            path: '/favorites',
            builder: (context, state) => const FavoritesScreen(),
          ),
        ],
      ),
      GoRoute(
        path: '/business-detail/:id',
        name: 'businessDetail',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final id = int.parse(state.pathParameters['id']!);
          final name = state.uri.queryParameters['name'] ?? 'İşletme Detayı';
          return BusinessDetailScreen(
            businessId: id,
            businessName: name,
          );
        },
      ),
      GoRoute(
        path: '/qr-scan',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) => const QrScannerScreen(),
      ),
    ],
  );
});

class AuthNotifierListenable extends ChangeNotifier {
  final AuthNotifier _notifier;
  AuthNotifierListenable(this._notifier) {
    _notifier.addListener((state) {
      notifyListeners();
    });
  }
}
