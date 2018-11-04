<title>Archiving System Data</title>
<style>
  th {
    text-align: center;
  }
  th, td {
    padding: 5px;
  }
</style>
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
        <td style="text-align:center">{{ $id }}</td>
        <td>{{ $row->title }}</td>
        <td>{{ $row->authors }}</td>
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
