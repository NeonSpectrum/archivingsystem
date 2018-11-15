<?php

namespace App\Http\Controllers;

use App\Data;
use App\Imports\DataImport;
use App\Roles;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DataController extends Controller {

  /**
   * @param Request $request
   */
  protected function show(Request $request) {
    return view('dashboard');
  }

  /**
   * @param Request $request
   */
  protected function get($id = null, Request $request) {

    if ($id) {
      $rows = Data::findOrFail($id);
    } else {
      $rows = Data::all();
    }

    return response()->json(['role_id' => \Auth::user()->role_id, 'data' => $rows]);
  }
  /**
   * @param Request $request
   */
  protected function add(Request $request) {
    $file = $request->file;

    $data = new Data;

    $data->role_id           = Roles::where('name', $request->college)->first()->id;
    $data->title             = $request->title;
    $data->authors           = $request->authors;
    $data->keywords          = $request->keywords;
    $data->category          = $request->category;
    $data->publisher         = $request->publisher;
    $data->proceeding_date   = $request->proceeding_date;
    $data->presentation_date = $request->presentation_date;
    $data->publication_date  = $request->publication_date;
    $data->note              = $request->note;

    if ($file) {
      $filename = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '-' . time() . '.' . $file->getClientOriginalExtension();
      $file->move(public_path('uploads'), $filename);

      $data->file_name = $filename;
    }

    if ($data->save()) {
      return response()->json(['success' => true]);
    } else {
      return response()->json(['success' => false, 'error' => 'Nothing changed!']);
    }
  }
  /**
   * @param Request $request
   */
  protected function edit($id, Request $request) {
    $file = $request->file;

    $data = Data::find($id);

    $data_role = $data->role_id;

    $data->role_id           = Roles::where('name', $request->college)->first()->id;
    $data->title             = $request->title;
    $data->authors           = $request->authors;
    $data->keywords          = $request->keywords;
    $data->category          = $request->category;
    $data->publisher         = $request->publisher;
    $data->proceeding_date   = $request->proceeding_date;
    $data->presentation_date = $request->presentation_date;
    $data->publication_date  = $request->publication_date;
    $data->note              = $request->note;

    if ($file) {
      $filename = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '-' . time() . '.' . $file->getClientOriginalExtension();

      $file->move(public_path('uploads'), $filename);

      $data->file_name = $filename;
    }

    $role = \Auth::user()->role_id;

    if ($role == 1 || $role != $data_role) {
      if ($data->save()) {
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
    $data = Data::find($id);

    $role = \Auth::user()->role_id;

    if ($role == 1 || $role != $data->role_id) {
      if ($data->delete()) {
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
  protected function upload(Request $request) {
    try {
      Excel::import(new DataImport, $request->file);
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
  }

/**
 * @return mixed
 */
  protected function pdf(Request $request) {

    if ($request->data) {
      $request->data = json_decode($request->data);

      $data = [];

      foreach ($request->data as $id => $value) {
        if (!isset($value[1])) {
          break;
        }

        $data[$id]                    = new \stdClass();
        $data[$id]->title             = $value[1];
        $data[$id]->authors           = $value[2];
        $data[$id]->keywords          = $value[3];
        $data[$id]->category          = $value[4];
        $data[$id]->publisher         = $value[5];
        $data[$id]->proceeding_date   = $value[6];
        $data[$id]->presentation_date = $value[7];
        $data[$id]->publication_date  = $value[8];
        $data[$id]->note              = $value[9];
      }
    } else {
      $data = \DB::table('data')->get();
    }

    $pdf = \PDF::loadView('pdf', ['data' => $data])->setPaper('a4', 'landscape');

    return $pdf->stream();
  }
}
