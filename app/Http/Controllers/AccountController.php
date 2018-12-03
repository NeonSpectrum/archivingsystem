<?php

namespace App\Http\Controllers;

use App\Log;
use App\Roles;
use App\User;
use Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AccountController extends Controller {
  protected function show() {
    return view('accounts');
  }
  /**
   * @param $id
   * @param Request $request
   */
  protected function get($id = null, Request $request) {
    if ($id) {
      $row          = User::findOrFail($id);
      $row->name    = User::find($row->id)->name;
      $row->college = User::find($row->id)->college;
      $row->role    = User::find($row->id)->role;
    } else {
      if (Auth::user()->isSuperAdmin) {
        $row = User::all();
      } else {
        $row = User::where('college_id', Auth::user()->college_id)->get();
      }

      foreach ($row as $data) {
        $data->name    = User::find($data->id)->name;
        $data->college = User::find($data->id)->college;
        $data->role    = User::find($data->id)->role;
      }
    }

    return response()->json($row);
  }
  /**
   * @param Request $request
   */
  protected function add(Request $request) {
    $user = new User;

    $user->username       = $request->username;
    $user->password       = $request->password;
    $user->first_name     = $request->first_name;
    $user->middle_initial = $request->middle_initial;
    $user->last_name      = $request->last_name;

    if (Auth::user()->isSuperAdmin) {
      $user->role_id    = $request->type;
      $user->college_id = $request->college;
    } else {
      if ($request->type == 1) {
        return response()->json(['success' => false, 'error' => 'Forbidden']);
      }
      $user->role_id    = $request->type;
      $user->college_id = Auth::user()->college_id;
    }

    try {
      if ($user->save()) {
        $college = Auth::user()->isSuperAdmin ? 'Super Admin' : Auth::user()->college->description;
        Log::create(['action' => $college . ' added an with username: ' . $user->username]);
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

    $user->first_name     = $request->first_name;
    $user->middle_initial = $request->middle_initial;
    $user->last_name      = $request->last_name;

    if (Auth::user()->isSuperAdmin) {
      $user->role_id    = $request->type;
      $user->college_id = $request->college;
    } else {
      if ($request->type == 1) {
        return response()->json(['success' => false, 'error' => 'Forbidden']);
      }
      $user->role_id    = $request->type;
      $user->college_id = Auth::user()->college_id;
    }

    if (Auth::user()->isSuperAdmin
      || (Auth::user()->isAdmin
        && Auth::user()->college_id == $user->college_id
      )
    ) {
      if ($user->save()) {
        $college = Auth::user()->isSuperAdmin ? 'Super Admin' : Auth::user()->college->description;
        Log::create(['action' => $college . ' edited an account with username: ' . $user->username]);
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

    if (Auth::user()->isSuperAdmin
      || (Auth::user()->isAdmin
        && Auth::user()->college_id == $user->college_id
      )
    ) {
      if ($user->delete()) {
        $college = Auth::user()->isSuperAdmin ? 'Super Admin' : Auth::user()->college->description;
        Log::create(['action' => $college . ' deleted an account with username: ' . $user->username]);
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
    $user = Auth::user();

    if (\Hash::check($request->old_password, $user->password)) {
      $user->password = $request->new_password;
      if ($user->save()) {
        Log::create(['action' => $user->username . ' changed password.']);
        return response()->json(['success' => true]);
      }
    } else {
      return response()->json(['success' => false, 'error' => 'Invalid Old Password']);
    }
  }

  protected function config() {
    $user = Auth::user();

    return [
      'user_id'      => $user->id,
      'role_id'      => $user->role_id,
      'college_id'   => $user->college_id,
      'isSuperAdmin' => $user->isSuperAdmin,
      'isAdmin'      => $user->isAdmin
    ];
  }
}
