import 'package:flutter/material.dart';

class HomeBackground extends StatefulWidget {
  const HomeBackground({super.key});

  @override
  State<HomeBackground> createState() => _HomeBackgroundState();
}

class _HomeBackgroundState extends State<HomeBackground>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _animation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      duration: const Duration(seconds: 4),
      vsync: this,
    )..repeat(reverse: true);

    _animation = Tween<double>(begin: 0.95, end: 1.05).animate(
      CurvedAnimation(parent: _controller, curve: Curves.easeInOut),
    );
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        // 1. Modern Background Gradient
        Positioned.fill(
          child: Container(
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
                colors: [
                  Color(0xFFF8F9FF),
                  Color(0xFFF1F4FF),
                  Color(0xFFE8EBFF),
                ],
              ),
            ),
          ),
        ),

        // 2. Decorative Background Elements (Glassmorphism / Abstract)
        // Animated Bubble 1
        Positioned(
          top: -100,
          right: -100,
          child: AnimatedBuilder(
            animation: _animation,
            builder: (context, child) {
              return Transform.scale(
                scale: _animation.value,
                child: child,
              );
            },
            child: Container(
              width: 300,
              height: 300,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: const Color(0xFF7A1DD1).withOpacity(0.05),
              ),
            ),
          ),
        ),
        // Animated Bubble 2
        Positioned(
          bottom: 100,
          left: -50,
          child: AnimatedBuilder(
            animation: _animation,
            builder: (context, child) {
              return Transform.scale(
                scale: _animation.value * 1.02, // Slightly different scale
                child: child,
              );
            },
            child: Container(
              width: 200,
              height: 200,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: const Color(0xFF600DB4).withOpacity(0.04),
              ),
            ),
          ),
        ),
      ],
    );
  }
}
