import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/application.dart';
import '../services/api_service.dart';
import '../core/api_result.dart';

// Application State
class ApplicationState {
  final List<Application> applications;
  final bool isLoading;
  final String? error;
  final bool isSubmitting;
  final String? submitError;
  final bool submitSuccess;

  const ApplicationState({
    this.applications = const [],
    this.isLoading = false,
    this.error,
    this.isSubmitting = false,
    this.submitError,
    this.submitSuccess = false,
  });

  ApplicationState copyWith({
    List<Application>? applications,
    bool? isLoading,
    String? error,
    bool? isSubmitting,
    String? submitError,
    bool? submitSuccess,
  }) {
    return ApplicationState(
      applications: applications ?? this.applications,
      isLoading: isLoading ?? this.isLoading,
      error: error,
      isSubmitting: isSubmitting ?? this.isSubmitting,
      submitError: submitError,
      submitSuccess: submitSuccess ?? this.submitSuccess,
    );
  }

  List<Application> get pendingApplications {
    return applications.where((app) => app.isPending).toList();
  }

  List<Application> get reviewedApplications {
    return applications.where((app) => app.isReviewed).toList();
  }

  List<Application> get interviewApplications {
    return applications.where((app) => app.isInterview).toList();
  }

  List<Application> get acceptedApplications {
    return applications.where((app) => app.isAccepted).toList();
  }

  List<Application> get rejectedApplications {
    return applications.where((app) => app.isRejected).toList();
  }
}

// Application Notifier
class ApplicationNotifier extends StateNotifier<ApplicationState> {
  ApplicationNotifier() : super(const ApplicationState());

  final ApiService _apiService = ApiService();

  Future<bool> submitApplication({
    required String fullName,
    required String phone,
    String? email,
    required String position,
    required String workExperience,
    int? jobListingId,
  }) async {
    state = state.copyWith(
      isSubmitting: true,
      submitError: null,
      submitSuccess: false,
    );

    final result = await _apiService.submitApplication(
      fullName: fullName,
      phone: phone,
      email: email,
      position: position,
      workExperience: workExperience,
      jobListingId: jobListingId,
    );

    return result.when(
      success: (response) {
        // Application submitted successfully
        state = state.copyWith(
          isSubmitting: false,
          submitSuccess: true,
        );
        return true;
      },
      error: (error) {
        state = state.copyWith(
          isSubmitting: false,
          submitError: error,
        );
        return false;
      },
      loading: () => false,
    );
  }

  Future<void> loadApplications() async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _apiService.getApplications();

    result.when(
      success: (applications) {
        state = state.copyWith(
          applications: applications,
          isLoading: false,
        );
      },
      error: (error) {
        state = state.copyWith(
          isLoading: false,
          error: error,
        );
      },
      loading: () {},
    );
  }

  Future<void> checkApplicationStatus({String? email, String? phone}) async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _apiService.getApplicationStatus(
      email: email,
      phone: phone,
    );

    result.when(
      success: (response) {
        // Parse applications from response
        final List<dynamic> data = response['data'] ?? response['applications'] ?? [];
        final applications = data.map((json) => Application.fromJson(json)).toList();
        state = state.copyWith(
          applications: applications,
          isLoading: false,
        );
      },
      error: (error) {
        state = state.copyWith(
          isLoading: false,
          error: error,
        );
      },
      loading: () {},
    );
  }

  void clearSubmitState() {
    state = state.copyWith(
      submitError: null,
      submitSuccess: false,
    );
  }

  void clearError() {
    state = state.copyWith(error: null);
  }

  void clearApplications() {
    state = const ApplicationState();
  }
}

// Provider
final applicationProvider = StateNotifierProvider<ApplicationNotifier, ApplicationState>((ref) {
  return ApplicationNotifier();
});
