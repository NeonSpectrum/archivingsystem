<?php

namespace App\Http\Controllers;

use App\Imports\DataImport;
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
  protected function get(Request $request) {

    if ($request->id) {
      $rows = \DB::table('data')->where('id', $request->id)->first();
    } else {
      $rows = \DB::table('data')->get();
    }

    return json_encode($rows ?? []);
  }
  /**
   * @param Request $request
   */
  protected function add(Request $request) {
    $file = $request->file;

    $filename = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '-' . time() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('uploads'), $filename);

    try {
      $affectedRows = \DB::table('data')->insert([
        'title'             => $request->title,
        'authors'           => $request->authors,
        'keywords'          => $request->keywords,
        'category'          => $request->category,
        'publisher'         => $request->publisher,
        'proceeding_date'   => $request->proceeding_date,
        'presentation_date' => $request->presentation_date,
        'publication_date'  => $request->publication_date,
        'file_name'         => $filename
      ]);
    } catch (QueryException $e) {
      return json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    if ($affectedRows > 0) {
      return json_encode(['success' => true]);
    } else {
      return json_encode(['success' => false, 'error' => 'Nothing changed!']);
    }
  }
  /**
   * @param Request $request
   */
  protected function edit(Request $request) {
    $id   = $request->id;
    $file = $request->file;

    $arr = [
      'title'             => $request->title,
      'authors'           => $request->authors,
      'keywords'          => $request->keywords,
      'category'          => $request->category,
      'publisher'         => $request->publisher,
      'proceeding_date'   => $request->proceeding_date,
      'presentation_date' => $request->presentation_date,
      'publication_date'  => $request->publication_date
    ];

    if ($file) {
      $filename = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '-' . time() . '.' . $file->getClientOriginalExtension();

      $file->move(public_path('uploads'), $filename);

      $arr['file_name'] = $filename;
    }

    try {
      $affectedRows = \DB::table('data')->where('id', $id)->update($arr);
    } catch (QueryException $e) {
      return json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    if ($affectedRows > 0) {
      return json_encode(['success' => true]);
    } else {
      return json_encode(['success' => false, 'error' => 'Nothing changed!']);
    }
  }
  /**
   * @param Request $request
   */
  protected function delete(Request $request) {
    try {
      $affectedRows = \DB::table('data')->where('id', $request->id)->delete();
    } catch (QueryException $e) {
      return json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    if ($affectedRows > 0) {
      return json_encode(['success' => true]);
    } else {
      return json_encode(['success' => false, 'error' => 'Nothing changed!']);
    }
  }

  /**
   * @param Request $request
   */
  protected function upload(Request $request) {
    try {
      Excel::import(new DataImport, $request->file);
      return json_encode(['success' => true]);
    } catch (\Exception $e) {
      return json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
  }
}
