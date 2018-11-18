@extends('layout')

@section('extra-scripts')
<script src="{{ asset('public/js/data.js') }}?v={{ filemtime(public_path('js/data.js')) }}"></script>
@endsection

@section('body')
@include('modal')
  <div class="center-align" style="padding-top:10px;width:100%">
    <img src="{{ asset('public/img/logo/ue.png') }}" alt="" height="80px">
    <img src="{{ asset('public/img/logo/' . Auth::user()->logo) }}" alt="" height="80px">
  </div>
  <div class="row">
    <div id="admin" class="col s12">
      <div class="card material-table">
        <div class="table-header">
          <span class="table-title">Archiving System</span>
          <div class="actions">
            @if(Auth::user()->role == "admin")
              <a title="Download as PDF" class="generate-pdf modal-trigger waves-effect btn-flat nopadding"><i class="material-icons">file_download</i></a>
              <a title="Upload from Excel" href="#" class="btnUpload modal-trigger waves-effect btn-flat nopadding"><i class="material-icons">file_upload</i></a>
            @endif
            <a title="Add" href="#addModal" class="modal-trigger waves-effect btn-flat nopadding"><i class="material-icons">add</i></a>
            <a title="Search" href="#" class="search-toggle waves-effect btn-flat nopadding"><i class="material-icons">search</i></a>
            @if(Auth::user()->role == "admin")
              <a title="Accounts" href="{{ url('accounts') }}" class="modal-trigger waves-effect btn-flat nopadding"><i class="material-icons">person</i></a>
            @endif
            <a title="Logout" href="{{ url('logout') }}" class="waves-effect btn-flat nopadding" onclick="return confirm('Are you sure do you want to logout?')"><i class="material-icons">logout</i></a>
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
