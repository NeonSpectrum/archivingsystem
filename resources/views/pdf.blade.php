<title>Archiving System Data</title>
<style>
  th {
    text-align: center;
  }
  th, td {
    padding: 5px;
  }
  h2 {
    margin: 0;
  }
</style>

<center>
  <img src="{{ asset('img/logo/ue.png') }}" alt="" height="65px" style="padding:5px 5px 0">
  @if(!Auth::user()->isSuperAdmin)
    <img src="{{ asset('img/logo/' . Auth::user()->role->logo) }}" alt="" height="65px" style="padding:5px 5px 0">
  @endif
  <h2>University of the East</h2>
  @if(!Auth::user()->isSuperAdmin)
    <small>{{ Auth::user()->memberRole->description }}</small>
  @endif
</center>
<br>
<table border="1" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th style="text-align:center">ID</th>
      <th>Title</th>
      <th>Authors</th>
      <th>Keywords</th>
      <th>Category</th>
      <th>Publisher</th>
      <th>Proceeding Date</th>
      <th>Presentation Date</th>
      <th>Publication Date</th>
      <th>Note</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $id => $row)
      <tr>
        <td style="text-align:center">{{ $id + 1 }}</td>
        <td>{!! $row->title !!}</td>
        <td>{!! $row->authors !!}</td>
        <td>{{ $row->keywords }}</td>
        <td>{{ $row->category }}</td>
        <td>{{ $row->publisher }}</td>
        <td>{{ $row->proceeding_date }}</td>
        <td>{{ $row->presentation_date }}</td>
        <td>{{ $row->publication_date }}</td>
        <td>{{ $row->note }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
<script type="text/php">
  if (isset($pdf)) {
    $font = $fontMetrics->getFont("Arial", "bold");
    $pdf->page_text(750, 545, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 9, array(0, 0, 0));
  }
</script>
