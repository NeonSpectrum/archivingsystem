@extends('layout')

@section('extra-scripts')
<script src="{{ asset('public/js/account.js') }}?v={{ filemtime(public_path('js/account.js')) }}"></script>
@endsection

@section('body')
@include('modal-account')
  <div class="center-align" style="padding-top:10px;width:100%">
    <img src="{{ asset('public/img/logo/ue.png') }}" alt="" height="80px">
    <img src="{{ asset('public/img/logo/' . Auth::user()->logo) }}" alt="" height="80px">
  </div>
  <div class="row">
    <div id="admin" class="col s12">
      <div class="card material-table">
        <div class="table-header">
          <span class="table-title">Accounts</span>
          <div class="actions">
            <a title="Add" href="#addAccountModal" class="modal-trigger waves-effect btn-flat nopadding"><i class="material-icons">add</i></a>
            <a title="Search" href="#" class="search-toggle waves-effect btn-flat nopadding"><i class="material-icons">search</i></a>
            <a title="Dashboard" href="{{ url('/') }}" class="waves-effect btn-flat nopadding"><i class="material-icons">dashboard</i></a>
            <a title="Logout" href="{{ url('logout') }}" class="waves-effect btn-flat nopadding" onclick="return confirm('Are you sure do you want to logout?')"><i class="material-icons">logout</i></a>
          </div>
        </div>
        <table id="datatable">
          <thead>
            <tr>
              <th width="5%">ID</th>
              <th>Username</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>College</th>
              <th width="5%">Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <img src="{{ asset('public/img/logo/rnd.png') }}" style="position:absolute;right:10px;bottom:10px" alt="" height="40px">
@endsection
