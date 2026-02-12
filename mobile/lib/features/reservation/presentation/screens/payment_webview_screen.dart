import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:google_fonts/google_fonts.dart';

class PaymentWebViewScreen extends StatefulWidget {
  final String url;
  final String title;

  const PaymentWebViewScreen({
    super.key,
    required this.url,
    this.title = 'Ödeme',
  });

  @override
  State<PaymentWebViewScreen> createState() => _PaymentWebViewScreenState();
}

class _PaymentWebViewScreenState extends State<PaymentWebViewScreen> {
  late final WebViewController _controller;
  bool _isLoading = true;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _initController();
  }

  void _initController() {
    _controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(Colors.white)
      ..setNavigationDelegate(
        NavigationDelegate(
          onPageStarted: (String url) {
            if (mounted) {
              setState(() {
                _isLoading = true;
                _errorMessage = null;
              });
            }
          },
          onPageFinished: (String url) {
            if (mounted) {
              setState(() => _isLoading = false);
            }
          },
          onWebResourceError: (WebResourceError error) {
            debugPrint(
                'WebView Error: ${error.description} (Code: ${error.errorCode}, Type: ${error.errorType}');
            // Only treat main frame errors as fatal. Sub-resource errors (fonts, images)
            // should not crash the page or trigger the broken WebViewClient identifier bug.
            if (error.isForMainFrame ?? true) {
              if (mounted) {
                setState(() {
                  _isLoading = false;
                  _errorMessage = 'Bağlantı hatası: ${error.description}';
                });
              }
            }
          },
          onNavigationRequest: (NavigationRequest request) {
            if (request.url.contains('booking/confirmation') ||
                request.url.contains('payment/success')) {
              Navigator.of(context).pop(true);
              return NavigationDecision.prevent;
            }
            return NavigationDecision.navigate;
          },
        ),
      )
      ..loadRequest(Uri.parse(
        widget.url
            .replaceFirst('127.0.0.1', '10.0.2.2')
            .replaceFirst('localhost', '10.0.2.2'),
      ));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text(
          widget.title,
          style: GoogleFonts.outfit(
            color: const Color(0xFF1E293B),
            fontWeight: FontWeight.w700,
          ),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.close_rounded, color: Colors.black),
          onPressed: () => Navigator.of(context).pop(false),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh_rounded, color: Colors.black),
            onPressed: () => _controller.reload(),
          ),
        ],
      ),
      body: Stack(
        children: [
          if (_errorMessage != null)
            Center(
              child: Padding(
                padding: const EdgeInsets.all(24.0),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.error_outline_rounded,
                        size: 64, color: Colors.red),
                    const SizedBox(height: 16),
                    Text(
                      'Hay Aksi!',
                      style: GoogleFonts.outfit(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                        color: const Color(0xFF1E293B),
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      _errorMessage!,
                      textAlign: TextAlign.center,
                      style: GoogleFonts.outfit(
                        color: const Color(0xFF64748B),
                      ),
                    ),
                    const SizedBox(height: 24),
                    ElevatedButton.icon(
                      onPressed: () => _controller.reload(),
                      icon: const Icon(Icons.refresh_rounded),
                      label: const Text('Tekrar Dene'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF7A1DD1),
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(
                            horizontal: 24, vertical: 12),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            )
          else
            WebViewWidget(controller: _controller),
          if (_isLoading)
            const Center(
              child: CircularProgressIndicator(
                color: Color(0xFF7A1DD1),
              ),
            ),
        ],
      ),
    );
  }
}
