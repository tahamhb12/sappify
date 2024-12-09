<?php

namespace App\Http\Controllers;

use App\Models\AffiliateProgram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AffiliateController extends Controller
{
    public function showAffiliateRequest($app_id, $unique_id)
    {
        $affiliate_program = AffiliateProgram::where('app_id', $app_id)
            ->where('unique_id', $unique_id)
            ->firstOrFail();

        return view('affiliate.affiliateRequest', compact('affiliate_program'));
    }

    public function affiliateRegisterPage()
    {
        return view('affiliate.AffiliateRegister');
    }

    public function register(Request $req)
    {
        $user = User::create([
            'name' => $req->name,
            'role' => 'affiliate',
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);
        Auth::login($user);

        return redirect('/affiliate');
    }
}
