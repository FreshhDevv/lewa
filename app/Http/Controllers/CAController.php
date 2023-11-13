<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBulkUploadCARequest;
use App\Http\Requests\StoreCARequest;
use App\Http\Requests\UpdateCARequest;
use App\Imports\CAsImport;
use App\Models\CA;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @group CA
 *
 * CA marks endpoints
 * */

class CAController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:coordinator|lecturer']);
    }

/**
* Get All CA marks.
* @header Authorization Bearer {token} required A valid Sanctum token.

* @authenticated
* @response 200 {
*    "id": 1,
*    "studentId": 1,
*    "courseId": 1,
*    "semesterId": 1,
*    "mark": 25,
*    "created_at": "2023-07-16T07:30:16.000000Z",
*    "updated_at": "2023-07-16T07:30:16.000000Z"
*  }
*
* @response 401 {
*      "message": "Unauthenticated."
* }
*
*
*/
    public function index()
    {
        $caMarks = CA::all();
        return response($caMarks);
    }

    public function store(StoreCARequest $request)
    {
        $rules = (new StoreCARequest())->rules();
        $validated = $request->validate($rules);
        $ca = CA::create([
            'studentId' => $validated['studentId'],
            'courseId' => $validated['courseId'],
            'semesterId' => $validated['semesterId'],
            'mark' => $validated['mark']
        ]);

        return response($ca);
    }

    public function show($id)
    {
        $caMark = CA::where('studentId', $id)->get();
        return response($caMark);
    }

    public function update(UpdateCARequest $request, $id)
    {
        $validated = $request->validated();
        $caDetails = [
            'studentId' => $validated['studentId'],
            'courseId' => $validated['courseId'],
            'semesterId' => $validated['semesterId'],
            'mark' => $validated['mark'],
        ];

        $caMark = CA::findOrFail($id);
        $caMark->update($caDetails);
        return response ($caMark->fresh());
    }

    public function delete($id)
    {
        $ca = CA::findOrFail($id);
        $ca->delete();
        return response(['message' => 'CA mark Deleted Successfully']);
    }

/**
* Bulk Upload CA marks.
* @header Authorization Bearer {token} required A valid Sanctum token.

* @authenticated
* @response 200 {
*    "studentId": 1,
*    "courseId": "1",
*    "semesterId": "2",
*    "mark": 23,
*    "created_at": "2023-07-19T03:19:40.350571Z",
*    "updated_at": "2023-07-19T03:19:40.350577Z"
*  }
*
* @response 401 {
*      "message": "Unauthenticated."
* }
*
*
*/
    public function bulkUpload(StoreBulkUploadCARequest $request)
    {
        $rules = (new StoreBulkUploadCARequest())->rules();
        $validated = $request->validate($rules);
        if (!$request->hasFile('yourFile')) {
            // Handle file not found error
            return response()->json(['message' => 'File not found'], 400);        }

        $file = $request->file('yourFile');

        if (!$file->isValid()) {
            // Handle file upload error
            return response()->json(['message' => 'File upload failed'], 400);        }

        $rows = Excel::toArray(new CAsImport, $file);
        // Get the list of matriculation numbers from the Excel file

        $matriculationNumbers = array_map(function ($row) {
            return $row[0];
        }, array_slice($rows[0], 1));
        // Get the corresponding student IDs from the database
        $students = Student::whereIn('matriculationNumber', $matriculationNumbers)->get();

        if ($students->isEmpty()) {
            return response()->json(['message' => 'No students found with the given matriculation numbers'], 400);
        }

        $studentMap = $students->pluck('id', 'matriculationNumber');

        // Iterate over each row and perform calculations
        $results = [];
        foreach (array_slice($rows[0], 1) as $row) {
            $matriculationNumber = $row[0];
            $mark = $row[1];

            if (!isset($studentMap[$matriculationNumber])) {
                // Log a message indicating that the record was skipped
                \Log::warning("Skipping record with matriculation number '$matriculationNumber' as it does not exist in the students table");
                // dd('skipping record');

                continue; // Skip this row and move on to the next one
            }

            // Get the student ID corresponding to the matriculation number
            $studentId = $studentMap[$matriculationNumber];

            // Perform calculations on $studentId and $mark here
            // ...

            // Add the calculated result to the $results array
            $results[] = [
                'studentId' => $studentId,
                'courseId' => $validated['courseId'],
                'semesterId' => $validated['semesterId'],
                'mark' => $mark,
                'created_at' => now(),
                'updated_at' => now(),
                // Add other calculated fields here
            ];
        }

        // Save the calculated results to the database
        CA::insert($results);
        return response($results);
    }
}
