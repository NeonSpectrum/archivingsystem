<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DataImport implements ToCollection {
  /**
   * @param Collection $collection
   */
  public function collection(Collection $collection) {
    $collection = $collection->slice(1);

    foreach ($collection as $row) {
      try {
        $affectedRows = \DB::table('data')->insert([
          'title'             => $row[0],
          'authors'           => $row[1],
          'keywords'          => $row[2],
          'category'          => $row[3],
          'publisher'         => $row[4],
          'proceeding_date'   => $row[5],
          'presentation_date' => $row[6],
          'publication_date'  => $row[7],
          'note'              => $row[8]
        ]);
      } catch (QueryException $e) {
        return json_encode(['success' => false, 'error' => $e->getMessage()]);
      }
    }

    return json_encode(['success' => true]);
  }
}
