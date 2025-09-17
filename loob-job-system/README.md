# Loob Job System

A comprehensive job recruitment platform I built using Flutter and Laravel, designed specifically for the Loob Holdings. This is a complete mobile-first recruitment system that handles everything from job listings to application tracking and recruiter management.

## Build Performance Note

**Initial iOS Build Time:** ~51 minutes (3069 seconds) on iPhone 16 simulator  
**Platform:** macOS Sonoma with Apple Silicon  

## Quick Start Guide
### Backend Setup (Laravel API)
   ```bash
   cd laravel-backend/loob_recruitment
   composer install
   cp .env.example .env
   php artisan key:generate
touch database/database.sqlite
   php artisan migrate --seed
php artisan serve --port=8000
   ```

The Laravel server will run on `http://localhost:8000`. The port specification is important for the Flutter app to connect properly.

### Mobile App Setup (Flutter)
   ```bash
   cd flutter-app/loob_app
   flutter pub get
   flutter run
   ```

The app automatically connects to `http://localhost:8000` for API calls.

### Test Data Available

The seeders create realistic Malaysian job market data:

**Pre-created Accounts:**
- **Recruiter Login**: `recruiter@example.com` / `password`
- **Test Users**: 5 additional candidate accounts created via factory

**Sample Jobs:** 6 authentic Loob Holding F&B positions:
- Barista - Tealive (Kuala Lumpur)
- Area Manager - F&B Operations (Kota Damansara)
- Digital Marketing Executive (Kota Damansara)  
- Store Manager - Tealive (Various Locations)
- Supply Chain Coordinator (Kota Damansara)
- Brand Development Manager (Kota Damansara)

**Sample Applications:** 25 realistic applications with Malaysian names, +60 phone numbers, and localized work experience descriptions in both English and Bahasa Malaysia.

## What I Built

This project represents a full-stack recruitment solution with three main components:

- **Mobile App (Flutter)** - Job seekers can browse opportunities and submit applications
- **API Backend (Laravel)** - Robust REST API handling all business logic and data management  
- **Web Dashboard (Laravel Blade)** - Recruiters can manage applications and track candidates

The system is currently configured for Loob Holding, Malaysia's leading F&B company with brands like Tealive, but the architecture is flexible enough to adapt to any industry.

## Mobile App Architecture Deep Dive

I designed this Flutter app following clean architecture principles with clear separation of concerns:

### Screen Structure & User Journey

**Authentication Flow:**
- `SplashScreen` - Animated app initialization with automatic auth state detection
- `LoginScreen` - Clean Material Design 3 login with comprehensive validation
- `RegisterScreen` - Standard registration form with name, email, password validation and success dialog

**Main Application Flow:**
- `HomeScreen` - Dashboard with personalized welcome, quick actions, and recent job previews
- `JobListScreen` - Scrollable job list with "Apply Now" buttons and clean Material Design cards
- `JobDetailScreen` - Detailed job view with rich formatting and direct application access
- `ApplicationFormScreen` - Single-page application form with comprehensive validation and modern UI design
- `ApplicationsScreen` - Complete application history with status tracking and filtering
- `ApplicationStatusCheckScreen` - Anonymous status lookup with phone/email toggle selection
- `ProfileScreen` - Basic user profile display with logout functionality (other features show "Coming Soon")

### Form Validation Implementation

I implemented comprehensive validation throughout the mobile app:

**Authentication Forms:**
- **LoginScreen**: Email format validation with regex + password minimum length
- **RegisterScreen**: Name length, email format, password strength, and password confirmation matching

**Application Form (Most Comprehensive):**
- **Full Name**: Required field with minimum 2 characters
- **Phone Number**: Required with 10+ digit validation and character format checking
- **Email**: Optional but when provided, comprehensive regex validation with format checks
- **Work Experience**: Required field for job-relevant information

**Application Status Check:**
- **Dynamic validation** based on selected method (phone vs email)
- **Real-time switching** between validation rules

**Validation Features:**
- Real-time error display with styled error containers
- Form submission prevention until validation passes
- User-friendly error messages with clear instructions
- Consistent validation styling across all forms

### State Management Strategy

I chose **Riverpod** over other state management solutions for several key reasons:

