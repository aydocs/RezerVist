import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AuthTextField extends StatelessWidget {
  final String label;
  final String hint;
  final TextEditingController controller;
  final String? Function(String?)? validator;
  final TextInputType keyboardType;
  final bool obscureText;
  final VoidCallback? onToggleVisibility;
  final Widget? suffixIcon;

  const AuthTextField({
    super.key,
    required this.label,
    required this.hint,
    required this.controller,
    this.validator,
    this.keyboardType = TextInputType.text,
    this.obscureText = false,
    this.onToggleVisibility,
    this.suffixIcon,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.only(bottom: 8, left: 4),
          child: Text(
            label,
            style: GoogleFonts.outfit(
              color: const Color(0xFF1E293B),
              fontWeight: FontWeight.w700,
              fontSize: 15,
            ),
          ),
        ),
        TextFormField(
          controller: controller,
          validator: validator,
          keyboardType: keyboardType,
          obscureText: obscureText,
          style: GoogleFonts.outfit(
            color: const Color(0xFF0F172A),
            fontWeight: FontWeight.w600,
          ),
          decoration: InputDecoration(
            hintText: hint,
            filled: true,
            fillColor: Colors.white,
            hintStyle: GoogleFonts.outfit(
              color: const Color(0xFF94A3B8),
              fontWeight: FontWeight.w400,
            ),
            contentPadding:
                const EdgeInsets.symmetric(vertical: 22, horizontal: 20),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(16),
              borderSide:
                  const BorderSide(color: Color(0xFFE2E8F0), width: 1.5),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(16),
              borderSide: const BorderSide(color: Color(0xFF7A1DD1), width: 2),
            ),
            errorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(16),
              borderSide: const BorderSide(color: Colors.redAccent, width: 1.5),
            ),
            focusedErrorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(16),
              borderSide: const BorderSide(color: Colors.redAccent, width: 2),
            ),
            suffixIcon: suffixIcon ??
                (onToggleVisibility != null
                    ? IconButton(
                        icon: Icon(
                          obscureText
                              ? Icons.visibility_outlined
                              : Icons.visibility_off_outlined,
                          color: const Color(0xFF94A3B8),
                          size: 22,
                        ),
                        onPressed: onToggleVisibility,
                      )
                    : null),
          ),
        ),
      ],
    );
  }
}
