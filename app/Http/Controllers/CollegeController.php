<?php

namespace App\Http\Controllers;

use App\College;
use App\Log;
use Auth;
use Illuminate\Http\Request;

class CollegeController extends Controller {
  protected function show() {
    return view('colleges');
  }
  /**
   * @param $id
   * @param nullRequest $request
   */
  protected function get($id = null, Request $request) {
    if ($id) {
      $row = College::findOrFail($id);
    } else {
      $row = College::all();
    }

    return response()->json($row);
  }
  /**
   * @param Request $request
   */
  protected function add(Request $request) {
    $error = [];

    if ($request->logo) {
      if (substr($request->logo->getMimeType(), 0, 5) != 'image') {
        $error[] = 'Logo file is not an image.';
      }

      $logo = $request->name . '-' . time() . '.' . $request->logo->getClientOriginalExtension();
    }

    if ($request->background) {
      if (substr($request->background->getMimeType(), 0, 5) != 'image') {
        $error[] = 'Background file is not an image.';
      }
      $background = $request->name . '-' . time() . '.' . $request->background->getClientOriginalExtension();
    }

    if (count($error) > 0) {
      return json_encode(['success' => false, 'error' => join("\n", $error)]);
    }

    $college = new College;

    $college->name = $request->name;
    $college->description = $request->description;
    $college->logo = $logo ?? null;
    $college->background = $background ?? null;

    if (Auth::user()->isSuperAdmin) {
      if (isset($logo)) {
        $request->logo->move(public_path('img/logo'), $logo);
        $college->logo = $logo;
      }

      if (isset($background)) {
        $request->background->move(public_path('img'), $background);
        $college->background = $background;
      }

      if ($college->save()) {
        Log::create(['action' => Auth::user()->role->description . ' added a college: ' . $college->name]);
        return response()->json(['success' => true]);
      } else {
        return response()->json(['success' => false, 'error' => 'There was an error creating an account!']);
      }
    } else {
      return response()->json(['success' => false, 'error' => 'Forbidden']);
    }
  }
  /**
   * @param Request $request
   */
  protected function edit($id, Request $request) {
    $error = [];

    if ($request->logo) {
      if (substr($request->logo->getMimeType(), 0, 5) != 'image') {
        $error[] = 'Logo file is not an image.';
      }

      $logo = $request->name . '-' . time() . '.' . $request->logo->getClientOriginalExtension();
    }

    if ($request->background) {
      if (substr($request->background->getMimeType(), 0, 5) != 'image') {
        $error[] = 'Background file is not an image.';
      }
      $background = $request->name . '-' . time() . '.' . $request->background->getClientOriginalExtension();
    }

    if (count($error) > 0) {
      return json_encode(['success' => false, 'error' => join("\n", $error)]);
    }

    $college = College::find($id);

    $college->name = $request->name;
    $college->description = $request->description;

    if (Auth::user()->isSuperAdmin) {
      if (isset($logo)) {
        $request->logo->move(public_path('img/logo'), $logo);
        $college->logo = $logo;
      }

      if (isset($background)) {
        $request->background->move(public_path('img'), $background);
        $college->background = $background;
      }

      if ($college->save()) {
        Log::create(['action' => Auth::user()->role->description . ' edited a college: ' . $college->name]);
        return response()->json(['success' => true]);
      } else {
        return response()->json(['success' => false, 'error' => 'There was an error creating an account!']);
      }
    } else {
      return response()->json(['success' => false, 'error' => 'Forbidden']);
    }
  }
/**
 * @param Request $request
 */
  protected function delete($id, Request $request) {
    $college = College::find($id);

    if (Auth::user()->isSuperAdmin) {
      if ($college->delete()) {
        Log::create(['action' => Auth::user()->role->description . ' deleted a college: ' . $college->name]);
        return response()->json(['success' => true]);
      } else {
        return response()->json(['success' => false, 'error' => 'Nothing changed!']);
      }
    } else {
      return response()->json(['success' => false, 'error' => 'Forbidden']);
    }
  }
}
