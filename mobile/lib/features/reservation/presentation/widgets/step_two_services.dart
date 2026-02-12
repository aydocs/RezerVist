import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../home/domain/models/business.dart';
import 'services/service_search_bar.dart';
import 'services/service_subtotal_banner.dart';
import 'services/service_item_card.dart';

class StepTwoServices extends StatefulWidget {
  final List<BusinessCategory> categories;
  final Map<int, int> selectedQuantities;
  final Function(int, int) onQuantityChanged;

  const StepTwoServices({
    super.key,
    required this.categories,
    required this.selectedQuantities,
    required this.onQuantityChanged,
  });

  @override
  State<StepTwoServices> createState() => _StepTwoServicesState();
}

class _StepTwoServicesState extends State<StepTwoServices> {
  final TextEditingController _searchController = TextEditingController();

  List<BusinessCategory> get filteredCategories {
    if (_searchController.text.isEmpty) {
      return widget.categories;
    }

    final query = _searchController.text.toLowerCase();
    List<BusinessCategory> filtered = [];

    for (var category in widget.categories) {
      final matchingItems = category.items
          ?.where((item) => item.name.toLowerCase().contains(query))
          .toList();

      if (matchingItems != null && matchingItems.isNotEmpty) {
        filtered.add(BusinessCategory(
          id: category.id,
          name: category.name,
          items: matchingItems,
        ));
      }
    }
    return filtered;
  }

  // Calculate running subtotal
  double get _subtotal {
    double total = 0;
    for (var category in widget.categories) {
      if (category.items != null) {
        for (var item in category.items!) {
          if (widget.selectedQuantities.containsKey(item.id)) {
            total += item.price * widget.selectedQuantities[item.id]!;
          }
        }
      }
    }
    return total;
  }

  int get _selectedItemCount {
    int count = 0;
    widget.selectedQuantities.forEach((_, qty) => count += qty);
    return count;
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 16, 16, 0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    'Ekstra Hizmetler',
                    style: GoogleFonts.outfit(
                      fontSize: 18,
                      fontWeight: FontWeight.w700,
                      color: const Color(0xFF1E293B),
                    ),
                  ),
                  Text(
                    'Opsiyonel',
                    style: GoogleFonts.outfit(
                      fontSize: 12,
                      fontWeight: FontWeight.w500,
                      color: const Color(0xFF94A3B8),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              const SizedBox(height: 12),
              ServiceSearchBar(
                controller: _searchController,
                onChanged: () => setState(() {}),
              ),
            ],
          ),
        ),
        // Subtotal banner
        // Subtotal banner
        ServiceSubtotalBanner(
          itemCount: _selectedItemCount,
          subtotal: _subtotal,
        ),
        const SizedBox(height: 12),
        Expanded(
          child: ListView.builder(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: filteredCategories.length,
            itemBuilder: (context, index) {
              final category = filteredCategories[index];
              final items = category.items ?? [];

              if (items.isEmpty) return const SizedBox.shrink();

              return Container(
                margin: const EdgeInsets.only(bottom: 12),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: Theme(
                  data: Theme.of(context)
                      .copyWith(dividerColor: Colors.transparent),
                  child: ExpansionTile(
                    initiallyExpanded: true,
                    leading: const Icon(Icons.circle,
                        size: 10, color: Color(0xFF7A1DD1)),
                    title: Text(
                      category.name,
                      style: GoogleFonts.outfit(
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF1E293B),
                      ),
                    ),
                    children: items.map((item) {
                      final qty = widget.selectedQuantities[item.id] ?? 0;
                      return ServiceItemCard(
                        item: item,
                        quantity: qty,
                        onQuantityChanged: (newQty) =>
                            widget.onQuantityChanged(item.id, newQty),
                      );
                    }).toList(),
                  ),
                ),
              );
            },
          ),
        ),
      ],
    );
  }
}
