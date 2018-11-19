<?php

namespace App\Http\Controllers;

use App\Logs;
use App\Roles;
use App\User;
use Illuminate\Database\QueryException;
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
      $row            = User::findOrFail($id);
      $row->role_name = Roles::where('id', $row->role_id)->first()->name;
      $row->college   = Roles::where('id', $row->role_id)->first()->description;
    } else {
      $row = User::all();

      foreach ($row as $data) {
        $data->role_name = Roles::where('id', $data->role_id)->first()->name;
        $data->college   = Roles::where('id', $data->role_id)->first()->description;
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

    $role = \Auth::user()->role;

    try {
      if ($user->save()) {
        Logs::create(['action' => $role->description . ' added an with username: ' . $user->username]);
        return response()->json(['success' => true]);
      } else {
        return response()->json(['success' => false, 'error' => 'There was an error creating an account!']);
      }
    } catch (QueryException $e) {
      $errorCode = $e->errorInfo[1];
      if ($errorCode == 1062) {
        return response()->json(['success' => false, 'error' => 'Already Exists!']);
      } else {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
      }
    }
  }
  /**
   * @param Request $request
   */
  protected function edit($id, Request $request) {

    $user = User::find($id);

    $user_role = $user->role_id;

    $user->first_name = $request->first_name;
    $user->last_name  = $request->last_name;
    $user->role_id    = Roles::where('name', $request->college)->first()->id;

    $role = \Auth::user()->role;

    if ($role->role_id == 1 || $role->role_id != $user->role_id) {
      if ($user->save()) {
        Logs::create(['action' => $role->description . ' edited an account with username: ' . $user->username]);
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

    $role = \Auth::user()->role;

    if ($role->role_id == 1 || $role->role_id != $user->role_id) {
      if ($user->delete()) {
        Logs::create(['action' => $role->description . ' deleted an account with username: ' . $user->username]);
        return response()->json(['success' => true]);
      } else {
        return response()->json(['success' => false, 'error' => 'Nothing changed!']);
      }
    } else {
      return response()->json(['success' => false, 'error' => 'Forbidden']);
    }
  }

  /**
   * @param Request $request
   */
  protected function changePassword(Request $request) {
    $user = User::find(\Auth::user()->id);

    if (\Hash::check($request->old_password, $user->password)) {
      $user->password = $request->new_password;
      if ($user->save()) {
        Logs::create(['action' => $user->username . ' changed password.']);
        return response()->json(['success' => true]);
      }
    } else {
      return response()->json(['success' => false, 'error' => 'Invalid Old Password']);
    }
  }
}
