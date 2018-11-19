@extends('layout')

@section('extra-scripts')
<script src="{{ asset('public/js/data.js') }}?v={{ filemtime(public_path('js/data.js')) }}"></script>
@endsection

@section('body')
@include('modal')
@include('navbar')
  <input type="hidden" name="filter" value="{{ $filter }}">
  <div class="row">
    <div id="admin" class="col s12">
      <div class="card material-table">
        <div class="table-header">
          <span class="table-title">My Researches</span>
          <div class="actions">
            <a title="Add" href="#addModal" class="modal-trigger waves-effect btn-flat nopadding"><i class="material-icons">add</i></a>
            <a title="Search" href="javascript:void(0)" class="search-toggle waves-effect btn-flat nopadding"><i class="material-icons">search</i></a>
          </div>
          <input type="file" name="uploadExcel" style="display:none">
          <form action="{{ url('pdf') }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="data">
          </form>
        </div>
        <table id="datatable">
          <thead>
            <tr>
              <th width="5%">ID</th>
              <th>Title</th>
              <th>Authors</th>
              <th>Keywords</th>
              <th>Category</th>
              <th>Publisher</th>
              <th>Proceeding Date</th>
              <th>Presentation Date</th>
              <th>Publication Date</th>
              <th>Note</th>
              <th width="5%">Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <img src="{{ asset('public/img/logo/rnd.png') }}" style="float:right" alt="" height="40px">
    </div>
  </div>
@endsection
