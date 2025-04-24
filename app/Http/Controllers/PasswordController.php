<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{EmailCheckRequest};
use App\Jobs\SendVerificationCodeMail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Traits\HttpsResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    use HttpsResponse;

    public function sendOrFail(EmailCheckRequest $request)
    {
        $oldVerifications = PasswordReset::where('email', $request->email)->get();
        foreach ($oldVerifications as $verification) {
            $verification->delete();
        }

        $verificationCode = Str::uuid();

        $check = PasswordReset::create([
            'email' => $request->email,
            'verification_code' => $verificationCode,
            'expired_at' => now()->addSeconds((int) env('VERIFICATION_EXPIRATION')),
        ]);

        if (!$check) {
            return $this->error('Something went wrong, try again', null, [], 400);
        }

        try {
            SendVerificationCodeMail::dispatch($request->email, $verificationCode);
        } catch (\Exception $e) {
            return $this->error('Failed to send email. Please try again.', null, [], 500);
        }

        return $this->success('Enter the verification code that has been sent to your email');
    }

    public function validOrFail(Request $request)
    {
        $request->validate([
            'verification' => 'required|string'
        ]);

        $verification = PasswordReset::where('verification_code', $request->verification)->first();

        if (!$verification) {
            return $this->error('Verification code is not valid.', null, [], 400);
        }

        if (now()->greaterThan($verification->expired_at)) {
            return $this->error('Verification code has already expired. Please request a new one.', null, [], 400);
        }

        $verification->delete();

        return $this->success('Enter your new password.');
    }

    public function changeOrFail(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', Password::default(), 'confirmed'],
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->error('User not found.', null, [], 404);
        }

        if (Hash::check($request->password, $user->password)) {
            return $this->error('You cannot use your old password.', null, [], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return $this->success('Password has been successfully updated.');
    }
}
