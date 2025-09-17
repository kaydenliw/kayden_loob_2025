import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import '../models/user.dart';
import '../services/api_service.dart';
import '../core/api_result.dart';
import '../core/constants.dart';

// Auth State
class AuthState {
  final User? user;
  final bool isLoading;
  final String? error;
  final bool isAuthenticated;

  const AuthState({
    this.user,
    this.isLoading = false,
    this.error,
    this.isAuthenticated = false,
  });

  AuthState copyWith({
    User? user,
    bool? isLoading,
    String? error,
    bool? isAuthenticated,
  }) {
    return AuthState(
      user: user ?? this.user,
      isLoading: isLoading ?? this.isLoading,
      error: error,
      isAuthenticated: isAuthenticated ?? this.isAuthenticated,
    );
  }
}

// Auth Notifier
class AuthNotifier extends StateNotifier<AuthState> {
  AuthNotifier() : super(const AuthState()) {
    _loadStoredAuth();
  }

  final ApiService _apiService = ApiService();

  Future<void> _loadStoredAuth() async {
    try {
      await _apiService.loadAuthToken();
      final prefs = await SharedPreferences.getInstance();
      final userJson = prefs.getString(AppConstants.userKey);
      
      if (userJson != null) {
        final user = User.fromJson(json.decode(userJson));
        state = state.copyWith(
          user: user,
          isAuthenticated: true,
        );
      }
    } catch (e) {
      // ignore: avoid_print
      print('Failed to load stored auth: $e');
    }
  }

  Future<bool> login({
    required String email,
    required String password,
  }) async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _apiService.login(email: email, password: password);

    return result.when(
      success: (data) async {
        try {
          // Handle Laravel API response format: {success: true, data: {user: {...}, token: "..."}}
          final responseData = data['data'] ?? data;
          final token = responseData['token'] ?? responseData['access_token'];
          final userData = responseData['user'] ?? responseData;
          
          // Debug logging
          print('Login response data: $data');
          print('Response data: $responseData');
          print('User data: $userData');
          print('Token: $token');
          
          if (token != null) {
            await _apiService.setAuthToken(token);
          }
          
          final user = User.fromJson(userData);
          await _saveUserData(user);
          
          state = state.copyWith(
            user: user,
            isAuthenticated: true,
            isLoading: false,
          );
          
          return true;
        } catch (e, stackTrace) {
          // ignore: avoid_print
          print('Login response processing error: $e');
          print('Stack trace: $stackTrace');
          state = state.copyWith(
            isLoading: false,
            error: 'Failed to process login response: ${e.toString()}',
          );
          return false;
        }
      },
      error: (error) {
        state = state.copyWith(
          isLoading: false,
          error: error,
        );
        return false;
      },
      loading: () => false,
    );
  }

  Future<bool> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String role = 'candidate',
  }) async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _apiService.register(
      name: name,
      email: email,
      password: password,
      passwordConfirmation: passwordConfirmation,
      role: role,
    );

    return result.when(
      success: (data) async {
        try {
          // Handle Laravel API response format: {success: true, data: {user: {...}, token: "..."}}
          final responseData = data['data'] ?? data;
          final token = responseData['token'] ?? responseData['access_token'];
          final userData = responseData['user'] ?? responseData;
          
          if (token != null) {
            await _apiService.setAuthToken(token);
          }
          
          final user = User.fromJson(userData);
          await _saveUserData(user);
          
          state = state.copyWith(
            user: user,
            isAuthenticated: true,
            isLoading: false,
          );
          
          return true;
        } catch (e) {
          // ignore: avoid_print
          print('Registration response processing error: $e');
          state = state.copyWith(
            isLoading: false,
            error: 'Failed to process registration response: ${e.toString()}',
          );
          return false;
        }
      },
      error: (error) {
        state = state.copyWith(
          isLoading: false,
          error: error,
        );
        return false;
      },
      loading: () => false,
    );
  }

  Future<void> logout() async {
    state = state.copyWith(isLoading: true);

    await _apiService.logout();
    await _clearUserData();

    state = const AuthState();
  }

  Future<void> _saveUserData(User user) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(AppConstants.userKey, json.encode(user.toJson()));
  }

  Future<void> _clearUserData() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(AppConstants.userKey);
    await prefs.remove(AppConstants.authTokenKey);
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}

// Provider
final authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) {
  return AuthNotifier();
});
