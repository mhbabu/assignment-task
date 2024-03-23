<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        if(!auth()->user()->is_verified) {
            return redirect()->route('otp.verifications');
        }

        $data['verifiedUsers']   = User::where('is_verified', true)->get();  
        $data['unVerifiedUsers'] = User::where('is_verified', false)->get();  
        return view('dashboard', $data);
    }
}
