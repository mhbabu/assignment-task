<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use App\Notifications\AccountVerificationNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/otp-verifications';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'date_of_birth' => ['required', 'date', 'date_format:Y-m-d', 'before:now'],
            'mobile'        => ['required', 'regex:/^(?:\+?88|0088)?01[3-9]\d{8}$/', 'unique:users'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name'          => $data['name'],
                'email'         => $data['email'],
                'date_of_birth' => $data['date_of_birth'],
                'mobile'        => $data['mobile'],
                'password'      => Hash::make($data['password'])
            ]);

            $verificationCode = VerificationCode::create([
                'user_id'   => $user->id,
                'otp'       => rand(100000, 999999),
                'expire_at' => Carbon::now()->addMinutes(10)
            ]);

            DB::commit();
            $user->notify(new AccountVerificationNotification($user, $verificationCode->otp)); // we can use here queue job 
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::error('Database transaction failed: ' . $e->getMessage());
        }
    }
}
