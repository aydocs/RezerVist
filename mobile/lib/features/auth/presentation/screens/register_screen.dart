import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:google_fonts/google_fonts.dart';

import '../widgets/auth_text_field.dart';
import '../widgets/auth_button.dart';
import '../widgets/social_login_button.dart';
import '../widgets/terms_bottom_sheet.dart';
import '../providers/auth_provider.dart';

class RegisterScreen extends ConsumerStatefulWidget {
  const RegisterScreen({super.key});

  @override
  ConsumerState<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends ConsumerState<RegisterScreen> {
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _emailController = TextEditingController();
  final _referralController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  final _formKey = GlobalKey<FormState>();

  bool _obscurePassword = true;
  bool _termsAccepted = false;
  final Color primaryColor = const Color(0xFF7A1DD1);

  @override
  void dispose() {
    _nameController.dispose();
    _phoneController.dispose();
    _emailController.dispose();
    _referralController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  void _showTermsBottomSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => TermsBottomSheet(
        onAccepted: () {
          setState(() => _termsAccepted = true);
        },
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authProvider);

    ref.listen(authProvider, (previous, next) {
      if (next.value != null) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('auth.register_success'.tr())),
        );
      }
      if (next.hasError) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('${'auth.error_prefix'.tr()}${next.error}')),
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
                const SizedBox(height: 40),
                // Logo
                Center(
                  child: Container(
                    width: 70,
                    height: 70,
                    decoration: BoxDecoration(
                      color: const Color(0xFF7A1DD1),
                      borderRadius: BorderRadius.circular(18),
                      boxShadow: [
                        BoxShadow(
                          color: const Color(0xFF7A1DD1).withOpacity(0.3),
                          blurRadius: 20,
                          offset: const Offset(0, 10),
                        ),
                      ],
                    ),
                    child: Center(
                      child: Text(
                        'R',
                        style: GoogleFonts.outfit(
                          color: Colors.white,
                          fontSize: 36,
                          fontWeight: FontWeight.w900,
                        ),
                      ),
                    ),
                  ),
                ),
                const SizedBox(height: 32),

