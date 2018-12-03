<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Http\Request;

class LogsController extends Controller {
  protected function show() {
    return view('logs', ['data' => Log::all()]);
  }
}
