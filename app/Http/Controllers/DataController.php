<?php

namespace App\Http\Controllers;

use App\Attachment;
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
      $rows          = Data::with('attachments')->findOrFail($id);
      $rows->college = Data::find($rows->id)->college->name;
    } else {
      if ($request->filter == 'all') {
        $rows = Data::all();
      } else if ($request->filter == 'college') {
        $rows = Data::where('college_id', Auth::user()->college_id)->get();
      } else if ($request->filter == 'my') {
        $rows = Data::where([
          ['authors', 'like', '%' . Auth::user()->searchName . '%']
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
    set_time_limit(0);

    $data = new Data;

    if (!Auth::user()->isAdmin) {
      $request->authors = join(';', array_merge([Auth::user()->searchName], explode(';', $request->authors)));
    }

    if (Data::where('title', $request->title)->count() > 0) {
      return response()->json(['success' => false, 'error' => 'Title already exists.']);
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
    $data->url               = $request->url;
    $data->conference_name   = $request->conference_name;

    if ($data->save()) {
      if (count($request->attachments) > 0) {
        foreach ($request->attachments as $file) {
          $filename = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '-' . time() . '.' . $file->getClientOriginalExtension();
          $file->move(public_path('uploads'), $filename);

          $attachment = new Attachment;
          $attachment->data()->associate($data);
          $attachment->filename = $filename;
          $attachment->save();
        }
      }
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
    set_time_limit(0);

    $data = Data::find($id);

    if (Data::whereNot('id', $id)->where('title', trim($request->title))->count() > 0) {
      return response()->json(['success' => false, 'error' => 'Title already exists.']);
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
    $data->url               = $request->url;
    $data->conference_name   = $request->conference_name;

    if (Auth::user()->isSuperAdmin || Auth::user()->isAdmin || $data->isResearchOwner) {
      if ($data->save()) {
        if ($request->attachments && count($request->attachments) > 0) {
          foreach ($request->attachments as $id => $file) {
            $filename = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '-' . (time() + $id) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            $attachment = new Attachment;
            $attachment->data()->associate($data);
            $attachment->filename = $filename;
            $attachment->save();
          }
        }
        if ($request->attachments_to_delete && count($request->attachments_to_delete) > 0) {
          foreach ($request->attachments_to_delete as $file) {
            $attachment = Attachment::find($file);
            $attachment->delete();
          }
        }
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
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '1024M');
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

  /**
   * @param $filename
   */
  public function showUpload($title, $id) {
    $attachment = Attachment::findOrFail($id);
    $data       = Data::where('title', $title)->firstOrFail();

    if (Auth::user()->isSuperAdmin || Auth::user()->isAdmin || $data->isResearchOwner) {
      return response()->file(public_path('uploads/' . $attachment->filename));
    } else {
      abort(404);
    }
  }
}
