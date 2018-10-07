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
    try {
      $affectedRows = \DB::table('data')->insert([
        'title'             => $request->title,
        'authors'           => $request->authors,
        'keywords'          => $request->keywords,
        'category'          => $request->category,
        'publisher'         => $request->publisher,
        'proceeding_date'   => $request->proceeding_date,
        'presentation_date' => $request->presentation_date,
        'publication_date'  => $request->publication_date
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
    $id = $request->id;

    try {
      $affectedRows = \DB::table('data')->where('id', $id)->update([
        'title'             => $request->title,
        'authors'           => $request->authors,
        'keywords'          => $request->keywords,
        'category'          => $request->category,
        'publisher'         => $request->publisher,
        'proceeding_date'   => $request->proceeding_date,
        'presentation_date' => $request->presentation_date,
        'publication_date'  => $request->publication_date
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