**Why Riverpod?**
- **Compile-time safety** - Catches errors before runtime
- **Fine-grained reactivity** - Only rebuilds what actually changed
- **Easy testing** - Provider overrides make unit testing straightforward
- **Better performance** - More efficient than Provider or setState
- **Great for API state** - Perfect for handling async operations

### API Service Design

I implemented a singleton API service with automatic token management:

```dart
class ApiService {
  // Singleton pattern for consistent API access
  static final ApiService _instance = ApiService._internal();
  
  // Dio HTTP client with interceptors
  late final Dio _dio;
  String? _authToken;
  
  // Automatic token injection for authenticated requests
  void initialize() {
    _dio = Dio(BaseOptions(baseUrl: AppConstants.baseUrl));
    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        if (_authToken != null) {
          options.headers['Authorization'] = 'Bearer $_authToken';
        }
        handler.next(options);
      },
    ));
  }
}
```

**Key Features:**
- **Automatic token management** - Tokens are stored securely and injected automatically
- **Comprehensive error handling** - Network errors, timeouts, and API errors are handled gracefully
- **Type-safe responses** - All API responses are wrapped in `ApiResult<T>` for consistent handling
- **Persistent authentication** - User sessions survive app restarts

## Laravel Backend Architecture

The backend is built as a robust REST API with additional web interface for recruiters:

### API Controller Design

**AuthController** - Laravel Sanctum token-based authentication:
```php
public function login(Request $request)
{
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
    }
    
    $user = Auth::user();
    $token = $user->createToken('API Token')->plainTextToken;
    
    return response()->json([
        'success' => true,
        'data' => ['user' => $user, 'token' => $token]
    ]);
}
```

**ApplicationController** - Handles job applications with email notifications:
```php
public function store(StoreApplicationRequest $request): JsonResponse
{
    $application = Application::create($request->validated());
    
    // Send confirmation email if email provided
    if ($application->email) {
        Mail::to($application->email)->send(new ApplicationConfirmation($application));
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Application submitted successfully',
        'application_id' => $application->id
    ], 201);
}
```

**JobListingController** - Simple CRUD operations with mock job board integration:
```php
public function postToExternalBoards(Request $request): JsonResponse
{
    // Mock implementation - ready for real job board integrations
    return response()->json([
        'success' => true,
        'message' => 'Job posted to external boards (mock)',
        'boards' => ['indeed', 'linkedin', 'glassdoor']
    ]);
}
```

### Database Design

I designed a clean, normalized database structure:

```sql
-- Users table with role-based access
users (id, name, email, password, role, timestamps)

-- Job listings with basic information
job_listings (id, title, location, description, timestamps)

-- Applications with comprehensive candidate data
applications (
  id, 
  job_listing_id, 
  full_name, 
  phone, 
  email (nullable),
  position, 
  work_experience, 
  status ENUM('applied', 'screening', 'interview', 'offer', 'rejected'),
  timestamps
)

-- Laravel Sanctum tokens for API authentication
personal_access_tokens (id, tokenable_id, name, token, abilities, timestamps)
```

**Key Design Decisions:**
- **Flexible email field** - Applications can be submitted without email (phone-only)
- **Status enum** - Enforced application workflow states
- **Relationship integrity** - Foreign key constraints maintain data consistency
- **Timestamp tracking** - Full audit trail for all records

**Key Laravel Features Used:**
- **Sanctum Authentication**: Token-based API auth for mobile
- **Eloquent Relationships**: HasMany/BelongsTo for data integrity
- **Form Requests**: Centralized validation in StoreApplicationRequest
- **Mail System**: Queue-able email notifications
- **Seeders**: Test data with Malaysian context
- **Feature Tests**: Full HTTP request/response testing
- **Laravel UI**: Basic web authentication for recruiter dashboard

**Authentication Flow:**
I implemented Laravel Sanctum because it's perfect for mobile apps:
1. User logs in via `/api/login`
2. Laravel creates a personal access token
3. Flutter stores token securely
4. All API requests include `Bearer {token}` header
5. Laravel validates token on protected routes

**Database Design:**
```sql
-- Core tables with relationships
users (id, name, email, role, timestamps)
job_listings (id, title, location, description, timestamps)
applications (id, job_listing_id, name, email, phone, experience, status, timestamps)
personal_access_tokens (Laravel Sanctum tokens)
```

