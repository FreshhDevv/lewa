<?php

namespace App\Imports;

use App\Models\StudentCodeMark;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentExamMarkImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $examMark = new StudentCodeMark([
            "studentCode" => $row[0],
            "mark" => $row[1],
        ]);

        return $examMark;
    }
}
