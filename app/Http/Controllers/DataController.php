<?php

namespace App\Http\Controllers;

use App\Data;
use App\Imports\DataImport;
use App\Logs;
use App\Roles;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DataController extends Controller {

  /**
   * @param Request $request
   */
  protected function show(Request $request) {
    return \Auth::user()->isAdmin ? redirect()->route('dashboard.all') : view('dashboard', ['filter' => 'my']);
  }

  /**
   * @param Request $request
   */
  protected function showCollege(Request $request) {
    return view('dashboard-college', ['filter' => 'college']);
  }

  /**
   * @param Request $request
   */
  protected function showAll(Request $request) {
    return view('dashboard-all', ['filter' => 'all']);
  }

  /**
   * @param Request $request
   */
  protected function get($id = null, Request $request) {

    if ($id) {
      $rows          = Data::findOrFail($id);
      $rows->college = Roles::where('id', $rows->role_id)->first()->name;
    } else {
      if ($request->filter == 'all') {
        $rows = Data::all();
      } else if ($request->filter == 'college') {
        $rows = Data::where('role_id', \Auth::user()->role_id);
      } else if ($request->filter == 'my') {
        $rows = Data::where([
          ['authors', 'like', '%' . \Auth::user()->name . '%']
        ]);
      }
      foreach ($rows as $row) {
        $row->college = Roles::where('id', $row->role_id)->first()->name;
      }
    }

    return response()->json(['role_id' => \Auth::user()->role_id, 'data' => $rows]);
  }
  /**
   * @param Request $request
   */
  protected function add(Request $request) {
    $pdf_file         = $request->pdf_file;
    $pdf_mime         = $pdf_file->getMimeType();
    $certificate_file = $request->certificate_file;
    $certificate_mime = $certificate_file->getMimeType();

    if (substr($pdf_mime, 0, 5) != 'image' || strpos($pdf_mime, 'application/pdf') !== 0) {
      $error[] = 'Upload PDF contains an invalid format.';
    }

    if (substr($certificate_mime, 0, 5) != 'image' || strpos($certificate_mime, 'application/pdf') !== 0) {
      $error[] = 'Upload Certificate contains an invalid format.';
    }

    if (count($error) > 0) {
      return response()->json(['success' => false, 'error' => join(' ', $error)]);
    }

    $data = new Data;

    $role = Roles::where('name', $request->college)->first();

    if (!\Auth::user()->isAdmin) {
      $request->authors = join(',', array_merge([Auth::user()->name], explode(',', $request->authors)));
    }

    $data->role_id           = $role->id;
    $data->title             = $request->title;
    $data->authors           = $request->authors;
    $data->keywords          = $request->keywords;
    $data->category          = $request->category;
    $data->publisher         = $request->publisher;
    $data->proceeding_date   = $request->proceeding_date;
    $data->presentation_date = $request->presentation_date;
    $data->publication_date  = $request->publication_date;
    $data->note              = $request->note;

    if ($pdf_file) {
      $filename = str_replace('.' . $pdf_file->getClientOriginalExtension(), '', $pdf_file->getClientOriginalName()) . '-' . time() . '.' . $pdf_file->getClientOriginalExtension();
      $pdf_file->move(public_path('uploads'), $filename);

      $data->pdf_file_name = $filename;
    }

    if ($certificate_file) {
      $filename = str_replace('.' . $certificate_file->getClientOriginalExtension(), '', $certificate_file->getClientOriginalName()) . '-' . time() . '.' . $certificate_file->getClientOriginalExtension();
      $certificate_file->move(public_path('uploads'), $filename);

      $data->certificate_file_name = $filename;
    }

    if ($data->save()) {
      Logs::create(['action' => $role->description . ' added a research with ID: ' . $data->id]);
      return response()->json(['success' => true]);
    } else {
      return response()->json(['success' => false, 'error' => 'Nothing changed!']);
    }
  }
  /**
   * @param Request $request
   */
  protected function edit($id, Request $request) {
    $pdf_file         = $request->pdf_file;
    $pdf_mime         = $pdf_file->getMimeType();
    $certificate_file = $request->certificate_file;
    $certificate_mime = $certificate_file->getMimeType();

    if (substr($pdf_mime, 0, 5) != 'image' || strpos($pdf_mime, 'application/pdf') !== 0) {
      $error[] = 'Upload PDF contains an invalid format.';
    }

    if (substr($certificate_mime, 0, 5) != 'image' || strpos($certificate_mime, 'application/pdf') !== 0) {
      $error[] = 'Upload Certificate contains an invalid format.';
    }

    if (count($error) > 0) {
      return response()->json(['success' => false, 'error' => join(' ', $error)]);
    }

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

    if ($pdf_file) {
      $filename = str_replace('.' . $pdf_file->getClientOriginalExtension(), '', $pdf_file->getClientOriginalName()) . '-' . time() . '.' . $pdf_file->getClientOriginalExtension();
      $pdf_file->move(public_path('uploads'), $filename);

      $data->pdf_file_name = $filename;
    }

    if ($certificate_file) {
      $filename = str_replace('.' . $certificate_file->getClientOriginalExtension(), '', $certificate_file->getClientOriginalName()) . '-' . time() . '.' . $certificate_file->getClientOriginalExtension();
      $certificate_file->move(public_path('uploads'), $filename);

      $data->certificate_file_name = $filename;
    }

    $role = \Auth::user()->role_id;

    if ($role == 1) {
      if ($data->save()) {
        Logs::create(['action' => $role->description . ' edited a research with ID: ' . $data->id]);
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

    if ($role == 1) {
      if ($data->delete()) {
        Logs::create(['action' => $role->description . ' deleted a research with ID: ' . $data->id]);
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