## Testing - How It Works

### Backend Tests (Laravel)

**Run tests:**
```bash
cd laravel-backend/loob_recruitment
php artisan test
```

**What gets tested:**
- **Feature Tests** (`tests/Feature/`): Full API endpoints
- **Unit Tests** (`tests/Unit/`): Individual model methods

**Example test output:**
```
PASS  Tests\Feature\ApplicationApiTest
✓ can submit application
✓ validates required fields  
✓ sends confirmation email

PASS  Tests\Unit\Models\UserTest
✓ user can be recruiter
✓ user has job listings relationship
```

**Check test coverage:**
```bash
php artisan test --coverage
```

**Logs location:**
- Test logs: `storage/logs/laravel.log`
- Email testing: `storage/logs/mail.log`

### Frontend Tests (Flutter)

**Run tests:**
```bash
cd flutter-app/loob_app
flutter test
```

**Test types:**
- **Unit tests**: Models and services (`test/models/`, `test/services/`)
- **Widget tests**: UI components (`test/widgets/`)

**Example test output:**
```
✓ User model serializes correctly
✓ API service handles auth tokens
✓ Application form validates input
✓ Job listing widget displays data
```

**View test details:**
```bash
flutter test --reporter expanded
```

## Complete API Documentation

### Authentication Endpoints
```php
// POST /api/register
{
    "name": "John Doe",
    "email": "john@example.com", 
    "password": "password",
    "password_confirmation": "password",
    "role": "candidate" // or "recruiter"
}

// POST /api/login  
{
    "email": "recruiter@example.com",
    "password": "password"
}

// Response includes Sanctum token
{
    "success": true,
    "user": {...},
    "token": "1|abcdef123456..."
}
```

### Job Listings API
```php
// GET /api/jobs - List all jobs
// GET /api/jobs/1 - Get specific job
// Response format:
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Barista - Tealive",
        "location": "Kuala Lumpur, Malaysia", 
        "description": "Join Loob Holding's flagship brand...",
        "created_at": "2024-01-15T10:30:00Z"
    }
}
```

### Applications API
```php
// POST /api/applications - Submit application
{
    "job_listing_id": 1,
    "full_name": "Ahmad bin Abdullah",
    "email": "ahmad@example.com",
    "phone": "+60123456789",
    "work_experience": "5 years experience in F&B..."
}

// GET /api/applications/status?email=ahmad@example.com
// Returns all applications for that email
```

## Mock Job Board Posting API

**Endpoint:** `POST /api/jobs/post`

**Implementation in JobListingController.php:**
```php
public function postToExternalBoards(Request $request): JsonResponse
{
    // Mock implementation - in real scenario this would integrate with job boards
    return response()->json([
        'success' => true,
        'message' => 'Job posted to external boards (mock)',
        'boards' => ['indeed', 'linkedin', 'glassdoor'],
        'job_id' => $request->input('job_id', 1),
        'posted_at' => now()
    ]);
}
```

**Test it:**
```bash
curl -X POST http://localhost:8000/api/jobs/post \
  -H "Content-Type: application/json" \
  -d '{"job_id": 1}'
```

## Firebase Build Setup

For production app builds and releases, here's how I'd configure Firebase:

### 1. Firebase Project Setup
```bash
# Install Firebase CLI
npm install -g firebase-tools

# Login and initialize
firebase login
firebase init
```

### 2. Android Configuration
```bash
# Add Firebase to Android
flutter packages get
firebase apps:create android com.loob.recruitment
```

Add to `android/app/build.gradle.kts`:
```kotlin
plugins {
    id("com.google.gms.google-services")
}

dependencies {
    implementation("com.google.firebase:firebase-analytics")
    implementation("com.google.firebase:firebase-crashlytics")
}
```

### 3. iOS Configuration  
```bash
# Add Firebase to iOS
firebase apps:create ios com.loob.recruitment
```

Download `GoogleService-Info.plist` to `ios/Runner/`

