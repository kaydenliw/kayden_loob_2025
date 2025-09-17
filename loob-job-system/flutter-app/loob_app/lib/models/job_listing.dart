import 'package:json_annotation/json_annotation.dart';

part 'job_listing.g.dart';

@JsonSerializable()
class JobListing {
  final int id;
  final String title;
  final String location;
  final String description;
  @JsonKey(name: 'created_at')
  final DateTime createdAt;
  @JsonKey(name: 'updated_at')
  final DateTime updatedAt;

  const JobListing({
    required this.id,
    required this.title,
    required this.location,
    required this.description,
    required this.createdAt,
    required this.updatedAt,
  });

  factory JobListing.fromJson(Map<String, dynamic> json) => _$JobListingFromJson(json);

  Map<String, dynamic> toJson() => _$JobListingToJson(this);

  JobListing copyWith({
    int? id,
    String? title,
    String? location,
    String? description,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) {
    return JobListing(
      id: id ?? this.id,
      title: title ?? this.title,
      location: location ?? this.location,
      description: description ?? this.description,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  @override
  String toString() {
    return 'JobListing(id: $id, title: $title, location: $location)';
  }

  @override
  bool operator ==(Object other) {
    if (identical(this, other)) return true;

    return other is JobListing &&
        other.id == id &&
        other.title == title &&
        other.location == location;
  }

  @override
  int get hashCode {
    return id.hashCode ^
        title.hashCode ^
        location.hashCode;
  }
}
