import 'package:flutter/material.dart';

class ServiceSearchBar extends StatelessWidget {
  final TextEditingController controller;
  final VoidCallback onChanged;

  const ServiceSearchBar({
    super.key,
    required this.controller,
    required this.onChanged,
  });

  @override
  Widget build(BuildContext context) {
    return TextField(
      controller: controller,
      decoration: InputDecoration(
        hintText: 'Hizmetlerde ara...',
        prefixIcon: const Icon(Icons.search, color: Color(0xFF94A3B8)),
        filled: true,
        fillColor: const Color(0xFFF8FAFC),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
        ),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16),
      ),
      onChanged: (val) => onChanged(),
    );
  }
}