### 4. Build & Release Pipeline
```yaml
# .github/workflows/build.yml
name: Build and Release
on:
  push:
    tags: ['v*']

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: subosito/flutter-action@v2
      - run: flutter pub get
      - run: flutter test
      - run: flutter build apk --release
      - run: firebase appdistribution:distribute build/app/outputs/apk/release/app-release.apk
```

**Firebase features I'd use:**
- **App Distribution**: Beta testing with testers
- **Crashlytics**: Crash reporting and analytics  
- **Analytics**: User behavior tracking
- **Remote Config**: Feature flags and A/B testing
- **Cloud Messaging**: Push notifications for application updates

## Email System Setup

**For testing emails locally:**

1. **Use Mailtrap:**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   ```

2. **Test email sending:**
   ```bash
   php artisan test:email test@example.com
   ```

3. **Check email logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

**Email templates location:** `resources/views/emails/`

## Detailed Setup Instructions

### Prerequisites Installation

**macOS:**
```bash
# Install Homebrew
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install PHP and Composer
brew install php@8.2 composer

# Install MySQL (optional, can use SQLite)
brew install mysql
brew services start mysql

# Install Flutter
brew install --cask flutter
flutter doctor
```

**Windows:**
```bash
# Install PHP from php.net
# Install Composer from getcomposer.org
# Install Flutter from flutter.dev
# Install MySQL or use SQLite
```

### Database Setup Options

**Option 1: SQLite (Easiest)**
```bash
# In .env file
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# Create database file
touch database/database.sqlite
php artisan migrate --seed
```

**Option 2: MySQL**
```bash
# Create database
mysql -u root -p
CREATE DATABASE loob_recruitment;

# In .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loob_recruitment
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Flutter Development Setup

**API Configuration:**
```dart
// lib/core/constants.dart
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
    'Full-time', 'Part-time', 'Contract', 'Internship', 'Remote',
  ];
}

// For different environments:
// Android emulator: http://10.0.2.2:8000
// iOS simulator: http://127.0.0.1:8000 or http://localhost:8000
// Physical device: http://YOUR_COMPUTER_IP:8000
```

**State Management Setup:**
```dart
// main.dart
void main() {
  runApp(
    ProviderScope( // Riverpod provider scope
      child: MyApp(),
    ),
  );
}
```

## Libraries & Dependencies Explained

### Flutter Dependencies (pubspec.yaml)
```yaml
dependencies:
  # UI & Design
  cupertino_icons: ^1.0.8       # iOS-style icons
  google_fonts: ^6.1.0          # Custom fonts from Google
  flutter_animate: ^4.2.0       # Smooth animations
  
  # State Management
  flutter_riverpod: ^2.4.9      # Reactive state management
  
  # HTTP & API
  http: ^1.1.2                  # Basic HTTP client
  dio: ^5.4.0                   # Advanced HTTP with interceptors
  
  # Storage & Data
  shared_preferences: ^2.2.2    # Local key-value storage
  json_annotation: ^4.8.1       # JSON serialization annotations
  
  # Navigation
  go_router: ^12.1.3            # Declarative routing
  
  # Utils
  intl: ^0.19.0                 # Internationalization
  
  # Firebase (configured but not actively used)
  firebase_core: ^4.1.0
  firebase_auth: ^6.0.2
  cloud_firestore: ^6.0.1
  
dev_dependencies:
  flutter_lints: ^5.0.0         # Dart linting rules
  build_runner: ^2.4.7          # Code generation
  json_serializable: ^6.7.1     # JSON code generation
```

**Why these choices:**
- **Riverpod**: Best-in-class state management with great testing support
- **Dio**: More powerful than http package, with interceptors for auth
- **GoRouter**: Declarative routing that works great with Flutter 3.x
- **SharedPreferences**: Simple persistent storage for auth tokens

### Laravel Dependencies (composer.json)
```json
{
    "require": {
        "php": "^8.2",                    // PHP 8.2+ required
        "laravel/framework": "^12.0",     // Latest Laravel 12
        "laravel/sanctum": "^4.2",        // API authentication tokens
        "laravel/tinker": "^2.10.1",      // REPL for debugging
        "laravel/ui": "^4.6"              // Basic UI scaffolding
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",        // Fake data generation
        "laravel/pail": "^1.2.2",         // Log monitoring
        "laravel/pint": "^1.24",          // Code formatting
        "laravel/sail": "^1.41",          // Docker development
        "mockery/mockery": "^1.6",        // Mocking for tests
        "nunomaduro/collision": "^8.6",   // Better error reporting
        "phpunit/phpunit": "^11.5.3"      // Testing framework
    }
}
```

