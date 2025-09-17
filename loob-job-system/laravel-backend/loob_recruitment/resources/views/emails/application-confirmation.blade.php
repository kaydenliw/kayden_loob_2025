<x-mail::message>
# Application Received

Dear {{ $application->full_name }},

Thank you for your interest in the **{{ $application->position }}** position at Loob. We have successfully received your application and will review it carefully.

**Application Details:**
- Position: {{ $application->position }}
- Application ID: {{ $application->id }}
- Submitted: {{ $application->created_at->format('F j, Y \a\t g:i A') }}

Our recruitment team will review your application and contact you within 5-7 business days if your profile matches our requirements.

You can check your application status anytime using your phone number or email address through our mobile app.

Thanks,<br>
{{ config('app.name') }} Recruitment Team
</x-mail::message>
