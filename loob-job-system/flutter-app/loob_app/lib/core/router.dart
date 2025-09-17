import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../screens/splash_screen.dart';
import '../screens/auth/login_screen.dart';
import '../screens/auth/register_screen.dart';
import '../screens/home/home_screen.dart';
import '../screens/jobs/job_list_screen.dart';
import '../screens/jobs/job_detail_screen.dart';
import '../screens/applications/applications_screen.dart';
import '../screens/applications/application_form_screen.dart';
import '../screens/applications/application_status_screen.dart';
import '../screens/applications/application_status_check_screen.dart';
import '../screens/profile/profile_screen.dart';
import '../providers/auth_provider.dart';

final routerProvider = Provider<GoRouter>((ref) {
  final authState = ref.watch(authProvider);
  
  return GoRouter(
    initialLocation: '/splash',
    refreshListenable: ValueNotifier(authState),
    redirect: (context, state) {
      final isAuthenticated = authState.isAuthenticated;
      final isOnAuthPage = state.matchedLocation.startsWith('/auth');
      final isOnSplash = state.matchedLocation == '/splash';
      
      // Allow splash screen
      if (isOnSplash) return null;
      
      // Redirect to login if not authenticated and not on auth page
      if (!isAuthenticated && !isOnAuthPage) {
        return '/auth/login';
      }
      
      // Redirect to home if authenticated and on auth page
      if (isAuthenticated && isOnAuthPage) {
        return '/home';
      }
      
      return null;
    },
    routes: [
      GoRoute(
        path: '/splash',
        name: 'splash',
        builder: (context, state) => const SplashScreen(),
      ),
      GoRoute(
        path: '/auth/login',
        name: 'login',
        builder: (context, state) => const LoginScreen(),
      ),
      GoRoute(
        path: '/auth/register',
        name: 'register',
        builder: (context, state) => const RegisterScreen(),
      ),
      GoRoute(
        path: '/home',
        name: 'home',
        builder: (context, state) => const HomeScreen(),
      ),
      GoRoute(
        path: '/jobs',
        name: 'jobs',
        builder: (context, state) => const JobListScreen(),
        routes: [
          GoRoute(
            path: ':id',
            name: 'job-detail',
            builder: (context, state) {
              final id = int.parse(state.pathParameters['id']!);
              return JobDetailScreen(jobId: id);
            },
          ),
        ],
      ),
      GoRoute(
        path: '/applications',
        name: 'applications',
        builder: (context, state) => const ApplicationStatusCheckScreen(),
        routes: [
          GoRoute(
            path: 'list',
            name: 'applications-list',
            builder: (context, state) => const ApplicationsScreen(),
          ),
          GoRoute(
            path: 'apply/:jobId',
            name: 'application-form',
            builder: (context, state) {
              final jobId = int.parse(state.pathParameters['jobId']!);
              return ApplicationFormScreen(jobId: jobId);
            },
          ),
          GoRoute(
            path: 'status/:applicationId',
            name: 'application-status',
            builder: (context, state) {
              final applicationId = int.parse(state.pathParameters['applicationId']!);
              return ApplicationStatusScreen(applicationId: applicationId);
            },
          ),
        ],
      ),
      GoRoute(
        path: '/profile',
        name: 'profile',
        builder: (context, state) => const ProfileScreen(),
      ),
    ],
    errorBuilder: (context, state) => Scaffold(
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.error_outline, size: 64, color: Colors.red),
            const SizedBox(height: 16),
            Text(
              'Page not found',
              style: Theme.of(context).textTheme.headlineSmall,
            ),
            const SizedBox(height: 8),
            Text(state.error.toString()),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => context.go('/home'),
              child: const Text('Go Home'),
            ),
          ],
        ),
      ),
    ),
  );
});
