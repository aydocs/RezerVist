import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';

class SocialLoginButton extends StatelessWidget {
  final bool isGoogle;
  final bool isLoading;
  final VoidCallback? onTap;

  const SocialLoginButton({
    super.key,
    required this.isGoogle,
    required this.isLoading,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(20),
      child: Container(
        height: 64,
        decoration: BoxDecoration(
          color: isGoogle ? Colors.white : Colors.black,
          borderRadius: BorderRadius.circular(20),
          border: isGoogle
              ? Border.all(color: const Color(0xFFE2E8F0), width: 1.5)
              : null,
          boxShadow: [
            BoxShadow(
              color: isGoogle
                  ? Colors.black.withOpacity(0.05)
                  : Colors.black.withOpacity(0.2),
              blurRadius: 15,
              offset: const Offset(0, 6),
            ),
          ],
        ),
        child: Center(
          child: isLoading
              ? SizedBox(
                  width: 24,
                  height: 24,
                  child: CircularProgressIndicator(
                    strokeWidth: 2,
                    color: isGoogle ? const Color(0xFF7A1DD1) : Colors.white,
                  ),
                )
              : (isGoogle
                  ? SvgPicture.network(
                      'https://www.vectorlogo.zone/logos/google/google-icon.svg',
                      height: 26,
                    )
                  : const Icon(Icons.apple, color: Colors.white, size: 34)),
        ),
      ),
    );
  }
}
