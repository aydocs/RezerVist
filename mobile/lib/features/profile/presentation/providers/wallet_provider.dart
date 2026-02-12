import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/repositories/wallet_repository.dart';
import '../../domain/models/wallet_transaction.dart';

final walletRepositoryProvider = Provider((ref) => WalletRepository());

final walletTransactionsProvider = FutureProvider<List<WalletTransaction>>((ref) async {
  final repository = ref.watch(walletRepositoryProvider);
  return repository.getTransactions();
});
