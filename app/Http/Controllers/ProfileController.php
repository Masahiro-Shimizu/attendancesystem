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
     * @param int $id ユーザーID
     * @return \Illuminate\View\View プロフィールページのビュー
     */
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    /**
     * プロフィール編集フォームを表示する
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * ユーザープロフィールを更新
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id ユーザーID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        // データ更新
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.show', ['id' => $user->id])->with('success', 'プロフィールが更新されました。');
    }
}
