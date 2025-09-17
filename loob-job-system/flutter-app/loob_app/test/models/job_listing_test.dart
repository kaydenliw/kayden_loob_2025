import 'package:flutter_test/flutter_test.dart';
import 'package:loob_app/models/job_listing.dart';

void main() {
  group('JobListing Model Tests', () {
    test('should create JobListing from JSON', () {
      final json = {
        'id': 1,
        'title': 'Software Developer - Loob Holding',
        'location': 'Kuala Lumpur, Malaysia',
        'description': 'Membangunkan aplikasi perisian untuk syarikat teknologi terkemuka di Malaysia',
        'created_at': '2024-01-01T08:00:00.000000+08:00',
        'updated_at': '2024-01-01T08:00:00.000000+08:00',
      };

      final jobListing = JobListing.fromJson(json);

      expect(jobListing.id, 1);
      expect(jobListing.title, 'Software Developer - Loob Holding');
      expect(jobListing.location, 'Kuala Lumpur, Malaysia');
      expect(jobListing.description, 'Membangunkan aplikasi perisian untuk syarikat teknologi terkemuka di Malaysia');
    });

    test('should convert JobListing to JSON', () {
      final jobListing = JobListing(
        id: 1,
        title: 'Full Stack Developer - Loob Holding',
        location: 'Petaling Jaya, Selangor',
        description: 'Jawatan untuk pembangun full stack yang mahir dalam React dan Laravel',
        createdAt: DateTime.parse('2024-01-01T08:00:00.000000+08:00'),
        updatedAt: DateTime.parse('2024-01-01T08:00:00.000000+08:00'),
      );

      final json = jobListing.toJson();

      expect(json['id'], 1);
      expect(json['title'], 'Full Stack Developer - Loob Holding');
      expect(json['location'], 'Petaling Jaya, Selangor');
      expect(json['description'], 'Jawatan untuk pembangun full stack yang mahir dalam React dan Laravel');
    });

    test('should create copy with updated values', () {
      final original = JobListing(
        id: 1,
        title: 'Backend Developer - Loob Holding',
        location: 'Cyberjaya, Selangor',
        description: 'Jawatan untuk pembangun backend yang mahir dalam Laravel',
        createdAt: DateTime.parse('2024-01-01T08:00:00.000000+08:00'),
        updatedAt: DateTime.parse('2024-01-01T08:00:00.000000+08:00'),
      );

      final copy = original.copyWith(title: 'Senior Backend Developer - Loob Holding');

      expect(copy.id, 1);
      expect(copy.title, 'Senior Backend Developer - Loob Holding');
      expect(copy.location, 'Cyberjaya, Selangor');
    });

    test('should implement equality correctly', () {
      final job1 = JobListing(
        id: 1,
        title: 'Mobile Developer - Loob Holding',
        location: 'Shah Alam, Selangor',
        description: 'Pembangun aplikasi mobile untuk iOS dan Android',
        createdAt: DateTime.parse('2024-01-01T08:00:00.000000+08:00'),
        updatedAt: DateTime.parse('2024-01-01T08:00:00.000000+08:00'),
      );

      final job2 = JobListing(
        id: 1,
        title: 'Mobile Developer - Loob Holding',
        location: 'Shah Alam, Selangor',
        description: 'Pembangun aplikasi mobile dengan fokus pada Flutter',
        createdAt: DateTime.parse('2024-01-01T08:00:00.000000+08:00'),
        updatedAt: DateTime.parse('2024-01-01T08:00:00.000000+08:00'),
      );

      expect(job1, equals(job2));
    });
  });
}
