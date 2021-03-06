@extends('layout')

@section('extra-scripts')
<script src="{{ asset('js/data.js') }}?v={{ filemtime(public_path('js/data.js')) }}"></script>
@endsection

@section('body')
@include('modal')
@include('navbar')
  <input type="hidden" name="filter" value="{{ $filter }}">
  @if(request()->query("s"))
    <input type="hidden" name="search" value="{{ request()->query("s") }}">
  @endif
  <div class="row">
    <div id="admin" class="col s12">
      <div class="card material-table">
        <div class="table-header">
          <span class="table-title">All Researches</span>
          <div class="actions">
            @if(Auth::user()->isSuperAdmin)
              {{-- <a title="Download as PDF" class="generate-pdf modal-trigger waves-effect btn-flat nopadding"><i class="material-icons left">insert_drive_file</i>PDF</a> --}}
              <a title="Download as Excel" class="generate-excel modal-trigger waves-effect btn-flat nopadding"><i class="material-icons left">assessment</i>Excel</a>
            @endif
            @if(Auth::user()->isAdmin)
              <a title="Add" href="#addModal" class="modal-trigger waves-effect btn-flat nopadding"><i class="material-icons">add</i></a>
            @endif
            <a title="Search" href="javascript:void(0)" class="search-toggle waves-effect btn-flat nopadding"><i class="material-icons">search</i></a>
          </div>
          <input type="file" name="uploadExcel" style="display:none">
          <form action="{{ url('pdf') }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="pdf_data">
          </form>
          <form action="{{ url('excel') }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="excel_data">
          </form>
        </div>
        <table id="datatable" data-filter="0,1,2">
          <thead>
            <tr>
              <th width="5%">College</th>
              <th>Title</th>
              <th width="20%">Authors</th>
              <th>Keywords</th>
              <th width="15%">Category</th>
              <th>Publisher</th>
              <th>Proceeding Date</th>
              <th>Presentation Date</th>
              <th>Publication Date</th>
              <th>Note</th>
              <th>Conference Name</th>
              <th>URL</th>
              <th width="10%">Actions</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <img src="{{ asset('img/logo/rnd.png') }}" style="float:right" alt="" height="40px">
    </div>
  </div>
@endsection
