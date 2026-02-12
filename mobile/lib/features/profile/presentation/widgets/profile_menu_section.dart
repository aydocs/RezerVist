import 'package:flutter/material.dart';

class ProfileMenuSection extends StatelessWidget {
  final List<Widget> children;

  const ProfileMenuSection({
    super.key,
    required this.children,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      children: children,
    );
  }
}
