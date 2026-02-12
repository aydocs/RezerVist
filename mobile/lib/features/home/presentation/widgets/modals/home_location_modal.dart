import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class HomeLocationModal extends StatefulWidget {
  final TextEditingController controller;
  final VoidCallback onLocationSelected;

  const HomeLocationModal({
    super.key,
    required this.controller,
    required this.onLocationSelected,
  });

  @override
  State<HomeLocationModal> createState() => _HomeLocationModalState();
}

class _HomeLocationModalState extends State<HomeLocationModal> {
  // Mock data moved here
  final List<Map<String, String>> _allResults = [
    {'title': 'Adana Sofrası', 'address': 'Seyhan, Adana'},
    {'title': 'Adıyaman Çiğköftecisi', 'address': 'Merkez, Adıyaman'},
    {
      'title': 'Saray Sofrası',
      'address': 'Cumhuriyet Meydanı No:12, Çankaya, Ankara'
    },
    {
      'title': 'Deniz Mahsulleri Restaurant',
      'address': 'Kordon Boyu, Alsancak, İzmir'
    },
    {
      'title': 'Güzellik Uzmanı Studio',
      'address': 'Nişantaşı, Teşvikiye Cad. No:67, İstanbul'
    },
  ];

  @override
  Widget build(BuildContext context) {
    final query = widget.controller.text.toLowerCase();
    final filteredResults = _allResults.where((item) {
      return item['title']!.toLowerCase().contains(query) ||
          item['address']!.toLowerCase().contains(query);
    }).toList();

    return Container(
      height: MediaQuery.of(context).size.height * 0.85,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
      ),
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 32),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFFE2E8F0)),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.02),
                  blurRadius: 10,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        'NEREYE?',
                        style: GoogleFonts.outfit(
                          fontSize: 11,
                          fontWeight: FontWeight.w800,
                          color: const Color(0xFF94A3B8),
                          letterSpacing: 0.5,
                        ),
                      ),
                      const SizedBox(height: 4),
                      TextField(
                        controller: widget.controller,
                        autofocus: true,
                        cursorColor: const Color(0xFF7A1DD1),
                        onChanged: (val) {
                          setState(() {});
                        },
                        style: GoogleFonts.outfit(
                          fontSize: 18,
                          fontWeight: FontWeight.w900,
                          color: Colors.black,
                        ),
                        decoration: const InputDecoration(
                          hintText: 'Mekan ara...',
                          hintStyle: TextStyle(color: Color(0xFF94A3B8)),
                          isDense: true,
                          contentPadding: EdgeInsets.zero,
                          border: InputBorder.none,
                          enabledBorder: InputBorder.none,
                          focusedBorder: InputBorder.none,
                          filled: true,
                          fillColor: Colors.white,
                        ),
                      ),
                    ],
                  ),
                ),
                const Icon(Icons.search_rounded,
                    color: Color(0xFF7A1DD1), size: 28),
              ],
            ),
          ),
          const SizedBox(height: 32),
          Expanded(
            child: ListView.builder(
              itemCount: filteredResults.length,
              itemBuilder: (context, index) {
                final item = filteredResults[index];
                return _buildLocationResult(
                  item['title']!,
                  item['address']!,
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildLocationResult(String title, String address) {
    return InkWell(
      onTap: () {
        widget.controller.text = title;
        widget.onLocationSelected();
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
}
