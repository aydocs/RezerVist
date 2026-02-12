import 'package:flutter/material.dart';
import '../category_pill.dart';

class HomeCategoryList extends StatelessWidget {
  const HomeCategoryList({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text(
                'Kategorilere Göz At',
                style: TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.w900,
                    color: Color(0xFF111827)),
              ),
              TextButton(
                onPressed: () {},
                child: const Text('Tümünü Gör',
                    style: TextStyle(
                        color: Color(0xFF7A1DD1), fontWeight: FontWeight.bold)),
              ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        SizedBox(
          height: 48,
          child: ListView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16),
            children: [
              _buildCategoryPill('Tümü', Icons.grid_view_rounded, true),
              _buildCategoryPill('Restoran', Icons.restaurant_rounded, false),
              _buildCategoryPill('Cafe', Icons.coffee_rounded, false),
              _buildCategoryPill('Güzellik', Icons.auto_awesome_rounded, false),
              _buildCategoryPill('Spor', Icons.fitness_center_rounded, false),
              _buildCategoryPill('Eğlence', Icons.celebration_rounded, false),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildCategoryPill(String name, IconData icon, bool isActive) {
    return CategoryPill(
      name: name,
      icon: icon,
      isActive: isActive,
      onTap: () {}, // TODO: Implement category filtering
    );
  }
}
