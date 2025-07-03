@component('mail::message')
# Contact Deleted

Hello,

This is to inform you that your contact information has been removed from our system.

**Contact Details:**
- Name: {{ $contact->name }}
- Email: {{ $contact->email }}
- Phone: {{ $contact->phone }}

If you believe this was done in error, please contact our support team.

Thanks,<br>
{{ config('app.name') }}
@endcomponent 