import 'package:json_annotation/json_annotation.dart';
import 'job_listing.dart';

part 'application.g.dart';

@JsonSerializable()
class Application {
  final int id;
  @JsonKey(name: 'job_listing_id')
  final int jobListingId;
  @JsonKey(name: 'full_name')
  final String fullName;
  final String phone;
  final String? email;
  final String position;
  @JsonKey(name: 'work_experience')
  final String workExperience;
  final String status;
  @JsonKey(name: 'created_at')
  final DateTime createdAt;
  @JsonKey(name: 'updated_at')
  final DateTime updatedAt;
  @JsonKey(name: 'job_listing')
  final JobListing? jobListing;

  const Application({
    required this.id,
    required this.jobListingId,
    required this.fullName,
    required this.phone,
    this.email,
    required this.position,
    required this.workExperience,
    required this.status,
    required this.createdAt,
    required this.updatedAt,
    this.jobListing,
  });

  factory Application.fromJson(Map<String, dynamic> json) => _$ApplicationFromJson(json);

  Map<String, dynamic> toJson() => _$ApplicationToJson(this);

  Application copyWith({
    int? id,
    int? jobListingId,
    String? fullName,
    String? phone,
    String? email,
    String? position,
    String? workExperience,
    String? status,
    DateTime? createdAt,
    DateTime? updatedAt,
    JobListing? jobListing,
  }) {
    return Application(
      id: id ?? this.id,
      jobListingId: jobListingId ?? this.jobListingId,
      fullName: fullName ?? this.fullName,
      phone: phone ?? this.phone,
      email: email ?? this.email,
      position: position ?? this.position,
      workExperience: workExperience ?? this.workExperience,
      status: status ?? this.status,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
      jobListing: jobListing ?? this.jobListing,
    );
  }

  String get statusDisplayText {
    switch (status) {
      case 'applied':
        return 'Application Submitted';
      case 'screening':
        return 'Under Review';
      case 'interview':
        return 'Interview Scheduled';
      case 'offer':
        return 'Offer Extended';
      case 'rejected':
        return 'Not Selected';
      default:
        return status.toUpperCase();
    }
  }

  bool get isApplied => status == 'applied';
  bool get isScreening => status == 'screening';
  bool get isInterview => status == 'interview';
  bool get isOffer => status == 'offer';
  bool get isRejected => status == 'rejected';

  // Legacy support
  bool get isPending => status == 'applied';
  bool get isReviewed => status == 'screening';
  bool get isAccepted => status == 'offer';

  String get jobTitle => jobListing?.title ?? position;
  DateTime get appliedAt => createdAt;
  String get applicantName => fullName;
  String get applicantEmail => email ?? '';
  String get coverLetter => workExperience;

  @override
  String toString() {
    return 'Application(id: $id, fullName: $fullName, status: $status, jobListingId: $jobListingId)';
  }

  @override
  bool operator ==(Object other) {
    if (identical(this, other)) return true;

    return other is Application &&
        other.id == id &&
        other.fullName == fullName &&
        other.phone == phone;
  }

  @override
  int get hashCode {
    return id.hashCode ^
        fullName.hashCode ^
        phone.hashCode;
  }
}
