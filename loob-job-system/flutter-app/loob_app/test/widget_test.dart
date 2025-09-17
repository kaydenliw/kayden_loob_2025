import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import 'package:loob_app/main.dart';

void main() {
  testWidgets('App smoke test', (WidgetTester tester) async {
    await tester.pumpWidget(const ProviderScope(child: LoobApp()));

    expect(find.byType(LoobApp), findsOneWidget);
    
    // Handle splash screen timer
    await tester.pumpAndSettle(const Duration(seconds: 3));
  });
}