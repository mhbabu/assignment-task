<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use App\Notifications\AccountVerificationNotification;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthOTPController extends Controller
{
    public function otpVerification()
    {
        if (auth()->user()->is_verified) {
            return redirect('dashboard');
        }

        return view('otp-verification');
    }

    public function loginWithOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $verificationCode = VerificationCode::where(['user_id' => auth()->id(), 'otp' => $request->otp])->first();

            if (!$verificationCode) {
                throw new \Exception('Your OTP is not correct.');
            }

            $now = Carbon::now();
            if ($now->isAfter($verificationCode->expire_at)) {
                throw new \Exception('Your OTP has expired.');
            }

            $user = User::find(auth()->id());
            if (!$user) {
                throw new \Exception('User not found');
            }

            $user->email_verified_at = Carbon::now();
            $user->is_verified       = true;
            $user->save();

            $verificationCode->update([
                'expire_at' => Carbon::now(),
            ]);


            DB::commit();

            Auth::login($user);
            return redirect(route('dashboard'));
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    public function reGenerateOtp()
    {
        $user             = User::where('id', auth()->id())->first();
        $verificationCode = VerificationCode::where('user_id', $user->id)->latest()->first();
        $now              = Carbon::now();

        if ($verificationCode && $now->isBefore($verificationCode->expire_at)) {
            return $verificationCode;
        }

        $verificationCode = VerificationCode::create([
            'user_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expire_at' => Carbon::now()->addMinutes(10)
        ]);
        $user->notify((new AccountVerificationNotification($user)));

        Toastr::success('OTP successfully regenerated. Check your email');
        return redirect()->back();
    }
}
