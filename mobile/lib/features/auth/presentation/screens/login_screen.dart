import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:google_fonts/google_fonts.dart';

import '../widgets/auth_text_field.dart';
import '../widgets/auth_button.dart';
import '../widgets/social_login_button.dart';
import '../providers/auth_provider.dart';

class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});

  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;
  bool _rememberMe = false;

  final _formKey = GlobalKey<FormState>();

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authProvider);

    // Listen to auth state changes
    ref.listen(authProvider, (previous, next) {
      if (next.value != null && next.value != previous?.value) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('auth.login_success'.tr())),
        );
      }
      if (next.hasError && !next.isLoading) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('${next.error}'),
            backgroundColor: Colors.redAccent,
            behavior: SnackBarBehavior.floating,
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
          ),
        );
      }
    });

    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24.0),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const SizedBox(height: 60),
                // Logo
                Center(
                  child: Container(
                    width: 84,
                    height: 84,
                    decoration: BoxDecoration(
                      gradient: const LinearGradient(
                        begin: Alignment.topLeft,
                        end: Alignment.bottomRight,
                        colors: [Color(0xFF7A1DD1), Color(0xFF600DB4)],
                      ),
                      borderRadius: BorderRadius.circular(22),
                      boxShadow: [
                        BoxShadow(
                          color: const Color(0xFF7C3AED).withOpacity(0.35),
                          blurRadius: 30,
                          offset: const Offset(0, 12),
                        ),
                        BoxShadow(
                          color: Colors.white.withOpacity(0.1),
                          blurRadius: 0,
                          offset: const Offset(0, -2),
                          spreadRadius: -1,
                        ),
                      ],
                    ),
                    child: Center(
                      child: Text(
                        'R',
                        style: GoogleFonts.outfit(
                          color: Colors.white,
                          fontSize: 48,
                          fontWeight: FontWeight.w900,
                          letterSpacing: -1,
                        ),
                      ),
                    ),
                  ),
                ),
                const SizedBox(height: 40),
                // Welcome Text
                Center(
                  child: Text(
                    'auth.login.welcome_title'.tr(),
                    style: GoogleFonts.outfit(
                      fontSize: 32,
                      fontWeight: FontWeight.w900,
                      color: const Color(0xFF060B1A),
                      letterSpacing: -1,
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                Center(
                  child: Text(
                    'auth.login.welcome_subtitle'.tr(),
                    textAlign: TextAlign.center,
                    style: GoogleFonts.outfit(
                      fontSize: 16,
                      color: const Color(0xFF64748B),
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ),
                const SizedBox(height: 40),

                // Email Label
                AuthTextField(
                  label: 'auth.login.email_label'.tr(),
                  hint: 'owner2@test.com',
                  controller: _emailController,
                  keyboardType: TextInputType.emailAddress,
                  validator: (v) {
                    if (v?.isEmpty ?? true) return 'auth.field_required'.tr();
                    if (!RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$')
                        .hasMatch(v!)) {
                      return 'auth.invalid_email'.tr();
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 24),

                // Password Label
                AuthTextField(
                  label: 'auth.login.password_label'.tr(),
                  hint: '••••••••',
                  controller: _passwordController,
                  obscureText: _obscurePassword,
                  onToggleVisibility: () {
                    setState(() {
                      _obscurePassword = !_obscurePassword;
                    });
                  },
                  validator: (v) {
                    if (v?.isEmpty ?? true) return 'auth.field_required'.tr();
                    if (v!.length < 6) return 'auth.password_min_length'.tr();
                    return null;
                  },
                ),
                const SizedBox(height: 20),

                // Remember Me & Forgot Password
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        Transform.scale(
                          scale: 0.9,
                          child: Switch(
                            value: _rememberMe,
                            onChanged: (value) {
                              setState(() {
                                _rememberMe = value;
                              });
                            },
                            activeColor: const Color(0xFF7A1DD1),
                            activeTrackColor:
                                const Color(0xFF7A1DD1).withOpacity(0.3),
                            inactiveThumbColor: Colors.white,
                            inactiveTrackColor: const Color(0xFFE2E8F0),
                          ),
                        ),
                        Text(
                          'auth.login.remember_me'.tr(),
                          style: GoogleFonts.outfit(
                            color: const Color(0xFF475569),
                            fontSize: 14,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ],
                    ),
                    TextButton(
                      onPressed: () {},
                      child: Text(
                        'auth.login.forgot_password'.tr(),
                        style: GoogleFonts.outfit(
                          color: const Color(0xFF7A1DD1),
                          fontWeight: FontWeight.w700,
                          fontSize: 14,
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 24),

                // Login Button
                // Login Button
                AuthButton(
                  text: 'auth.login.login_button'.tr(),
                  isLoading: authState.isLoading,
                  onPressed: () {
                    if (_formKey.currentState!.validate()) {
                      ref.read(authProvider.notifier).login(
                            _emailController.text,
                            _passwordController.text,
                          );
                    }
                  },
                ),
                const SizedBox(height: 32),

                // Divider
                Row(
                  children: [
                    Expanded(
                        child: Divider(
                            color: const Color(0xFFE2E8F0), thickness: 1)),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 12),
                      child: Text(
                        'auth.login.or_divider'.tr(),
                        style: GoogleFonts.outfit(
                          color: const Color(0xFF94A3B8),
                          fontSize: 14,
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ),
                    Expanded(
                        child: Divider(
                            color: const Color(0xFFE2E8F0), thickness: 1)),
                  ],
                ),
                const SizedBox(height: 32),

                // Social Logins
                Row(
                  children: [
                    Expanded(
                      child: SocialLoginButton(
                        isGoogle: true,
                        isLoading: authState.isLoading,
                        onTap: authState.isLoading
                            ? null
                            : () => ref
                                .read(authProvider.notifier)
                                .loginWithGoogle(),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: SocialLoginButton(
                        isGoogle: false,
                        isLoading: authState.isLoading,
                        onTap: authState.isLoading
                            ? null
                            : () => ref
                                .read(authProvider.notifier)
                                .loginWithApple(),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 40),

                // Register Link
                Center(
                  child: GestureDetector(
                    onTap: () => context.push('/register'),
                    child: RichText(
                      text: TextSpan(
                        style: GoogleFonts.outfit(
                          color: const Color(0xFF64748B),
                          fontSize: 15,
                          fontWeight: FontWeight.w500,
                        ),
                        children: [
                          TextSpan(text: '${'auth.login.no_account'.tr()} '),
                          TextSpan(
                            text: 'auth.login.register_button'.tr(),
                            style: const TextStyle(
                              color: Color(0xFF7A1DD1),
                              fontWeight: FontWeight.w800,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
                const SizedBox(height: 40),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
