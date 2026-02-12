import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import 'package:intl/intl.dart';
import '../../../home/domain/models/business.dart';
import '../providers/reservation_provider.dart';
import 'step_one_details.dart';
import 'step_two_services.dart';
import 'step_three_payment.dart';
import '../screens/payment_webview_screen.dart';
import 'wizard/reservation_wizard_header.dart';
import 'wizard/reservation_wizard_stepper.dart';
import 'wizard/reservation_wizard_bottom_bar.dart';
import 'dialogs/reservation_loading_dialog.dart';
import 'dialogs/reservation_success_dialog.dart';

class ReservationWizardModal extends ConsumerStatefulWidget {
  final Business business;
  final List<BusinessCategory> categories;

  const ReservationWizardModal({
    super.key,
    required this.business,
    required this.categories,
  });

  @override
  ConsumerState<ReservationWizardModal> createState() =>
      _ReservationWizardModalState();
}

class _ReservationWizardModalState
    extends ConsumerState<ReservationWizardModal> {
  int _currentStep = 0;
  bool _isGoingForward = true;

  // Step 1 Data
  DateTime _selectedDate = DateTime.now();
  int _adults = 2;
  int _children = 0;

  int get _guestCount => _adults + _children;
  String? _selectedTimeSlot;

  // Step 2 Data
  final Map<int, int> _selectedMenuQuantities = {};

  // Step 3 Data
  String _paymentMethod = 'card';
  String _specialRequest = '';

  // --- Price Calculations ---
  double get _perPersonTotal {
    final ppp = widget.business.pricePerPerson ?? 0;

    if (widget.business.pricingType == 'per_person') {
      return ppp * _guestCount;
    }

    // Default: Fixed (Reservation Fee)
    return ppp;
  }

  double get _servicesTotal {
    double total = 0;
    for (var category in widget.categories) {
      if (category.items != null) {
        for (var item in category.items!) {
          if (_selectedMenuQuantities.containsKey(item.id)) {
            total += item.price * _selectedMenuQuantities[item.id]!;
          }
        }
      }
    }
    return total;
  }

  double get _grandTotal => _perPersonTotal + _servicesTotal;

  // --- Step Navigation ---
  void _nextStep() {
    if (_currentStep == 0) {
      if (_selectedTimeSlot == null) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: const Text('Lütfen bir saat seçin.'),
            backgroundColor: Colors.red.shade400,
            behavior: SnackBarBehavior.floating,
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          ),
        );
        return;
      }
    }
    setState(() {
      _isGoingForward = true;
      _currentStep++;
    });
  }

  void _prevStep() {
    if (_currentStep > 0) {
      setState(() {
        _isGoingForward = false;
        _currentStep--;
      });
    } else {
      Navigator.pop(context);
    }
  }

  Future<void> _submitReservation() async {
    final dateStr = DateFormat('yyyy-MM-dd').format(_selectedDate);

    List<Map<String, dynamic>> services = [];
    _selectedMenuQuantities.forEach((id, qty) {
      services.add({'id': id, 'quantity': qty});
    });

    try {
      showDialog(
        context: context,
        barrierDismissible: false,
        builder: (context) => const ReservationLoadingDialog(),
      );

      await ref.read(reservationControllerProvider.notifier).createReservation(
            businessId: widget.business.id,
            reservationDate: dateStr,
            reservationTime: _selectedTimeSlot!,
            guestCount: _guestCount,
            paymentMethod: _paymentMethod,
            services: services.isNotEmpty ? services : null,
            specialRequests:
                _specialRequest.isNotEmpty ? _specialRequest : null,
          );

      if (!mounted) return;
      Navigator.pop(context); // Close loading dialog

      final state = ref.read(reservationControllerProvider);

      if (state.hasError) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Hata: ${state.error}'),
            backgroundColor: Colors.red.shade400,
          ),
        );
      } else if (state.hasValue) {
        final result = state.value!;
        final paymentUrl = result['payment_url'] as String?;
        final message = result['message'] as String?;

        if (paymentUrl != null) {
          if (!mounted) return;
          final success = await Navigator.push<bool>(
            context,
            MaterialPageRoute(
              builder: (context) => PaymentWebViewScreen(
                url: paymentUrl,
                title: 'Ödeme Yap',
              ),
            ),
          );

          if (success == true) {
            if (!mounted) return;
            // Navigator.pop(context); // Not needed if pushed? Wait, we are in a modal.
            // Actually, we want to close the wizard itself.
            _showSuccessDialog('Ödeme Başarılı', 'Rezervasyonunuz onaylandı.');
          }
        } else {
          // Navigator.pop(context); // Already popped loading dialog
          _showSuccessDialog(
            'Rezervasyon Başarılı',
            message ?? 'Rezervasyonunuz oluşturuldu.',
          );
        }
      }
    } catch (e) {
      if (!mounted) return;
      Navigator.pop(context); // Close loading dialog if open?
      // Risk: if loading dialog was already passed?
      // Simplified error handling
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Beklenmeyen bir hata oluştu: $e')),
      );
    }
  }

  void _showSuccessDialog(String title, String content) {
    showDialog(
      context: context,
      builder: (context) => ReservationSuccessDialog(
        title: title,
        content: content,
        onConfirm: () => Navigator.pop(context),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.92,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(28)),
      ),
      child: Column(
        children: [
          ReservationWizardHeader(
            currentStep: _currentStep,
            onBack: _prevStep,
            business: widget.business,
          ),
          ReservationWizardStepper(currentStep: _currentStep),
          Expanded(child: _buildAnimatedStepContent()),
          ReservationWizardBottomBar(
            grandTotal: _grandTotal,
            currentStep: _currentStep,
            onPrevStep: _prevStep,
            onNextStep: _nextStep,
            onSubmit: _submitReservation,
          ),
        ],
      ),
    );
  }

  // --- Animated Step Content ---
  Widget _buildAnimatedStepContent() {
    return AnimatedSwitcher(
      duration: const Duration(milliseconds: 300),
      switchInCurve: Curves.easeOut,
      switchOutCurve: Curves.easeIn,
      transitionBuilder: (Widget child, Animation<double> animation) {
        final offsetAnimation = Tween<Offset>(
          begin: Offset(_isGoingForward ? 1.0 : -1.0, 0.0),
          end: Offset.zero,
        ).animate(animation);
        return FadeTransition(
          opacity: animation,
          child: SlideTransition(position: offsetAnimation, child: child),
        );
      },
      child: KeyedSubtree(
        key: ValueKey<int>(_currentStep),
        child: _buildStepContent(),
      ),
    );
  }

  Widget _buildStepContent() {
    switch (_currentStep) {
      case 0:
        return StepOneDetails(
          business: widget.business,
          selectedDate: _selectedDate,
          adults: _adults,
          children: _children,
          selectedTimeSlot: _selectedTimeSlot,
          onDateChanged: (date) => setState(() => _selectedDate = date),
          onGuestsChanged: (adults, children) => setState(() {
            _adults = adults;
            _children = children;
          }),
          onTimeSlotChanged: (time) => setState(() => _selectedTimeSlot = time),
        );
      case 1:
        return StepTwoServices(
          categories: widget.categories,
          selectedQuantities: _selectedMenuQuantities,
          onQuantityChanged: (menuId, quantity) {
            setState(() {
              if (quantity > 0) {
                _selectedMenuQuantities[menuId] = quantity;
              } else {
                _selectedMenuQuantities.remove(menuId);
              }
            });
          },
        );
      case 2:
        return StepThreePayment(
          business: widget.business,
          selectedDate: _selectedDate,
          selectedTime: _selectedTimeSlot!,
          guestCount: _guestCount,
          selectedMenuQuantities: _selectedMenuQuantities,
          categories: widget.categories,
          selectedPaymentMethod: _paymentMethod,
          onPaymentMethodChanged: (val) => setState(() => _paymentMethod = val),
          onSpecialRequestChanged: (val) =>
              setState(() => _specialRequest = val),
        );
      default:
        return const SizedBox();
    }
  }
}
