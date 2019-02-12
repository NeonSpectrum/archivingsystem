<?php

namespace App\Http\Controllers;

use App\Data;
use App\Exports\DataExport;
use App\Imports\DataImport;
use App\Log;
use App\User;
use Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DataController extends Controller {
  /**
   * @param Request $request
   */
  protected function show(Request $request) {
    if (Auth::user()->isSuperAdmin) {
      return redirect()->route('dashboard.all');
    } else if (Auth::user()->isAdmin || Auth::user()->isGuest) {
      return redirect()->route('dashboard.college');
    } else {
      return view('dashboard', ['filter' => 'my']);
    }
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
      $rows->college = Data::find($rows->id)->college->name;
    } else {
      if ($request->filter == 'all') {
        $rows = Data::all();
      } else if ($request->filter == 'college') {
        $rows = Data::where('college_id', Auth::user()->college_id)->get();
      } else if ($request->filter == 'my') {
        $rows = Data::where([
          ['authors', 'like', '%' . Auth::user()->name . '%']
        ])->get();
      }
      foreach ($rows as $row) {
        $row->college = Data::find($row->id)->college->name;
      }
    }

    return response()->json($rows);
  }
  /**
   * @param Request $request
   */
  protected function add(Request $request) {
    $pdf_file         = $request->pdf_file;
    $certificate_file = $request->certificate_file;

    $error = [];

    if ($pdf_file) {
      $pdf_mime = $pdf_file->getMimeType();
      if (substr($pdf_mime, 0, 5) != 'image' || strpos($pdf_mime, 'application/pdf') === FALSE) {
        $error[] = 'Upload PDF contains an invalid format.';
      }
    }
    if ($certificate_file) {
      $certificate_mime = $certificate_file->getMimeType();
      if (substr($certificate_mime, 0, 5) != 'image' || strpos($certificate_mime, 'application/pdf') === FALSE) {
        $error[] = 'Upload Certificate contains an invalid format.';
      }
    }

    if (count($error) > 0) {
      return response()->json(['success' => false, 'error' => join(' ', $error)]);
    }

    $data = new Data;

    if (!Auth::user()->isAdmin) {
      $request->authors = join(',', array_merge([Auth::user()->name], explode(',', $request->authors)));
    }

    $data->college_id        = $request->college ?? Auth::user()->college_id;
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
      Log::create(['action' => Auth::user()->username . ' added a research with ID: ' . $data->id]);
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
    $certificate_file = $request->certificate_file;

    $error = [];

    if ($pdf_file) {
      $pdf_mime = $pdf_file->getMimeType();
      if (false) {
//substr($pdf_mime, 0, 5) != 'image' || strpos($pdf_mime, 'application/pdf') !== 0) {
        $error[] = 'Upload PDF contains an invalid format.';
      }
    }
    if ($certificate_file) {
      $certificate_mime = $certificate_file->getMimeType();
      if (false) {
//substr($certificate_mime, 0, 5) != 'image' || strpos($certificate_mime, 'application/pdf') !== 0) {
        $error[] = 'Upload Certificate contains an invalid format.';
      }
    }

    if (count($error) > 0) {
      return response()->json(['success' => false, 'error' => join(' ', $error)]);
    }

    $data = Data::find($id);

    $data->college_id        = $request->college ?? Auth::user()->college_id;
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

    if (Auth::user()->isSuperAdmin || Auth::user()->isAdmin || $data->isResearchOwner) {
      if ($data->save()) {
        Log::create(['action' => Auth::user()->username . ' edited a research with ID: ' . $data->id]);
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

    if (Auth::user()->isSuperAdmin || Auth::user()->isAdmin || $data->isResearchOwner) {
      if ($data->delete()) {
        Log::create(['action' => Auth::user()->username . ' deleted a research with ID: ' . $data->id]);
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
    if ($request->pdf_data) {
      $request->pdf_data = json_decode($request->pdf_data);

      $data = [];

      foreach ($request->pdf_data as $id => $value) {
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
      if (Auth::user()->isSuperAdmin) {
        $data = Data::all();
      } else {
        $data = Data::where('college_id', Auth::user()->college_id)->get();
      }
    }

    $pdf = \PDF::loadView('pdf', ['data' => $data])->setPaper('a4', 'landscape');

    return $pdf->stream();
  }
  /**
   * @param Request $request
   * @return mixed
   */
  protected function excel(Request $request) {
    if ($request->excel_data) {
      $request->excel_data = json_decode($request->excel_data);

      $data = [];

      foreach ($request->excel_data as $id => $value) {
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
      if (Auth::user()->isSuperAdmin) {
        $data = Data::all();
      } else {
        $data = Data::where('college_id', Auth::user()->college_id)->get();
      }
    }

    return \Excel::download(new DataExport($data), date('F_d_Y_h_i_s_A') . ' Archiving System Report.xlsx');
  }
}
