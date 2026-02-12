import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import '../../providers/business_provider.dart';

import '../../../domain/models/business.dart';

class BusinessReviewsSection extends ConsumerWidget {
  final Business business;

  const BusinessReviewsSection({
    super.key,
    required this.business,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Değerlendirmeler',
                style: GoogleFonts.outfit(
                  fontSize: 18,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFF1E293B),
                ),
              ),
              Row(
                children: [
                  TextButton.icon(
                    onPressed: () => _showReviewDialog(context, ref, business),
                    icon: const Icon(Icons.edit_outlined,
                        size: 16, color: Color(0xFF7A1DD1)),
                    label: Text(
                      'Yorum Yaz',
                      style: GoogleFonts.outfit(
                        fontSize: 13,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF7A1DD1),
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        if (business.reviews == null || business.reviews!.isEmpty)
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 24),
            child: Text(
              'Henüz değerlendirme yok. İlk yorumu siz yazın!',
              style: GoogleFonts.outfit(
                fontSize: 14,
                color: const Color(0xFF94A3B8),
              ),
            ),
          )
        else
          ListView.separated(
            padding: const EdgeInsets.symmetric(horizontal: 24),
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: business.reviews!.take(3).length,
            separatorBuilder: (_, __) => const SizedBox(height: 16),
            itemBuilder: (context, index) {
              final review = business.reviews![index];
              return TweenAnimationBuilder<double>(
                tween: Tween(begin: 0.0, end: 1.0),
                duration: Duration(milliseconds: 400 + (index * 100)),
                builder: (context, value, child) {
                  return Transform.translate(
                    offset: Offset(0, 20 * (1 - value)),
                    child: Opacity(
                      opacity: value,
                      child: child,
                    ),
                  );
                },
                child: Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: const Color(0xFFF8FAFC),
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: Colors.grey.shade100),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Container(
                            width: 40,
                            height: 40,
                            decoration: BoxDecoration(
                              color: const Color(0xFFE2E8F0),
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: const Icon(Icons.person_rounded,
                                color: Color(0xFF94A3B8)),
                          ),
                          const SizedBox(width: 12),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                review.user?.name ?? 'Misafir',
                                style: GoogleFonts.outfit(
                                  fontSize: 14,
                                  fontWeight: FontWeight.w700,
                                  color: const Color(0xFF1E293B),
                                ),
                              ),
                              Row(
                                children: List.generate(5, (starIndex) {
                                  return Icon(
                                    Icons.star_rounded,
                                    size: 14,
                                    color: starIndex < (review.rating ?? 0)
                                        ? const Color(0xFFF59E0B)
                                        : const Color(0xFFE2E8F0),
                                  );
                                }),
                              ),
                            ],
                          ),
                          const Spacer(),
                          Text(
                            review.createdAt != null
                                ? DateFormat('d MMM yyyy')
                                    .format(DateTime.parse(review.createdAt!))
                                : '',
                            style: GoogleFonts.outfit(
                              fontSize: 12,
                              color: const Color(0xFF94A3B8),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 12),
                      Text(
                        review.comment ?? '',
                        style: GoogleFonts.outfit(
                          fontSize: 14,
                          color: const Color(0xFF475569),
                          height: 1.5,
                        ),
                      ),
                    ],
                  ),
                ),
              );
            },
          ),
      ],
    );
  }

  void _showReviewDialog(
      BuildContext context, WidgetRef ref, Business business) {
    int selectedRating = 0;
    final commentController = TextEditingController();
    bool isSubmitting = false;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) => StatefulBuilder(
        builder: (ctx, setModalState) => Container(
          padding: EdgeInsets.only(
            bottom: MediaQuery.of(ctx).viewInsets.bottom + 24,
            top: 24,
            left: 24,
            right: 24,
          ),
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: Container(
                  width: 40,
                  height: 4,
                  decoration: BoxDecoration(
                    color: const Color(0xFFE2E8F0),
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),
              const SizedBox(height: 20),
              Text(
                '${business.name} değerlendir',
                style: GoogleFonts.outfit(
                  fontSize: 20,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFF1E293B),
                ),
              ),
              const SizedBox(height: 20),
              Center(
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: List.generate(5, (index) {
                    return GestureDetector(
                      onTap: () =>
                          setModalState(() => selectedRating = index + 1),
                      child: Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 4),
                        child: Icon(
                          index < selectedRating
                              ? Icons.star_rounded
                              : Icons.star_outline_rounded,
                          size: 40,
                          color: index < selectedRating
                              ? const Color(0xFFF59E0B)
                              : const Color(0xFFCBD5E1),
                        ),
                      ),
                    );
                  }),
                ),
              ),
              const SizedBox(height: 20),
              TextField(
                controller: commentController,
                maxLines: 4,
                maxLength: 1000,
                style: GoogleFonts.outfit(
                  color: Colors.black,
                  fontSize: 16,
                ),
                decoration: InputDecoration(
                  hintText: 'Deneyiminizi paylaşın... (min 5 karakter)',
                  hintStyle: TextStyle(color: Colors.grey[400]),
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
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: Color(0xFF7A1DD1)),
                  ),
                ),
              ),
              const SizedBox(height: 16),
              SizedBox(
                width: double.infinity,
                height: 52,
                child: ElevatedButton(
                  onPressed: isSubmitting || selectedRating == 0
                      ? null
                      : () async {
                          if (commentController.text.trim().length < 5) {
                            ScaffoldMessenger.of(ctx).showSnackBar(
                              const SnackBar(
                                content: Text('Yorum en az 5 karakter olmalı.'),
                                backgroundColor: Colors.red,
                                behavior: SnackBarBehavior.floating,
                                margin: EdgeInsets.all(16),
                              ),
                            );
                            return;
                          }
                          setModalState(() => isSubmitting = true);
                          try {
                            await ref
                                .read(businessRepositoryProvider)
                                .submitReview(
                                  business.id,
                                  rating: selectedRating,
                                  comment: commentController.text.trim(),
                                );
                            if (ctx.mounted) Navigator.pop(ctx);
                            ref.invalidate(businessDetailProvider(business.id));
                            if (context.mounted) {
                              ScaffoldMessenger.of(context).showSnackBar(
                                const SnackBar(
                                  content:
                                      Text('Değerlendirmeniz gönderildi! ✨'),
                                  backgroundColor: Color(0xFF22C55E),
                                ),
                              );
                            }
                          } catch (e) {
                            setModalState(() => isSubmitting = false);
                            if (ctx.mounted) {
                              ScaffoldMessenger.of(ctx).showSnackBar(
                                SnackBar(content: Text('$e')),
                              );
                            }
                          }
                        },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF7A1DD1),
                    disabledBackgroundColor: const Color(0xFFCBD5E1),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16),
                    ),
                    elevation: 0,
                  ),
                  child: isSubmitting
                      ? const SizedBox(
                          width: 24,
                          height: 24,
                          child: CircularProgressIndicator(
                            color: Colors.white,
                            strokeWidth: 2,
                          ),
                        )
                      : Text(
                          'Gönder',
                          style: GoogleFonts.outfit(
                            fontSize: 16,
                            fontWeight: FontWeight.w700,
                            color: Colors.white,
                          ),
                        ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
