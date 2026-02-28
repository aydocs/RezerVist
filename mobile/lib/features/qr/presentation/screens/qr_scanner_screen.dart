import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import '../providers/qr_provider.dart';
import 'table_order_screen.dart';

class QrScannerScreen extends ConsumerStatefulWidget {
  const QrScannerScreen({super.key});

  @override
  ConsumerState<QrScannerScreen> createState() => _QrScannerScreenState();
}

class _QrScannerScreenState extends ConsumerState<QrScannerScreen> {
  final MobileScannerController _scannerController = MobileScannerController();
  bool _isProcessing = false;

  @override
  void dispose() {
    _scannerController.dispose();
    super.dispose();
  }

  Future<void> _onDetect(BarcodeCapture capture) async {
    if (_isProcessing) return;
    final barcode = capture.barcodes.firstOrNull;
    if (barcode == null || barcode.rawValue == null) return;

    setState(() => _isProcessing = true);

    try {
      final qrData = _parseQrUrl(barcode.rawValue!);
      if (qrData == null) {
        _showError('Geçersiz QR kodu');
        setState(() => _isProcessing = false);
        return;
      }

      final repo = ref.read(qrRepositoryProvider);
      final session = await repo.startSession(
        businessId: qrData['business_id']!,
        resourceId: qrData['resource_id']!,
      );

      ref.read(qrSessionProvider.notifier).state = session;

      if (mounted) {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (_) => TableOrderScreen(
              sessionToken: session['session_token'],
              tableName: session['table']?['name'] ?? 'Masa',
              businessName: session['business']?['name'] ?? '',
            ),
          ),
        );
      }
    } catch (e) {
      _showError('Oturum başlatılamadı: $e');
      setState(() => _isProcessing = false);
    }
  }

  /// Parse QR URL: rezervist://table/{business_id}/{resource_id}
  /// or https://rezervist.com/qr/{business_id}/{resource_id}
  Map<String, int>? _parseQrUrl(String url) {
    try {
      // Try URI format
      final uri = Uri.parse(url);
      final segments = uri.pathSegments;

      // Look for pattern: .../qr/{business_id}/{resource_id}
      for (int i = 0; i < segments.length - 2; i++) {
        if (segments[i] == 'qr' || segments[i] == 'table') {
          final bizId = int.tryParse(segments[i + 1]);
          final resId = int.tryParse(segments[i + 2]);
          if (bizId != null && resId != null) {
            return {'business_id': bizId, 'resource_id': resId};
          }
        }
      }

      // Try simple format: business_id,resource_id
      if (url.contains(',')) {
        final parts = url.split(',');
        final bizId = int.tryParse(parts[0].trim());
        final resId = int.tryParse(parts[1].trim());
        if (bizId != null && resId != null) {
          return {'business_id': bizId, 'resource_id': resId};
        }
      }

      return null;
    } catch (_) {
      return null;
    }
  }

  void _showError(String message) {
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(message), backgroundColor: Colors.red[400]),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      body: Stack(
        children: [
          // Camera
          MobileScanner(
            controller: _scannerController,
            onDetect: _onDetect,
          ),

          // Overlay
          Container(
            decoration: BoxDecoration(
              color: Colors.black.withOpacity(0.5),
            ),
          ),

          // Scanner frame
          Center(
            child: Container(
              width: 280,
              height: 280,
              decoration: BoxDecoration(
                border: Border.all(color: const Color(0xFF7A1DD1), width: 3),
                borderRadius: BorderRadius.circular(24),
              ),
            ),
          ),

          // Cut-out effect (transparent center)
          ClipPath(
            clipper: _ScannerOverlayClipper(),
            child: Container(color: Colors.black.withOpacity(0.6)),
          ),

          // Top bar
          SafeArea(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Row(
                children: [
                  IconButton(
                    onPressed: () => Navigator.pop(context),
                    icon: const Icon(Icons.arrow_back_rounded,
                        color: Colors.white, size: 28),
                  ),
                  const Spacer(),
                  IconButton(
                    onPressed: () => _scannerController.toggleTorch(),
                    icon: const Icon(Icons.flash_on_rounded,
                        color: Colors.white, size: 28),
                  ),
                ],
              ),
            ),
          ),

          // Bottom label
          Positioned(
            bottom: 120,
            left: 0,
            right: 0,
            child: Column(
              children: [
                Text(
                  'Masadaki QR Kodu Okutun',
                  textAlign: TextAlign.center,
                  style: GoogleFonts.outfit(
                    fontSize: 20,
                    fontWeight: FontWeight.w700,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  'QR kodu çerçevenin içine hizalayın',
                  textAlign: TextAlign.center,
                  style: GoogleFonts.outfit(
                    fontSize: 14,
                    color: Colors.white70,
                  ),
                ),
              ],
            ),
          ),

          // Loading overlay
          if (_isProcessing)
            Container(
              color: Colors.black54,
              child: const Center(
                child: CircularProgressIndicator(color: Color(0xFF7A1DD1)),
              ),
            ),
        ],
      ),
    );
  }
}

class _ScannerOverlayClipper extends CustomClipper<Path> {
  @override
  Path getClip(Size size) {
    final path = Path()..addRect(Rect.fromLTWH(0, 0, size.width, size.height));

    final cutout = RRect.fromRectAndRadius(
      Rect.fromCenter(
        center: Offset(size.width / 2, size.height / 2),
        width: 280,
        height: 280,
      ),
      const Radius.circular(24),
    );

    path.addRRect(cutout);
    path.fillType = PathFillType.evenOdd;
    return path;
  }

  @override
  bool shouldReclip(covariant CustomClipper<Path> oldClipper) => false;
}
