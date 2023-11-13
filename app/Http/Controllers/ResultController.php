<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResultRequest;
use App\Http\Requests\UpdateResultRequest;
use App\Models\CA;
use App\Models\Result;
use App\Models\Student;
use App\Models\StudentCode;
use App\Models\StudentCodeMark;
use Illuminate\Http\Request;

/**
 * @group Results
 *
 * Result endpoints
 * */

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod|coordinator|lecturer|student|supportStaff']);
    }

    public function index()
    {
        // Get all results for all students and all courses
        $result = Result::all();
        return response($result);
    }

    public function store(StoreResultRequest $request)
    {
        $rules = (new StoreResultRequest())->rules();
        $validated = $request->validate($rules);

        // The following block of code, gets the student id, looks for the corresponding CA and gets the CA mark
        $student = Student::with('user')->findOrFail($validated['studentId']);
        $studentCA = CA::where('studentId', $student->id)->
        where('courseId', $validated['courseId'])
        ->get();

        // The following code gets the student code and matches it to the student exam mark and adds the exam mark to the ca mark obtained above
        $studentCode = StudentCode::where('studentId', $student->id)->
        where('courseId', $validated['courseId'])
        ->get();
         $studentExamMark = StudentCodeMark::where('studentCodeId', $studentCode->value('id'))->get();

        $finalMark = $studentCA->value('mark') + $studentExamMark->value('mark');
        switch (true) {
            case ($finalMark >= 80):
                $grade = 'A';
                $gradePoint = 4.0;
                break;
            case ($finalMark >= 70):
                $grade = 'B+';
                $gradePoint = 3.5;
                break;
            case ($finalMark >= 60):
                $grade = 'B';
                $gradePoint = 3.0;
                break;
            case ($finalMark >= 55):
                $grade = 'C+';
                $gradePoint = 2.5;
                break;
            case ($finalMark >= 50):
                $grade = 'C';
                $gradePoint = 2.0;
                break;
            case ($finalMark >= 45):
                $grade = 'D+';
                $gradePoint = 1.5;
                break;
            case ($finalMark >= 40):
                $grade = 'D';
                $gradePoint = 1.0;
                break;
            case ($finalMark >= 0):
                $grade = 'F';
                $gradePoint = 0.0;
                break;
        }

        $resultDetails = [
            'studentId' => $validated['studentId'],
            'semesterId' => $validated['semesterId'],
            'courseId' => $validated['courseId'],
            'mark' => $finalMark,
            'grade' => $grade,
            'gradePoint' => $gradePoint,
        ];

        $result = Result::create($resultDetails);
        return response($result);

    }

    public function show($id)
    {
        $results = Result::where('studentId', $id)->get();
        return response($results);
    }

    public function update(UpdateResultRequest $request, $id)
    {
        $validated = $request->validated();

        // The following block of code, gets the student id, looks for the corresponding CA and gets the CA mark
        $student = Student::findOrFail($validated['studentId']);
        $studentCA = CA::where('studentId', $student->id)->first();

        // The following code gets the student code and matches it to the student exam mark and adds the exam mark to the ca mark obtained above
        $studentCode = StudentCode::where('studentId', $student->id)->first();
        $studentExamMark = StudentCodeMark::where('studentCodeId', $studentCode->id)->first();

        $finalMark = $studentCA->mark + $studentExamMark->mark;

        $resultDetails = [
            'studentId' => $validated['studentId'],
            'semesterId' => $validated['semesterId'],
            'courseId' => $validated['courseId'],
            'mark' => $finalMark,
        ];

        $result = Result::findOrFail($id);
        $result->update($resultDetails);

        return response($result->fresh());
    }

    public function delete($id)
    {
        $result = Result::findOrFail($id);
        $result->delete();
        return response(['message' => 'Result code Deleted Successfully']);
    }
}
