sealed class ApiResult<T> {
  const ApiResult();
}

class ApiSuccess<T> extends ApiResult<T> {
  final T data;
  final String? message;

  const ApiSuccess(this.data, {this.message});

  @override
  String toString() => 'ApiSuccess(data: $data, message: $message)';
}

class ApiError<T> extends ApiResult<T> {
  final String message;
  final int? statusCode;
  final dynamic originalError;

  const ApiError(
    this.message, {
    this.statusCode,
    this.originalError,
  });

  @override
  String toString() => 'ApiError(message: $message, statusCode: $statusCode)';
}

class ApiLoading<T> extends ApiResult<T> {
  const ApiLoading();

  @override
  String toString() => 'ApiLoading()';
}

// Helper extension for easier usage
extension ApiResultExtension<T> on ApiResult<T> {
  bool get isSuccess => this is ApiSuccess<T>;
  bool get isError => this is ApiError<T>;
  bool get isLoading => this is ApiLoading<T>;

  T? get data => switch (this) {
    ApiSuccess<T>(:final data) => data,
    _ => null,
  };

  String? get error => switch (this) {
    ApiError<T>(:final message) => message,
    _ => null,
  };

  R when<R>({
    required R Function(T data) success,
    required R Function(String error) error,
    required R Function() loading,
  }) {
    return switch (this) {
      ApiSuccess<T>(:final data) => success(data),
      ApiError<T>(:final message) => error(message),
      ApiLoading<T>() => loading(),
    };
  }
}
