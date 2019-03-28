<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Authors</th>
      <th>Keywords</th>
      <th>Category</th>
      <th>Publisher</th>
      <th>Proceeding Date</th>
      <th>Presentation Date</th>
      <th>Publication Date</th>
      <th>Note</th>
      <th>Conference Name</th>
      <th>Website / URL</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $id => $row)
      <tr>
        <td>{{ $id + 1 }}</td>
        <td>{{ $row->title }}</td>
        <td>{!! str_replace(";", "<br>", $row->authors) !!}</td>
        <td>{!! str_replace(";", "<br>", $row->keywords) !!}</td>
        <td>{!! str_replace(";", "<br>", $row->category) !!}</td>
        <td>{{ $row->publisher }}</td>
        <td>{{ $row->proceeding_date }}</td>
        <td>{{ $row->presentation_date }}</td>
        <td>{{ $row->publication_date }}</td>
        <td>{{ $row->note }}</td>
        <td>{{ $row->conference_name }}</td>
        <td>{{ $row->url }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
