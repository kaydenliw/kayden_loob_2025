import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/job_listing.dart';
import '../services/api_service.dart';
import '../core/api_result.dart';

// Job State
class JobState {
  final List<JobListing> jobs;
  final JobListing? selectedJob;
  final bool isLoading;
  final String? error;
  final String searchQuery;
  final String? selectedType;
  final String? selectedLocation;

  const JobState({
    this.jobs = const [],
    this.selectedJob,
    this.isLoading = false,
    this.error,
    this.searchQuery = '',
    this.selectedType,
    this.selectedLocation,
  });

  JobState copyWith({
    List<JobListing>? jobs,
    JobListing? selectedJob,
    bool? isLoading,
    String? error,
    String? searchQuery,
    String? selectedType,
    String? selectedLocation,
  }) {
    return JobState(
      jobs: jobs ?? this.jobs,
      selectedJob: selectedJob ?? this.selectedJob,
      isLoading: isLoading ?? this.isLoading,
      error: error,
      searchQuery: searchQuery ?? this.searchQuery,
      selectedType: selectedType ?? this.selectedType,
      selectedLocation: selectedLocation ?? this.selectedLocation,
    );
  }

  List<JobListing> get filteredJobs {
    var filtered = jobs.where((job) {
      // Search filter
      if (searchQuery.isNotEmpty) {
        final query = searchQuery.toLowerCase();
        if (!job.title.toLowerCase().contains(query) &&
            !job.location.toLowerCase().contains(query) &&
            !job.description.toLowerCase().contains(query)) {
          return false;
        }
      }

      // Type filter - disabled since we don't have type data
      // if (selectedType != null && selectedType!.isNotEmpty) {
      //   if (job.type != selectedType) {
      //     return false;
      //   }
      // }

      // Location filter
      if (selectedLocation != null && selectedLocation!.isNotEmpty) {
        if (!job.location.toLowerCase().contains(selectedLocation!.toLowerCase())) {
          return false;
        }
      }

      return true;
    }).toList();

    // Sort by creation date (newest first)
    filtered.sort((a, b) => b.createdAt.compareTo(a.createdAt));
    return filtered;
  }

  Set<String> get availableTypes {
    // Return empty set since we don't have type data
    return <String>{};
  }

  Set<String> get availableLocations {
    return jobs.map((job) => job.location).toSet();
  }
}

// Job Notifier
class JobNotifier extends StateNotifier<JobState> {
  JobNotifier() : super(const JobState());

  final ApiService _apiService = ApiService();

  Future<void> loadJobs() async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _apiService.getJobListings();

    result.when(
      success: (jobs) {
        state = state.copyWith(
          jobs: jobs,
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

  Future<void> loadJobDetails(int jobId) async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _apiService.getJobListing(jobId);

    result.when(
      success: (job) {
        state = state.copyWith(
          selectedJob: job,
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

  void setSearchQuery(String query) {
    state = state.copyWith(searchQuery: query);
  }

  void setTypeFilter(String? type) {
    state = state.copyWith(selectedType: type);
  }

  void setLocationFilter(String? location) {
    state = state.copyWith(selectedLocation: location);
  }

  void clearFilters() {
    state = state.copyWith(
      searchQuery: '',
      selectedType: null,
      selectedLocation: null,
    );
  }

  void clearSelectedJob() {
    state = state.copyWith(selectedJob: null);
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}

// Provider
final jobProvider = StateNotifierProvider<JobNotifier, JobState>((ref) {
  return JobNotifier();
});
