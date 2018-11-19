<?php

namespace App\Http\Controllers;

use App\Logs;
use Illuminate\Http\Request;

class LogsController extends Controller {
  protected function show() {
    return view('logs', ['data' => Logs::all()]);
  }
}
