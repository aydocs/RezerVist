import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'core/theme/app_theme.dart';
import 'core/router/app_router.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await EasyLocalization.ensureInitialized();
  await initializeDateFormatting('tr_TR', null);

  runApp(
    EasyLocalization(
      supportedLocales: const [Locale('tr'), Locale('en')],
      path: 'assets/langs',
      fallbackLocale: const Locale('tr'),
      startLocale: const Locale('tr'),
      child: const ProviderScope(child: RezervistApp()),
    ),
  );
}

class RezervistApp extends ConsumerWidget {
  const RezervistApp({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final router = ref.watch(routerProvider);

    return MaterialApp.router(
      title: 'Rezervist.com',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      localizationsDelegates: context.localizationDelegates,
      supportedLocales: context.supportedLocales,
      locale: context.locale,
      routerConfig: router,
    );
  }
}
