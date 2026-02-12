import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../providers/business_provider.dart';
import '../widgets/business_detail/business_sliver_app_bar.dart';
import '../widgets/business_detail/business_info_section.dart';
import '../widgets/business_detail/business_menus_section.dart';
import '../widgets/business_detail/business_reviews_section.dart';
import '../widgets/business_detail/business_sticky_footer.dart';

class BusinessDetailScreen extends ConsumerStatefulWidget {
  final int businessId;
  final String businessName;

  const BusinessDetailScreen({
    super.key,
    required this.businessId,
    required this.businessName,
  });

  @override
  ConsumerState<BusinessDetailScreen> createState() =>
      _BusinessDetailScreenState();
}

class _BusinessDetailScreenState extends ConsumerState<BusinessDetailScreen> {
  final ScrollController _scrollController = ScrollController();
  bool _isScrolled = false;

  @override
  void initState() {
    super.initState();
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _scrollController.removeListener(_onScroll);
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.offset > 240 && !_isScrolled) {
      setState(() => _isScrolled = true);
    } else if (_scrollController.offset <= 240 && _isScrolled) {
      setState(() => _isScrolled = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final businessAsync = ref.watch(businessDetailProvider(widget.businessId));

    return Scaffold(
      backgroundColor: Colors.white,
      body: businessAsync.when(
        data: (business) => Stack(
          children: [
            CustomScrollView(
              controller: _scrollController,
              slivers: [
                BusinessSliverAppBar(
                  business: business,
                  isScrolled: _isScrolled,
                ),
                SliverList(
                  delegate: SliverChildListDelegate([
                    BusinessInfoSection(business: business),
                    BusinessMenusSection(business: business),
                    BusinessReviewsSection(business: business),
                    const SizedBox(height: 180), // Bottom padding for button
                  ]),
                ),
              ],
            ),
            BusinessStickyFooter(business: business),
          ],
        ),
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, st) => Center(child: Text('Hata: $e')),
      ),
    );
  }
}
