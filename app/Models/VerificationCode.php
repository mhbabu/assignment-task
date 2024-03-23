<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $table    = 'verification_codes';
    protected $fillable = [
        'user_id', 
        'otp', 
        'expire_at'
    ];

    public static function generateOtp($user)
    {
        $verificationCode = VerificationCode::where('user_id', $user->id)->latest()->first(); // we need to delete all the OTP by a Shedular or another way when user logged out
        $now = Carbon::now();

        if($verificationCode && $now->isBefore($verificationCode->expire_at)){
            return $verificationCode;
        }

        return VerificationCode::create([
            'user_id'   => $user->id,
            'otp'       => rand(100000, 999999),
            'expire_at' => Carbon::now()->addMinutes(10)
        ]);
    }
}
