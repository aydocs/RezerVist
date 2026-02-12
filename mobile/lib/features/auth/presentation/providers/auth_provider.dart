import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/repositories/auth_repository.dart';
import '../../domain/models/user.dart';

final authRepositoryProvider = Provider((ref) => AuthRepository());

final authProvider =
    StateNotifierProvider<AuthNotifier, AsyncValue<User?>>((ref) {
  return AuthNotifier(ref.watch(authRepositoryProvider));
});

class AuthNotifier extends StateNotifier<AsyncValue<User?>> {
  final AuthRepository _repository;

  AuthNotifier(this._repository) : super(const AsyncLoading()) {
    _restoreSession();
  }

  Future<void> _restoreSession() async {
    try {
      final token = await _repository.getToken();
      if (token != null) {
        final user = await _repository.getMe();
        state = AsyncData(user);
      } else {
        state = const AsyncData(null);
      }
    } catch (e) {
      state = const AsyncData(null);
    }
  }

  Future<void> login(String email, String password) async {
    state = const AsyncLoading();
    try {
      final user = await _repository.login(email, password);
      state = AsyncData(user);
    } catch (e, st) {
      state = AsyncError(e, st);
    }
  }

  Future<void> register({
    required String name,
    required String email,
    required String password,
    String? referralCode,
  }) async {
    state = const AsyncLoading();
    try {
      final user = await _repository.register(
        name: name,
        email: email,
        password: password,
        referralCode: referralCode,
      );
      state = AsyncData(user);
    } catch (e, st) {
      state = AsyncError(e, st);
    }
  }

  Future<void> loginWithGoogle() async {
    state = const AsyncLoading();
    try {
      final user = await _repository.signInWithGoogle();
      state = AsyncData(user);
    } catch (e, st) {
      state = AsyncError(e, st);
    }
  }

  Future<void> loginWithApple() async {
    state = const AsyncLoading();
    try {
      final user = await _repository.signInWithApple();
      state = AsyncData(user);
    } catch (e, st) {
      state = AsyncError(e, st);
    }
  }

  Future<void> logout() async {
    await _repository.clearToken();
    state = const AsyncData(null);
  }

  Future<void> updateProfilePhoto(String filePath) async {
    // Keep current state but show loading if needed, or just update in bg
    // For now, let's keep previous state and just update after success
    final currentUser = state.value;
    if (currentUser == null) return;

    try {
      final updatedUser = await _repository.updateProfilePhoto(filePath);
      state = AsyncData(updatedUser);
    } catch (e) {
      // Setup error state or show snackbar via UI listener
      // state = AsyncError(e, StackTrace.current); // This might replace the user with error, be careful
      // Better to throw and let UI handle, or have a separate "profileUpdateState"
      rethrow;
    }
  }

  Future<void> updateSettings(Map<String, dynamic> settings) async {
    final currentUser = state.value;
    if (currentUser == null) return;

    try {
      await _repository.updateSettings(settings);
      final updatedUser = await _repository.getMe();
      state = AsyncData(updatedUser);
    } catch (e) {
      rethrow;
    }
  }
}