                // Title
                Center(
                  child: Text(
                    'auth.register.title'.tr(), // Hemen Katılın
                    style: GoogleFonts.outfit(
                      fontSize: 28,
                      fontWeight: FontWeight.w900,
                      color: const Color(0xFF0F172A),
                      letterSpacing: -0.5,
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                Center(
                  child: Text(
                    'auth.register.subtitle'.tr(), // Yeni mekanlar keşfedin...
                    textAlign: TextAlign.center,
                    style: GoogleFonts.outfit(
                      fontSize: 15,
                      color: const Color(0xFF64748B),
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ),
                const SizedBox(height: 40),

                // Name
                AuthTextField(
                  label: 'auth.register.name_label'.tr(),
                  hint: 'auth.register.name_hint'.tr(),
                  controller: _nameController,
                  validator: (v) =>
                      v?.isEmpty ?? true ? 'auth.field_required'.tr() : null,
                ),
                const SizedBox(height: 24),

                // Phone
                AuthTextField(
                  label: 'auth.register.phone_label'.tr(),
                  hint: 'auth.register.phone_hint'.tr(),
                  controller: _phoneController,
                  keyboardType: TextInputType.phone,
                ),
                const SizedBox(height: 24),

                // Email
                AuthTextField(
                  label: 'auth.register.email_label'.tr(),
                  hint: 'auth.register.email_hint'.tr(),
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

                // Referral
                AuthTextField(
                  label: 'auth.register.referral_label'.tr(),
                  hint: 'auth.register.referral_hint'.tr(),
                  controller: _referralController,
                  suffixIcon: const Padding(
                    padding: EdgeInsets.only(right: 12.0),
                    child: Icon(Icons.sell_outlined,
                        color: Color(0xFF94A3B8), size: 20),
                  ),
                ),
                const SizedBox(height: 24),

                // Password
                AuthTextField(
                  label: 'auth.register.password_label'.tr(),
                  hint: 'auth.register.password_hint'.tr(),
                  controller: _passwordController,
                  obscureText: _obscurePassword,
                  onToggleVisibility: () =>
                      setState(() => _obscurePassword = !_obscurePassword),
                  validator: (v) {
                    if ((v?.length ?? 0) < 8)
                      return 'auth.password_min_length'.tr();
                    if (!RegExp(
                            r'^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!@#$%^&*()_+]{8,}$')
                        .hasMatch(v!)) {
                      return 'auth.error_weak_password'.tr();
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 24),

                // Confirm Password
                AuthTextField(
                  label: 'auth.register.confirm_password_label'.tr(),
                  hint: 'auth.register.confirm_password_hint'.tr(),
                  controller: _confirmPasswordController,
                  obscureText: _obscurePassword,
                  validator: (v) => v != _passwordController.text
                      ? 'Şifreler uyuşmuyor'
                      : null,
                ),
                const SizedBox(height: 32),

                // Terms Card
                GestureDetector(
                  onTap: () => _showTermsBottomSheet(context),
                  child: AnimatedContainer(
                    duration: const Duration(milliseconds: 300),
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: _termsAccepted
                          ? const Color(0xFFF5F3FF)
                          : const Color(0xFFF8FAFC),
                      borderRadius: BorderRadius.circular(24),
                      border: Border.all(
                        color: _termsAccepted
                            ? const Color(0xFF7A1DD1).withOpacity(0.5)
                            : const Color(0xFFE2E8F0),
                        width: 1.5,
                      ),
                      boxShadow: _termsAccepted
                          ? [
                              BoxShadow(
                                color:
                                    const Color(0xFF7A1DD1).withOpacity(0.05),
                                blurRadius: 15,
                                offset: const Offset(0, 8),
                              )
                            ]
                          : null,
                    ),
                    child: Row(
                      children: [
                        // Custom Checkbox Animation Area
                        Container(
                          width: 48,
                          height: 48,
                          decoration: BoxDecoration(
                            color: _termsAccepted
                                ? const Color(0xFF7A1DD1)
                                : Colors.white,
                            borderRadius: BorderRadius.circular(16),
                            border: Border.all(
                              color: _termsAccepted
                                  ? const Color(0xFF7A1DD1)
                                  : const Color(0xFFCBD5E1),
                              width: 1.5,
                            ),
                          ),
                          child: Icon(
                            _termsAccepted
                                ? Icons.check_rounded
                                : Icons.article_outlined,
                            color: _termsAccepted
                                ? Colors.white
                                : const Color(0xFF64748B),
                            size: 24,
                          ),
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'auth.register.terms_title'.tr(),
                                style: GoogleFonts.outfit(
                                  fontWeight: FontWeight.w800,
                                  color: const Color(0xFF0F172A),
                                  fontSize: 15,
                                ),
                              ),
                              const SizedBox(height: 3),
                              Text(
                                _termsAccepted
                                    ? 'Belgeler başarıyla onaylandı'
                                    : 'auth.register.terms_subtitle'.tr(),
                                style: GoogleFonts.outfit(
                                  color: _termsAccepted
                                      ? const Color(0xFF7A1DD1).withOpacity(0.8)
                                      : const Color(0xFF64748B),
                                  fontSize: 12,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ],
                          ),
                        ),
                        Icon(
                          Icons.arrow_forward_ios_rounded,
                          size: 14,
                          color: _termsAccepted
                              ? const Color(0xFF7A1DD1)
                              : const Color(0xFF94A3B8),
                        ),
                      ],
                    ),
                  ),
                ),

                const SizedBox(height: 32),

                // Register Button
                AuthButton(
                  text: 'auth.register.button'.tr(),
                  isLoading: authState.isLoading,
                  onPressed: (authState.isLoading || !_termsAccepted)
                      ? null
                      : () {
                          if (_formKey.currentState!.validate()) {
                            ref.read(authProvider.notifier).register(
                                  name: _nameController.text,
                                  email: _emailController.text,
                                  password: _passwordController.text,
                                  referralCode: _referralController.text.isEmpty
                                      ? null
                                      : _referralController.text,
                                );
                          } else if (!_termsAccepted) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                  content: Text(
                                      'Lütfen yasal belgeleri onaylayın.')),
                            );
                          }
                        },
                ),
                const SizedBox(height: 24),

                // veya Divider
                Row(
                  children: [
                    Expanded(
                        child: Divider(
                            color: const Color(0xFFE2E8F0), thickness: 1)),
                    Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 16, vertical: 4),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(color: const Color(0xFFF1F5F9)),
                      ),
                      child: Text(
                        'auth.login.or_divider'.tr(),
                        style: GoogleFonts.outfit(
                          color: const Color(0xFF94A3B8),
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                    Expanded(
                        child: Divider(
                            color: const Color(0xFFE2E8F0), thickness: 1)),
                  ],
                ),
                const SizedBox(height: 24),

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
                const SizedBox(height: 32),

                // Login Link
                Center(
                  child: GestureDetector(
                    onTap: () => context.pop(),
                    child: RichText(
                      text: TextSpan(
                        style: GoogleFonts.outfit(
                          color: const Color(0xFF64748B),
                          fontSize: 15,
                          fontWeight: FontWeight.w500,
                        ),
                        children: [
                          TextSpan(
                              text: '${'auth.register.have_account'.tr()} '),
                          TextSpan(
                            text: 'auth.register.login_link'.tr(),
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
                const SizedBox(height: 48),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
