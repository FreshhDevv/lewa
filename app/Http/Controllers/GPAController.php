<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGpaRequest;
use App\Models\GPA;
use App\Models\Result;
use App\Models\Semester;
use Illuminate\Http\Request;

/**
 * @group GPA Endpoints
 *
 *
 * API endpoints for GPA
 */

class GPAController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod|coordinator|lecturer|examiner|supportStaff']);
    }

/**
* Store semester Gpa.
* @header Authorization Bearer {token} required A valid Sanctum token.

* @authenticated
* @response 200 {
 * "semesterGpa": 2.9444444444444446,
*  "gpaDetails": {
*    "year": 2023,
*    "semesterId": 1,
*    "studentId": 1,
*    "gpa": 2.94,
*    "updated_at": "2023-07-17T22:46:27.000000Z",
*    "created_at": "2023-07-17T22:46:27.000000Z",
*    "id": 5
*  },
*  "gpaUserInfo": [
*    {
*      "id": 1,
*      "studentId": 1,
*      "semesterId": 1,
*      "courseId": 1,
*      "mark": 75,
*      "grade": "B+",
*      "gradePoint": "3.50",
*      "created_at": "2023-07-16T07:39:36.000000Z",
*      "updated_at": "2023-07-16T07:39:36.000000Z",
*      "course": {
*        "id": 1,
*        "facultyId": 1,
*        "userId": 4,
*        "semesterId": 1,
*        "name": "ANALYSIS",
*        "courseCode": "CEF 201",
*        "level": "200",
*        "status": "compulsory",
*        "creditValue": 4,
*        "created_at": "2023-07-16T07:25:15.000000Z",
*        "updated_at": "2023-07-16T07:25:15.000000Z"
*      },
*      "semester": {
*        "id": 1,
*        "semester": 1,
*        "year": 2023,
*        "created_at": "2023-07-16T07:25:15.000000Z",
*        "updated_at": "2023-07-16T07:25:15.000000Z"
*      }
*    }
*
* @response 401 {
*      "message": "Unauthenticated."
* }
*
*
*/
    public function index(StoreGpaRequest $request)
    {
        $allGpa = Result::with(['course', 'semester'])->get();

        $groupedGpa = $allGpa->groupBy('studentId');

        foreach ($groupedGpa as $studentId => $resultCollection) {
            $gp = $resultCollection->pluck('gradePoint');
            $cv = $resultCollection->pluck('course.creditValue');
            $totalGpTimesCv = $gp->map(function ($value, $key) use ($cv) {
                return $value * $cv[$key];
            })->sum();

            $totalCv = $cv->sum();
            $semesterGpa = $totalGpTimesCv / $totalCv;

            $semester = $resultCollection->first()->semester;

            $gpaDetails = [
                'year' => $semester->year,
                'semesterId' => $semester->id,
                'studentId' => $studentId,
                'gpa' => round($semesterGpa, 2),
            ];
            $gpa = GPA::create($gpaDetails);

            return response([
                "semesterGpa" => $semesterGpa,
                "gpaDetails" => $gpa,
                "gpaUserInfo" => $allGpa,
            ]);
        }
    }

    /**
* Store semester Gpa.
* @header Authorization Bearer {token} required A valid Sanctum token.

* @authenticated
* @response 200 {
 *  "cumulativeGpa": 3.08,
*  "studentSemesterGpas": {
*    "1|1|2023": {
*      "student": {
*        "id": 1,
*        "userId": 3,
*        "gender": "male",
*        "status": "single",
*        "dob": "1974-12-08",
*        "placeOfBirth": "Andyburgh",
*        "address": "Buea",
*        "phone": "580.555.3023",
*        "region": null,
*        "nationalIdentification": 123456789,
*        "country": "Cameroon",
*        "matriculationNumber": "FE19A102",
*        "level": "200",
*        "year": "1990",
*        "program": "software",
*        "certificateObtained": "A level",
*        "yearObtained": "2007",
*        "guardianFirstName": "Olivier",
*        "guardianLastName": "Twist",
*        "guardianEmail": "ankunding.freida@gmail.com",
*        "guardianAddress": "Virgilmouth",
*        "guardianPhone": "+17578547094",
*        "created_at": "2023-07-16T07:25:15.000000Z",
*        "updated_at": "2023-07-16T07:25:15.000000Z"
*      },
*      "semesterGpa": 2.94
*    },
*
* @response 401 {
*      "message": "Unauthenticated."
* }
*
*
*/
    public function cumulativeGpa()
    {
        // Retrieve all the GPAs for all the students
        $allGpa = GPA::with(['semester', 'student'])->get();

        // Group the GPAs by studentId semesterId, and year
        $groupedGpa = $allGpa->groupBy(function ($result) {
            $key = $result->studentId . '|' . $result->semester->id . '|' . $result->semester->year;
            return $key;
        });

        $averages = $groupedGpa->map(function ($group) {
            $sum = $group->sum('gpa');
            return $sum;
        })->sum();

        $cumulativeGpa = $averages / $groupedGpa->count();

        $studentSemesterGpas = $groupedGpa->map(function ($group) use ($cumulativeGpa) {
            $student = $group->first()->student;
            $cumulativeGpaForStudent = $group->sum('gpa') / $group->count();
            return [
                'student' => $student,
                'semesterGpa' => round($cumulativeGpaForStudent, 2),
            ];
        });

        return response([
            "cumulativeGpa" => round($cumulativeGpa, 2),
            "studentSemesterGpas" => $studentSemesterGpas,
        ]);
    }
}
