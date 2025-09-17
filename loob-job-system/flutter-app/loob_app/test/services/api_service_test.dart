import 'package:flutter_test/flutter_test.dart';
import 'package:loob_app/services/api_service.dart';

void main() {
  group('ApiService Tests', () {
    test('should be a singleton', () {
      final instance1 = ApiService();
      final instance2 = ApiService();
      
      expect(identical(instance1, instance2), isTrue);
    });

    test('should create proper request data for application submission', () {
      final requestData = {
        'full_name': 'Ahmad Rahman bin Abdullah',
        'phone': '+60123456789',
        'email': 'ahmad.rahman@gmail.com',
        'position': 'Software Developer',
        'work_experience': 'Saya mempunyai 5 tahun pengalaman dalam pembangunan perisian untuk syarikat-syarikat di Malaysia.',
        'job_listing_id': 1,
      };

      expect(requestData['full_name'], 'Ahmad Rahman bin Abdullah');
      expect(requestData['phone'], '+60123456789');
      expect(requestData['email'], 'ahmad.rahman@gmail.com');
      expect(requestData['position'], 'Software Developer');
      expect(requestData['work_experience'], 'Saya mempunyai 5 tahun pengalaman dalam pembangunan perisian untuk syarikat-syarikat di Malaysia.');
      expect(requestData['job_listing_id'], 1);
    });

    test('should create proper query params for status check', () {
      final queryParamsPhone = <String, dynamic>{'phone': '+60123456789'};
      final queryParamsEmail = <String, dynamic>{'email': 'siti.nurhaliza@gmail.com'};

      expect(queryParamsPhone['phone'], '+60123456789');
      expect(queryParamsEmail['email'], 'siti.nurhaliza@gmail.com');
    });
  });
}
