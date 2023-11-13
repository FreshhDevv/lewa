<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBulkUploadExamRequest;
use App\Http\Requests\StoreStudentCodeMarkRequest;
use App\Http\Requests\UpdateStudentCodeMarkRequest;
use App\Imports\StudentExamMarkImport;
use App\Models\StudentCode;
use App\Models\StudentCodeMark;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * @group Student code marks
 *
 * API Student code marks endpoints
 * */

class StudentCodeMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:coordinator|lecturer']);
    }

    public function index()
    {
        $studentCodeMarks = StudentCodeMark::all();
        return response($studentCodeMarks);
    }

    public function store(StoreStudentCodeMarkRequest $request)
    {
        $rules = (new StoreStudentCodeMarkRequest())->rules();
        $validated = $request->validate($rules);
        $studentCodeMark = StudentCodeMark::create([
            'studentCodeId' => $validated['studentCodeId'],
            'mark' => $validated['mark'],
        ]);
        // dd($studentCodeMark);

        return response($studentCodeMark);
    }

    public function show($id)
    {
        $studentCodeMark = StudentCodeMark::findOrFail($id);
        return response($studentCodeMark);
    }

    public function update(UpdateStudentCodeMarkRequest $request, $id)
    {
        $validated = $request->validated();
        $studentCodeMarkDetails = [
            'studentCodeId' => $validated['studentCodeId'],
            'mark' => $validated['mark'],
        ];

        $studentCodeMark = StudentCodeMark::findOrFail($id);
        $studentCodeMark->update($studentCodeMarkDetails);
        return response($studentCodeMark->fresh());
    }

    public function delete($id)
    {
        $studentCodeMark = StudentCodeMark::findOrFail($id);
        $studentCodeMark->delete();
        return response(['message' => 'Student code mark Deleted Successfully']);
    }

    /**
* Bulk Upload Exam marks.
* @header Authorization Bearer {token} required A valid Sanctum token.

* @authenticated
* @response 200 {
*    "studentCodeId": 1,
*    "mark": 50,
*    "created_at": "2023-07-19T05:21:11.840620Z",
*    "updated_at": "2023-07-19T05:21:11.840627Z"
*  }
*
* @response 401 {
*      "message": "Unauthenticated."
* }
*
*
*/
    public function bulkUpload(Request $request)
    {
        // $rules = (new StoreBulkUploadExamRequest())->rules();
        // $validated = $request->validate($rules);
        if (!$request->hasFile('yourFile')) {
            // Handle file not found error
            return response()->json(['message' => 'File not found'], 400);
        }

        $file = $request->file('yourFile');

        if (!$file->isValid()) {
            // Handle file upload error
            return response()->json(['message' => 'File upload failed'], 400);
        }

        $rows = Excel::toArray(new StudentExamMarkImport, $file);
        // Get the list of matriculation numbers from the Excel file

        $studentCodes = array_map(function ($row) {
            return $row[0];
        }, array_slice($rows[0], 1));
        // Get the corresponding student IDs from the database
        $codes = StudentCode::whereIn('studentCode', $studentCodes)->get();

        if ($codes->isEmpty()) {
            return response()->json(['message' => 'No students found with the given codes'], 400);
        }

        $studentMap = $codes->pluck('id', 'studentCode');

        // Iterate over each row and perform calculations
        $results = [];
        foreach (array_slice($rows[0], 1) as $row) {
            $studentCode = $row[0];
            $mark = $row[1];

            if (!isset($studentMap[$studentCode])) {
                // Log a message indicating that the record was skipped
                \Log::warning("Skipping record with student code '$studentCode' as it does not exist in the student code table");
                // dd('skipping record');

                continue; // Skip this row and move on to the next one
            }

            // Get the student ID corresponding to the matriculation number
            $studentCodeId = $studentMap[$studentCode];

            // Perform calculations on $studentId and $mark here
            // ...

            // Add the calculated result to the $results array
            $results[] = [
                'studentCodeId' => $studentCodeId,
                'mark' => $mark,
                'created_at' => now(),
                'updated_at' => now(),
                // Add other calculated fields here
            ];
        }

        // Save the calculated results to the database
        StudentCodeMark::insert($results);
        return response($results);
    }
}
