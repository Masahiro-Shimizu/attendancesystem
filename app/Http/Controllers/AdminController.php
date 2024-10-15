<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // 管理者用のログインフォームを表示
    public function showAdminLoginForm()
    {
        return view('admin.login'); // 管理者用のログインビューを作成する
    }

    // 管理者用のログイン処理
    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 管理者専用のガードを使ってログイン
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/admin/home'); // ログイン成功時に管理者専用ページにリダイレクト
        }

        return back()->withInput($request->only('email'))->withErrors(['email' => 'Invalid credentials']);
    }
}
