@extends('layout')

@section('extra-scripts')
<script>loadDatatable()</script>
@endsection

@section('body')
@include('navbar')
  <div class="row">
    <div id="admin" class="col s12">
      <div class="card material-table">
        <div class="table-header">
          <span class="table-title">Logs</span>
          <div class="actions">
            <a title="Search" href="#" class="search-toggle waves-effect btn-flat nopadding"><i class="material-icons">search</i></a>
          </div>
        </div>
        <table id="datatable">
          <thead>
            <tr>
              <th width="5%">ID</th>
              <th>Username</th>
              <th>Action</th>
              <th>Timestamp</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $id => $row)
              <tr>
                <td>{{ $id + 1 }}</td>
                <td>{{ $row->username }}</td>
                <td>{{ $row->action }}</td>
                <td>{{ date("F d, Y h:i:s A", strtotime($row->created_at)) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <img src="{{ asset('public/img/logo/rnd.png') }}" style="float:right" alt="" height="40px">
    </div>
  </div>
@endsection
