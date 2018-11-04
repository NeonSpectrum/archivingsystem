<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller {
  protected function show() {
    return Auth::check() ? redirect()->route('dashboard') : view('login');
  }

  /**
   * @param array $data
   * @return mixed
   */
  protected function process(Request $request) {
    $credentials = ['username' => $request->username, 'password' => $request->password];

    if (Auth::attempt($credentials)) {
      return json_encode(['success' => true]);
    } else {
      return json_encode(['success' => false, 'error' => 'Invalid Username and/or Password.']);
    }
  }

  protected function logout() {
    Auth::logout();

    return redirect()->route('login');
  }
}
