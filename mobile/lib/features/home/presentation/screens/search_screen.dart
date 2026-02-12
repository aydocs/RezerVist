import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../domain/models/business_filter.dart';
import '../providers/business_provider.dart';
import '../widgets/business_card.dart';
import '../widgets/business_grid_card.dart';

class SearchScreen extends ConsumerStatefulWidget {
  final String? query;
  final String? date;
  final int? guests;

  const SearchScreen({
    super.key,
    this.query,
    this.date,
    this.guests,
  });

  @override
  ConsumerState<SearchScreen> createState() => _SearchScreenState();
}

class _SearchScreenState extends ConsumerState<SearchScreen> {
  late TextEditingController _searchController;
  bool _isGridView = false;

  @override
  void initState() {
    super.initState();
    _searchController = TextEditingController(text: widget.query);

    // Defer the provider update to avoid built-time conflict,
    // or just assume the provider will pick it up if we set it.
    // However, businessesProvider watches businessFilterProvider.
    // So we should update businessFilterProvider.
    WidgetsBinding.instance.addPostFrameCallback((_) {
      ref.read(businessFilterProvider.notifier).state = BusinessFilter(
        query: widget.query,
      );
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  void _onSearchChanged(String value) {
    ref.read(businessFilterProvider.notifier).state =
        ref.read(businessFilterProvider).copyWith(query: value);
  }

  @override
  Widget build(BuildContext context) {
    final businessesState = ref.watch(businessesProvider);

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_rounded, color: Colors.black),
          onPressed: () => context.pop(),
        ),
        title: Container(
          height: 48,
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: const Color(0xFFE2E8F0)),
          ),
          child: TextField(
            controller: _searchController,
            onChanged: _onSearchChanged,
            decoration: const InputDecoration(
              prefixIcon: Icon(Icons.search_rounded, color: Color(0xFF94A3B8)),
              hintText: 'Mekan ara...',
              border: InputBorder.none,
              contentPadding: EdgeInsets.symmetric(vertical: 12),
              filled: true,
              fillColor: Colors.white,
            ),
            style: GoogleFonts.outfit(
              fontWeight: FontWeight.w600,
              color: const Color(0xFF1E293B),
            ),
          ),
        ),
        actions: [
          IconButton(
            onPressed: () {
              setState(() {
                _isGridView = !_isGridView;
              });
            },
            icon: Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: const Color(0xFFF1F5F9),
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Icon(
                _isGridView
                    ? Icons.view_agenda_rounded
                    : Icons.grid_view_rounded,
                color: const Color(0xFF64748B),
                size: 20,
              ),
            ),
          ),
          const SizedBox(width: 16),
        ],
      ),
      body: businessesState.when(
        data: (businesses) {
          if (businesses.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(Icons.search_off_rounded,
                      size: 64, color: Color(0xFFCBD5E1)),
                  const SizedBox(height: 16),
                  Text(
                    'Sonuç bulunamadı',
                    style: GoogleFonts.outfit(
                      fontSize: 18,
                      fontWeight: FontWeight.w700,
                      color: const Color(0xFF94A3B8),
                    ),
                  ),
                ],
              ),
            );
          }
          if (_isGridView) {
            return GridView.builder(
              padding: const EdgeInsets.all(24),
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2,
                childAspectRatio: 0.75,
                mainAxisSpacing: 16,
                crossAxisSpacing: 16,
              ),
              itemCount: businesses.length,
              itemBuilder: (context, index) {
                final business = businesses[index];
                return BusinessGridCard(
                  business: business,
                  onTap: () {
                    context.pushNamed(
                      'businessDetail',
                      pathParameters: {'id': business.id.toString()},
                      queryParameters: {'name': business.name},
                    );
                  },
                );
              },
            );
          }

          return ListView.separated(
            itemCount: businesses.length,
            padding: const EdgeInsets.all(24),
            separatorBuilder: (context, index) => const SizedBox(height: 16),
            itemBuilder: (context, index) {
              final business = businesses[index];
              return BusinessCard(
                business: business,
                onTap: () {
                  context.pushNamed(
                    'businessDetail',
                    pathParameters: {'id': business.id.toString()},
                    queryParameters: {'name': business.name},
                  );
                },
              );
            },
          );
        },
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, stack) => Center(child: Text('Error: $e')),
      ),
    );
  }
}
