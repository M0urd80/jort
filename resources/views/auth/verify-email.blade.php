<!-- verify-email.blade.php -->
<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Please verify your email address by clicking the link we sent you.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to your email.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">
            {{ __('Resend Verification Email') }}
        </button>
    </form>
</x-guest-layout>

