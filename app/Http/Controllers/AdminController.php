<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\MonthlyReport;
use App\Models\User;
use App\Http\Requests\AdminLoginRequest;


class AdminController extends Controller
{
    /**
     * 管理者ログインフォームを表示します。
     *
     * @return \Illuminate\View\View
     *     管理者ログインページのビューを返します。
     */
    public function showAdminLoginForm()
    {
        return view('admin.login'); // 管理者用のログインビューを作成する
    }

    /**
     * 管理者ログイン処理を行います。
     *
     * @param \Illuminate\Http\Request $request
     *     HTTPリクエストオブジェクト。ログイン情報が含まれます。
     *
     * @return \Illuminate\Http\RedirectResponse
     *     ログイン成功時に管理者ホームページへリダイレクトし、失敗時にはエラーメッセージを返します。
     *
     * @throws \Illuminate\Validation\ValidationException
     *     バリデーションに失敗した場合に例外が発生します。
     */
    public function adminLogin(AdminLoginRequest $request)
    {
        // バリデーションエラーがある場合、ここでリダイレクトされる
        $validated = $request->validated();

        //バリデーションを剥がしたためコメントアウト
        //$this->validate($request, [
            //'email' => 'required|email',
            //'password' => 'required',
        //]);

        // 管理者専用のガードを使ってログイン
        //if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            //return redirect()->intended('/admin/home'); // ログイン成功時に管理者専用ページにリダイレクト
        //}

        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return redirect()->intended('/admin/home');
        }

        return back()->withInput($request->only('email'))->withErrors(['email' => 'Invalid credentials']);
    }

   /**
     * 管理者用ホームページを表示し、全ての月次報告データを渡します。
     *
     * @return \Illuminate\View\View
     *     管理者ホームページビューを表示します。
     */
    public function home()
    {
        // 月次報告データを取得
        $reports = MonthlyReport::with('user')->get(); // 全ての報告を取得
        
        // home.blade.phpにデータを渡して表示
        return view('admin.home', compact('reports'));
    }

   /**
     * ユーザー一覧を取得し、管理者ページで表示します。
     *
     * @return \Illuminate\View\View
     *     ユーザー一覧ビューを返します。
     */
    public function index()
    {
        // 全てのユーザーを取得
        $users = User::all();
    
        // 管理者専用のユーザー一覧ビューを返す
        return view('admin.users', compact('users'));
    }

    /**
     * ユーザーを管理者に昇格します。
     *
     * @param int $id
     *     昇格対象のユーザーID。
     *
     * @return \Illuminate\Http\RedirectResponse
     *     ユーザー一覧ページにリダイレクトし、成功メッセージを表示します。
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *     指定されたIDのユーザーが存在しない場合に例外が発生します。
     */
    public function promote($id)
    {
        $user = User::findOrFail($id);

        // ロールをadminに変更
        $user->role = 'admin';
        $user->save();

        return redirect()->route('admin.users')->with('success', "{$user->name}を管理者に昇格させました。");
    }

   /**
     * ユーザーを管理者に昇格し、adminsテーブルに記録します。
     *
     * @param int $id
     *     昇格対象のユーザーID。
     *
     * @return \Illuminate\Http\RedirectResponse
     *     ユーザー一覧ページにリダイレクトし、成功メッセージを表示します。
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *     指定されたIDのユーザーが存在しない場合に例外が発生します。
     */
    public function promoteToAdmin($id)
    {
        $user = User::findOrFail($id);

        // roleをadminに変更
        $user->role = 'admin';
        $user->save();

        // adminsテーブルに新しいレコードを挿入
        Admin::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password, // 既存ユーザーのハッシュ化されたパスワードを利用
            'role' => 'admin',
        ]);

        return redirect()->route('admin.users')->with('success', 'ユーザーが管理者に昇格しました。');
    }

}
