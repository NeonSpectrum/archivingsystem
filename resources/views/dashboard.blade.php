@include('header')
@include('modal')
<div class="row">
  <div id="admin" class="col s12">
    <div class="card material-table">
      <div class="center-align" style="padding-top:10px;width:100%">
        <img src="{{ asset('public/img/rnd.png') }}" alt="" height="80px">
        <img src="{{ asset('public/img/ue_thumb.png') }}" alt="" height="80px">
        <img src="{{ asset('public/img/ccss_thumb.png') }}" alt="" height="80px">
      </div>
      <div class="table-header">
        <span class="table-title">Archiving System</span>
        <div class="actions">
          <a href="#" class="btnUpload modal-trigger waves-effect btn-flat nopadding"><i class="material-icons">file_upload</i></a>
          <a href="#addModal" class="modal-trigger waves-effect btn-flat nopadding"><i class="material-icons">add</i></a>
          <a href="#" class="search-toggle waves-effect btn-flat nopadding"><i class="material-icons">search</i></a>
        </div>
        <input type="file" name="uploadExcel" style="display:none">
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
            <th width="5%">Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>
@include('footer')
