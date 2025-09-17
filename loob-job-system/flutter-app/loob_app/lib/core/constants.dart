class AppConstants {
  // API Configuration
  static const String baseUrl = 'http://127.0.0.1:8000';
  static const String apiPrefix = '/api';
  
  // Storage Keys
  static const String authTokenKey = 'auth_token';
  static const String userKey = 'user_data';
  
  // App Info
  static const String appName = 'Loob Jobs';
  static const String appVersion = '1.0.0';
  
  // UI Constants
  static const double defaultPadding = 16.0;
  static const double smallPadding = 8.0;
  static const double largePadding = 24.0;
  
  // Animation Durations
  static const Duration shortAnimation = Duration(milliseconds: 200);
  static const Duration mediumAnimation = Duration(milliseconds: 400);
  static const Duration longAnimation = Duration(milliseconds: 600);
  
  // Application Status Values (aligned with backend)
  static const String statusApplied = 'applied';
  static const String statusScreening = 'screening';
  static const String statusInterview = 'interview';
  static const String statusOffer = 'offer';
  static const String statusRejected = 'rejected';
  
  // Job Types
  static const List<String> jobTypes = [
    'Full-time',
    'Part-time',
    'Contract',
    'Internship',
    'Remote',
  ];
}
