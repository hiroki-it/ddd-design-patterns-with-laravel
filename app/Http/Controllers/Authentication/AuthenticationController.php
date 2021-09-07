<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Requests\Authentication\AuthenticationRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

final class AuthenticationController
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json();
    }

    /**
     * @param AuthenticationRequest $authenticationRequest
     * @return Application|RedirectResponse|Redirector
     */
    public function login(AuthenticationRequest $authenticationRequest)
    {
        $validated = $authenticationRequest->validated();

        if (auth()->attempt($validated)) {
            // セッションID固定化を防ぐために，認証後にセッションを再作成します．
            $authenticationRequest->session()->regenerate();

            // 認証後ページにリダイレクトします．
            return redirect(RouteServiceProvider::AUTHENTHICATED);
        }

        // 認証前または認証失敗時は，認証前ページにリダイレクトします．
        return redirect(RouteServiceProvider::UNAUTHENTHICATED);
    }

    /**
     * @param AuthenticationRequest $authenticationRequest
     * @return Application|RedirectResponse|Redirector
     */
    public function logout(AuthenticationRequest $authenticationRequest)
    {
        auth()->logout();

        // セッションを削除します．
        $authenticationRequest->session()->invalidate();

        // CSRFトークンを再生成します．
        $authenticationRequest->session()->regenerateToken();

        return redirect(RouteServiceProvider::UNAUTHENTHICATED);
    }
}
