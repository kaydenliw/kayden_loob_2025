import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../core/constants.dart';
import '../core/api_result.dart';
import '../models/job_listing.dart';
import '../models/application.dart';

class ApiService {
  static final ApiService _instance = ApiService._internal();
  factory ApiService() => _instance;
  ApiService._internal();

  late final Dio _dio;
  String? _authToken;

  void initialize() {
    _dio = Dio(BaseOptions(
      baseUrl: AppConstants.baseUrl,
      connectTimeout: const Duration(seconds: 30),
      receiveTimeout: const Duration(seconds: 30),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    ));

    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          if (_authToken != null) {
            options.headers['Authorization'] = 'Bearer $_authToken';
          }
          handler.next(options);
        },
        onError: (error, handler) {
          // ignore: avoid_print
          print('API Error: ${error.message}');
          handler.next(error);
        },
      ),
    );
  }

  Future<void> setAuthToken(String? token) async {
    _authToken = token;
    final prefs = await SharedPreferences.getInstance();
    if (token != null) {
      await prefs.setString(AppConstants.authTokenKey, token);
    } else {
      await prefs.remove(AppConstants.authTokenKey);
    }
  }

  Future<void> loadAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    _authToken = prefs.getString(AppConstants.authTokenKey);
  }

  Future<ApiResult<Map<String, dynamic>>> login({
    required String email,
    required String password,
  }) async {
    try {
      final response = await _dio.post('${AppConstants.apiPrefix}/login', data: {
        'email': email,
        'password': password,
      });

      if (response.statusCode == 200) {
        return ApiSuccess(response.data);
      } else {
        return ApiError('Login failed', statusCode: response.statusCode);
      }
    } on DioException catch (e) {
      return ApiError(_handleDioError(e), originalError: e);
    } catch (e) {
      return ApiError('Unexpected error occurred', originalError: e);
    }
  }

  Future<ApiResult<Map<String, dynamic>>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String role = 'candidate',
  }) async {
    try {
      final response = await _dio.post('${AppConstants.apiPrefix}/register', data: {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
        'role': role,
      });

      if (response.statusCode == 201) {
        return ApiSuccess(response.data);
      } else {
        return ApiError('Registration failed', statusCode: response.statusCode);
      }
    } on DioException catch (e) {
      return ApiError(_handleDioError(e), originalError: e);
    } catch (e) {
      return ApiError('Unexpected error occurred', originalError: e);
    }
  }

  Future<ApiResult<void>> logout() async {
    try {
      await _dio.post('${AppConstants.apiPrefix}/logout');
      await setAuthToken(null);
      return const ApiSuccess(null);
    } on DioException catch (e) {
      return ApiError(_handleDioError(e), originalError: e);
    } catch (e) {
      return ApiError('Unexpected error occurred', originalError: e);
    }
  }

  Future<ApiResult<List<JobListing>>> getJobListings() async {
    try {
      final response = await _dio.get('${AppConstants.apiPrefix}/jobs');

      if (response.statusCode == 200) {
        final List<dynamic> data = response.data['data'] ?? response.data;
        final jobs = data.map((json) => JobListing.fromJson(json)).toList();
        return ApiSuccess(jobs);
      } else {
        return ApiError('Failed to fetch job listings', statusCode: response.statusCode);
      }
    } on DioException catch (e) {
      return ApiError(_handleDioError(e), originalError: e);
    } catch (e) {
      return ApiError('Unexpected error occurred', originalError: e);
    }
  }

  Future<ApiResult<JobListing>> getJobListing(int id) async {
    try {
      final response = await _dio.get('${AppConstants.apiPrefix}/jobs/$id');

      if (response.statusCode == 200) {
        final job = JobListing.fromJson(response.data['data'] ?? response.data);
        return ApiSuccess(job);
      } else {
        return ApiError('Failed to fetch job listing', statusCode: response.statusCode);
      }
    } on DioException catch (e) {
      return ApiError(_handleDioError(e), originalError: e);
    } catch (e) {
      return ApiError('Unexpected error occurred', originalError: e);
    }
  }

  Future<ApiResult<Map<String, dynamic>>> submitApplication({
    required String fullName,
    required String phone,
    String? email,
    required String position,
    required String workExperience,
    int? jobListingId,
  }) async {
    try {
      final response = await _dio.post('${AppConstants.apiPrefix}/applications', data: {
        'full_name': fullName,
        'phone': phone,
        'email': email,
        'position': position,
        'work_experience': workExperience,
        'job_listing_id': jobListingId,
      });

      if (response.statusCode == 201) {
        return ApiSuccess(response.data);
      } else {
        return ApiError('Failed to submit application', statusCode: response.statusCode);
      }
    } on DioException catch (e) {
      return ApiError(_handleDioError(e), originalError: e);
    } catch (e) {
      return ApiError('Unexpected error occurred', originalError: e);
    }
  }

  Future<ApiResult<List<Application>>> getApplications() async {
    try {
      final response = await _dio.get('${AppConstants.apiPrefix}/applications');

      if (response.statusCode == 200) {
        final List<dynamic> data = response.data['data'] ?? response.data;
        final applications = data.map((json) => Application.fromJson(json)).toList();
        return ApiSuccess(applications);
      } else {
        return ApiError('Failed to fetch applications', statusCode: response.statusCode);
      }
    } on DioException catch (e) {
      return ApiError(_handleDioError(e), originalError: e);
    } catch (e) {
      return ApiError('Unexpected error occurred', originalError: e);
    }
  }

  Future<ApiResult<Map<String, dynamic>>> getApplicationStatus({
    String? phone,
    String? email,
  }) async {
    try {
      final Map<String, dynamic> queryParams = {};
      if (phone != null) queryParams['phone'] = phone;
      if (email != null) queryParams['email'] = email;

      final response = await _dio.get('${AppConstants.apiPrefix}/applications/status', queryParameters: queryParams);

      if (response.statusCode == 200) {
        return ApiSuccess(response.data);
      } else {
        return ApiError('Failed to fetch application status', statusCode: response.statusCode);
      }
    } on DioException catch (e) {
      return ApiError(_handleDioError(e), originalError: e);
    } catch (e) {
      return ApiError('Unexpected error occurred', originalError: e);
    }
  }

  Future<Map<String, dynamic>> checkApplicationStatus(Map<String, dynamic> data) async {
    try {
      final response = await _dio.get('${AppConstants.apiPrefix}/applications/status', queryParameters: data);
      return response.data;
    } on DioException catch (e) {
      if (e.response?.statusCode == 404) {
        return {
          'success': false,
          'message': 'No application found for the provided contact information.'
        };
      }
      throw Exception(_handleDioError(e));
    }
  }

  String _handleDioError(DioException e) {
    switch (e.type) {
      case DioExceptionType.connectionTimeout:
      case DioExceptionType.sendTimeout:
      case DioExceptionType.receiveTimeout:
        return 'Connection timeout. Please check your internet connection.';
      case DioExceptionType.badResponse:
        final statusCode = e.response?.statusCode;
        final data = e.response?.data;
        if (data is Map && data.containsKey('message')) {
          return data['message'];
        }
        return 'Server error ($statusCode). Please try again.';
      case DioExceptionType.cancel:
        return 'Request was cancelled';
      case DioExceptionType.connectionError:
        return 'No internet connection. Please check your network.';
      default:
        return 'Network error occurred. Please try again.';
    }
  }
}

// Provider for ApiService
final apiServiceProvider = Provider<ApiService>((ref) => ApiService());
