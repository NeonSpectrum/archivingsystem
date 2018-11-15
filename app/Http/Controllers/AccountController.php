<?php

namespace App\Http\Controllers;

use App\Roles;
use App\User;
use Illuminate\Http\Request;

class AccountController extends Controller {
  protected function show() {
    return view('accounts');
  }
  /**
   * @param Request $request
   */
  protected function get($id = null, Request $request) {
    if ($id) {
      $row          = User::findOrFail($id);
      $row->college = Roles::where('id', $row->role_id)->first()->description;
    } else {
      $row = User::all();

      foreach ($row as $data) {
        $data->college = Roles::where('id', $data->role_id)->first()->description;
      }
    }

    return response()->json($row);
  }
  /**
   * @param Request $request
   */
  protected function add(Request $request) {
    $user = new User;

    $user->username   = $request->username;
    $user->password   = $request->password;
    $user->first_name = $request->first_name;
    $user->last_name  = $request->last_name;
    $user->role_id    = Roles::where('name', $request->college)->first()->id;

    if ($user->save()) {
      return response()->json(['success' => true]);
    } else {
      return response()->json(['success' => false, 'error' => 'Already Exists!']);
    }
  }
  /**
   * @param Request $request
   */
  protected function edit($id, Request $request) {
    $role = \Auth::user()->role_id;

    $user = User::find($id);

    $user_role = $user->role_id;

    $user->first_name = $request->first_name;
    $user->last_name  = $request->last_name;
    $user->role_id    = Roles::where('name', $request->college)->first()->id;

    if ($role == 1 || $role != $user_role) {
      if ($user->save()) {
        return response()->json(['success' => true]);
      } else {
        return response()->json(['success' => false, 'error' => $arr]);
      }
    } else {
      return response()->json(['success' => false, 'error' => 'Forbidden']);
    }
  }
/**
 * @param Request $request
 */
  protected function delete($id, Request $request) {
    $user = User::find($id);

    $role = \Auth::user()->role_id;

    if ($role == 1 || $role != $user->role_id) {
      if ($user->delete()) {
        return response()->json(['success' => true]);
      } else {
        return response()->json(['success' => false, 'error' => 'Nothing changed!']);
      }
    } else {
      return response()->json(['success' => false, 'error' => 'Forbidden']);
    }

  }

}
