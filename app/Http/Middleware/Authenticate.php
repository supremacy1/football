<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;

class Authenticate
{
    protected $auth;

    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next, ...$guards)
    {
        if ($this->auth->guard()->guest()) {
            if ($request->expectsJson()) {
                throw new AuthenticationException('Unauthenticated.');
            }

            return redirect()->guest(route('login'));
        }

        return $next($request);
    }
}
