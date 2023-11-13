<?php

namespace App\Imports;

use App\Models\CA;
use Maatwebsite\Excel\Concerns\ToModel;

class CAsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $cas = new CA([
            "matriculationNumber" => $row[0],
            "mark" => $row[1],
        ]);

        return $cas;
    }
}