**Why Laravel Sanctum:**
- Perfect for mobile app authentication
- Stateless token-based auth
- Easy to implement and secure
- Built-in CSRF protection

## Design Patterns Used

### Frontend (Flutter)
- **Provider Pattern**: Riverpod for state management
- **Repository Pattern**: API service abstracts HTTP calls
- **Model-View-ViewModel**: Screens consume providers (ViewModels)
- **Factory Pattern**: JSON serialization with factories

### Backend (Laravel)
- **MVC Pattern**: Controllers handle requests, Models handle data
- **Repository Pattern**: Eloquent models abstract database
- **Service Pattern**: Mail classes handle email logic
- **Request Pattern**: Form request classes for validation

## Future Extensions - Full ATS System

If I had more time, here's how I'd expand this into a complete Applicant Tracking System:

### 1. Advanced Job Board Integration
```php
// Real job board integrations
class JobBoardService {
    public function postToIndeed($job) {
        // Indeed API integration
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.indeed.api_key')
        ])->post('https://api.indeed.com/ads/apisearch', [
            'title' => $job->title,
            'description' => $job->description,
            'location' => $job->location
        ]);
    }
    
    public function postToLinkedIn($job) {
        // LinkedIn Jobs API
    }
}
```

### 2. Advanced Analytics Dashboard
- Application funnel analytics
- Source tracking (which job boards perform best)
- Time-to-hire metrics
- Recruiter performance dashboards
- A/B testing for job descriptions

### 3. Automation Features
```php
// Auto-screening based on keywords
class AutoScreeningService {
    public function screenApplication($application) {
        $keywords = ['PHP', 'Laravel', 'Flutter'];
        $experience = $application->experience;
        
        $score = $this->calculateScore($experience, $keywords);
        
        if ($score > 80) {
            $application->update(['status' => 'interview']);
            // Send automated interview invitation
        }
    }
}
```

### 4. Communication System
- In-app messaging between recruiters and candidates
- Automated email sequences
- Interview scheduling integration (Calendly, etc.)
- SMS notifications for urgent updates

### 5. Advanced Candidate Features
- Resume parsing and skill extraction
- Job recommendation engine
- Application history and analytics
- Salary insights and negotiation tools

### 6. Recruiter Tools
- Candidate pipeline management
- Interview feedback forms
- Team collaboration features
- Custom hiring workflows
- Integration with HRIS systems

### 7. Mobile Enhancements
- Push notifications for status updates
- Offline support for job browsing
- Video interview integration
- Document upload with camera
- Biometric authentication

### 8. Compliance & Security
- GDPR compliance for EU candidates
- Data retention policies
- Audit trails for all actions
- Role-based permissions
- SSO integration for enterprise

**Technical Architecture for Scale:**
```
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│   Flutter App   │    │   React Admin    │    │  Mobile APIs    │
│   (Candidates)  │    │   (Recruiters)   │    │    (Laravel)    │
└─────────┬───────┘    └────────┬─────────┘    └─────────┬───────┘
          │                     │                        │
          └─────────────────────┼────────────────────────┘
                                │
                    ┌───────────┴──────────┐
                    │   Load Balancer      │
                    │   (Nginx/HAProxy)    │
                    └───────────┬──────────┘
                                │
                    ┌───────────┴──────────┐
                    │   API Gateway        │
                    │   (Kong/AWS API GW)  │
                    └───────────┬──────────┘
                                │
        ┌───────────────────────┼───────────────────────┐
        │                       │                       │
┌───────┴────────┐    ┌────────┴─────────┐    ┌───────┴────────┐
│ Laravel APIs   │    │ Microservices    │    │ External APIs  │
│ (Core System)  │    │ (Analytics, ML)  │    │ (Job Boards)   │
└────────────────┘    └──────────────────┘    └────────────────┘
```

This would be a multi-million dollar ATS competing with systems like Greenhouse, Lever, and Workday.

---

**END OF README**
**Thank you**
