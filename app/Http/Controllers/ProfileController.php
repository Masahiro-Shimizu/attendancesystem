<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * プロフィール情報を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('profile.show',['user' => Auth::user()]);
    }
}
