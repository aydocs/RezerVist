import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:go_router/go_router.dart';

import '../providers/business_provider.dart';
import '../widgets/guest_selector_modal.dart';

import '../widgets/business_section.dart';

import '../widgets/home_screen/home_header.dart';
import '../widgets/home_screen/home_background.dart';
import '../widgets/home_screen/home_search_card.dart';
import '../widgets/home_screen/home_category_list.dart';
import '../widgets/modals/home_calendar_modal.dart';
import '../widgets/modals/home_location_modal.dart';

class HomeScreen extends ConsumerStatefulWidget {
  const HomeScreen({super.key});

  @override
  ConsumerState<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends ConsumerState<HomeScreen> {
  late DateTime _selectedDate;
  int _guestCount = 2;
  int _childCount = 0;
  final TextEditingController _locationController = TextEditingController();

  @override
  void initState() {
    super.initState();
    // Default to tomorrow
    _selectedDate = DateTime.now().add(const Duration(days: 1));
  }

  @override
  void dispose() {
    _locationController.dispose();
    super.dispose();
  }

  Future<void> _selectDate() async {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return HomeCalendarModal(
          initialDate: _selectedDate,
          onDateSelected: (date) {
            setState(() {
              _selectedDate = date;
            });
          },
        );
      },
    );
  }

  void _showGuestPicker() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return GuestSelectorModal(
          initialGuestCount: _guestCount,
          initialChildCount: _childCount,
          onApply: (guests, children) {
            setState(() {
              _guestCount = guests;
              _childCount = children;
            });
          },
        );
      },
    );
  }

  void _showLocationPicker() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return HomeLocationModal(
          controller: _locationController,
          onLocationSelected: () {
            setState(() {});
            Navigator.pop(context);
          },
        );
      },
    );
  }

  Widget _buildLocationResult(String title, String address) {
    return InkWell(
      onTap: () {
        _locationController.text = title;
        setState(() {});
        Navigator.pop(context);
      },
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 12),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: const Color(0xFF7A1DD1).withOpacity(0.08),
                borderRadius: BorderRadius.circular(12),
              ),
              child: const Icon(Icons.business_rounded,
                  color: Color(0xFF7A1DD1), size: 20),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: GoogleFonts.outfit(
                      fontSize: 16,
                      fontWeight: FontWeight.w700,
                      color: const Color(0xFF1E293B),
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    address,
                    style: GoogleFonts.outfit(
                      fontSize: 13,
                      fontWeight: FontWeight.w500,
                      color: const Color(0xFF94A3B8),
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final businessesState = ref.watch(businessesProvider);

    return Scaffold(
      backgroundColor: Colors.white,
      body: CustomScrollView(
        slivers: [
          SliverFillRemaining(
            hasScrollBody: false,
            child: Stack(
              children: [
                // 1. & 2. Background
                const HomeBackground(),

                // 3. Content Layer
                SafeArea(
                  child: Padding(
                    padding: const EdgeInsets.only(
                        left: 24, right: 24, top: 48, bottom: 16),
                    child: Column(
                      children: [
                        // Top Bar
                        const HomeHeader(),
                        const SizedBox(height: 24),

                        // Search Card
                        HomeSearchCard(
                          locationText: _locationController.text,
                          selectedDate: _selectedDate,
                          guestCount: _guestCount,
                          childCount: _childCount,
                          onLocationTap: _showLocationPicker,
                          onDateTap: _selectDate,
                          onGuestTap: _showGuestPicker,
                          onSearch: () {
                            context.push(
                              '/search',
                              extra: {
                                'query': _locationController.text,
                                'date': _selectedDate.toString(),
                                'guests': _guestCount,
                              },
                            );
                          },
                        ),
                        const Spacer(),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),

          // Categories Section (Horizontal Pills)
          const SliverToBoxAdapter(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                SizedBox(height: 32),
                HomeCategoryList(),
              ],
            ),
          ),

          // Top Rated Section
          businessesState.when(
            data: (businesses) => businesses.isEmpty
                ? const SliverToBoxAdapter(child: SizedBox())
                : SliverToBoxAdapter(
                    child: BusinessSection(
                      title: 'En Çok Puan Alan',
                      icon: Icons.star_rounded,
                      iconColor: const Color(0xFF334155),
                      businesses: List.from(businesses)
                        ..sort((a, b) =>
                            (b.rating ?? 0.0).compareTo(a.rating ?? 0.0)),
                    ),
                  ),
            loading: () => const SliverToBoxAdapter(
                child: Center(child: CircularProgressIndicator())),
            error: (e, _) =>
                SliverToBoxAdapter(child: Center(child: Text('Hata: $e'))),
          ),

          // Most Popular Section
          businessesState.when(
            data: (businesses) => businesses.isEmpty
                ? const SliverToBoxAdapter(child: SizedBox())
                : SliverToBoxAdapter(
                    child: BusinessSection(
                      title: 'En Popüler',
                      icon: Icons.local_fire_department_rounded,
                      iconColor: const Color(0xFF334155),
                      businesses: List.from(businesses)
                        ..sort((a, b) => (b.reviewsCount ?? 0)
                            .compareTo(a.reviewsCount ?? 0)),
                    ),
                  ),
            loading: () => const SliverToBoxAdapter(child: SizedBox()),
            error: (e, _) => const SliverToBoxAdapter(child: SizedBox()),
          ),

          // Best Seller Section (Mocked logic for now)
          businessesState.when(
            data: (businesses) => businesses.isEmpty
                ? const SliverToBoxAdapter(child: SizedBox())
                : SliverToBoxAdapter(
                    child: BusinessSection(
                      title: 'En Çok Satış Yapan',
                      icon: Icons.monetization_on_rounded,
                      iconColor: const Color(0xFF334155),
                      businesses: businesses.reversed.toList(),
                    ),
                  ),
            loading: () => const SliverToBoxAdapter(child: SizedBox()),
            error: (e, _) => const SliverToBoxAdapter(child: SizedBox()),
          ),

          const SliverToBoxAdapter(child: SizedBox(height: 120)),
        ],
      ),
    );
  }
}
