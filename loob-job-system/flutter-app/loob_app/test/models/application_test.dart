import 'package:flutter_test/flutter_test.dart';
import 'package:loob_app/models/application.dart';

void main() {
  group('Application Model Tests', () {
    test('should create Application from JSON', () {
      final json = {
        'id': 1,
        'job_listing_id': 1,
        'full_name': 'Ahmad Rahman bin Abdullah',
        'phone': '+60123456789',
        'email': 'ahmad.rahman@gmail.com',
        'position': 'Software Developer',
        'work_experience': 'Saya mempunyai 5 tahun pengalaman dalam pembangunan perisian untuk syarikat-syarikat di Malaysia.',
        'status': 'applied',
        'created_at': '2024-01-01T08:00:00.000000+08:00',
        'updated_at': '2024-01-01T08:00:00.000000+08:00',
      };

      final application = Application.fromJson(json);

      expect(application.id, 1);
      expect(application.fullName, 'Ahmad Rahman bin Abdullah');
      expect(application.phone, '+60123456789');
      expect(application.email, 'ahmad.rahman@gmail.com');
      expect(application.status, 'applied');
    });

    test('should create Application from JSON without email', () {
      final json = {
        'id': 1,
        'job_listing_id': 1,
        'full_name': 'Siti Nurhaliza binti Mohd Ali',
        'phone': '+60187654321',
        'position': 'UI/UX Designer',
        'work_experience': 'Fresh graduate dengan portfolio projek design untuk startup Malaysia.',
        'status': 'screening',
        'created_at': '2024-01-01T08:00:00.000000+08:00',
        'updated_at': '2024-01-01T08:00:00.000000+08:00',
      };

      final application = Application.fromJson(json);

      expect(application.fullName, 'Siti Nurhaliza binti Mohd Ali');
      expect(application.email, isNull);
      expect(application.status, 'screening');
    });

    test('should return correct status display text', () {
      final application = Application(
        id: 1,
        jobListingId: 1,
        fullName: 'Lim Wei Ming',
        phone: '+60123456789',
        position: 'Full Stack Developer',
        workExperience: 'Pengalaman 3 tahun dalam pembangunan aplikasi web menggunakan Laravel dan React.',
        status: 'applied',
        createdAt: DateTime.now(),
        updatedAt: DateTime.now(),
      );

      expect(application.statusDisplayText, 'Application Submitted');
      
      final screeningApp = application.copyWith(status: 'screening');
      expect(screeningApp.statusDisplayText, 'Under Review');
      
      final interviewApp = application.copyWith(status: 'interview');
      expect(interviewApp.statusDisplayText, 'Interview Scheduled');
    });

    test('should return correct status booleans', () {
      final application = Application(
        id: 1,
        jobListingId: 1,
        fullName: 'Chen Wei Loon',
        phone: '+60187654321',
        position: 'Backend Developer',
        workExperience: 'Pengalaman 2 tahun dalam pembangunan API dan sistem database.',
        status: 'applied',
        createdAt: DateTime.now(),
        updatedAt: DateTime.now(),
      );

      expect(application.isApplied, isTrue);
      expect(application.isScreening, isFalse);
      expect(application.isInterview, isFalse);
    });

    test('should provide backward compatibility getters', () {
      final application = Application(
        id: 1,
        jobListingId: 1,
        fullName: 'Rajesh Kumar a/l Subramaniam',
        phone: '+60198765432',
        email: 'rajesh.kumar@gmail.com',
        position: 'Mobile Developer',
        workExperience: 'Pengalaman 4 tahun dalam pembangunan aplikasi mobile untuk iOS dan Android.',
        status: 'applied',
        createdAt: DateTime.now(),
        updatedAt: DateTime.now(),
      );

      expect(application.applicantName, 'Rajesh Kumar a/l Subramaniam');
      expect(application.applicantEmail, 'rajesh.kumar@gmail.com');
      expect(application.coverLetter, 'Pengalaman 4 tahun dalam pembangunan aplikasi mobile untuk iOS dan Android.');
    });

    test('should handle missing email in backward compatibility', () {
      final application = Application(
        id: 1,
        jobListingId: 1,
        fullName: 'Nurul Aina binti Hassan',
        phone: '+60176543210',
        position: 'Frontend Developer',
        workExperience: 'Fresh graduate dengan pengalaman internship di syarikat teknologi Malaysia.',
        status: 'applied',
        createdAt: DateTime.now(),
        updatedAt: DateTime.now(),
      );

      expect(application.applicantEmail, '');
    });
  });
}