import 'package:flutter/material.dart';

/// A shimmer loading widget for premium skeleton loading effects
class ShimmerLoading extends StatefulWidget {
  final double width;
  final double height;
  final double borderRadius;

  const ShimmerLoading({
    super.key,
    this.width = double.infinity,
    required this.height,
    this.borderRadius = 12,
  });

  @override
  State<ShimmerLoading> createState() => _ShimmerLoadingState();
}

class _ShimmerLoadingState extends State<ShimmerLoading>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _animation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      duration: const Duration(milliseconds: 1500),
      vsync: this,
    )..repeat();
    _animation = Tween<double>(begin: -1, end: 2).animate(
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
    return AnimatedBuilder(
      animation: _animation,
      builder: (context, child) {
        return Container(
          width: widget.width,
          height: widget.height,
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(widget.borderRadius),
            gradient: LinearGradient(
              begin: Alignment(_animation.value - 1, 0),
              end: Alignment(_animation.value, 0),
              colors: const [
                Color(0xFF1E293B),
                Color(0xFF334155),
                Color(0xFF1E293B),
              ],
            ),
          ),
        );
      },
    );
  }
}

/// Pre-built skeleton card for business listings
class BusinessCardSkeleton extends StatelessWidget {
  const BusinessCardSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: const Color(0xFF1E293B),
        borderRadius: BorderRadius.circular(16),
      ),
      child: const Row(
        children: [
          ShimmerLoading(width: 64, height: 64, borderRadius: 12),
          SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                ShimmerLoading(height: 16, borderRadius: 6),
                SizedBox(height: 8),
                ShimmerLoading(width: 120, height: 12, borderRadius: 6),
                SizedBox(height: 6),
                ShimmerLoading(width: 160, height: 10, borderRadius: 6),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

/// Pre-built skeleton for reservation cards
class ReservationCardSkeleton extends StatelessWidget {
  const ReservationCardSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: const Color(0xFF1E293B),
        borderRadius: BorderRadius.circular(16),
      ),
      child: const Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              ShimmerLoading(width: 180, height: 16, borderRadius: 6),
              ShimmerLoading(width: 70, height: 22, borderRadius: 20),
            ],
          ),
          SizedBox(height: 12),
          Row(
            children: [
              ShimmerLoading(width: 100, height: 12, borderRadius: 6),
              SizedBox(width: 16),
              ShimmerLoading(width: 60, height: 12, borderRadius: 6),
              SizedBox(width: 16),
              ShimmerLoading(width: 60, height: 12, borderRadius: 6),
            ],
          ),
        ],
      ),
    );
  }
}

/// A loading list builder
class SkeletonList extends StatelessWidget {
  final int count;
  final Widget Function() builder;

  const SkeletonList({
    super.key,
    this.count = 5,
    required this.builder,
  });

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: count,
      itemBuilder: (_, __) => builder(),
    );
  }
}
