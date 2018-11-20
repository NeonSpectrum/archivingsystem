<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole {
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next, $role) {
    if ($role == 'super-admin' && !\Auth::user()->isSuperAdmin) {
      return redirect()->route('dashboard');
    } else if ($role == 'admin' && !\Auth::user()->isAdmin) {
      return redirect()->route('dashboard');
    }

    return $next($request);
  }
}
