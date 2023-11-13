<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseSummaryRequest;
use App\Http\Requests\StoreFrequencyRequest;
use App\Models\CA;
use App\Models\Course;
use App\Models\CourseSummary;
use Illuminate\Http\Request;

class FrequencyController extends Controller
{
    /**
     * @group Frequency
     *
     * API Frequency Sheet endpoints
     * */

    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod|coordinator']);
    }

    public function index()
    {
        if (!request()->get('semesterId')) {
            return response(['message' => 'Invalid semester id'], 400);
        }

        $courses = Course::where('semesterId', request()->get('semesterId'))->with(['user', 'results', 'courseSummary'])->get();


        $frequencyData = collect([]);
        $grades = ['A', 'B+', 'B', 'C+', 'C', 'D+', 'D', 'F'];


        foreach ($courses as $key => $course) {
            $row = [
                'sn' => $key + 1,
                'courseCode' => $course->courseCode,
                'courseTitle' => $course->name,
                'cv' => $course->creditValue,
                'st' => $course->status,
                'subjectMaster' => $course->user->firstName . ' ' . $course->user->lastName,

                // TODO START
                // if ce and cr table exists and has relationship $course->courseSummary, we do
                // CourseSummary (id, courseID, semesterId, cc, cr) as fields.

                'cc' => $course->courseSummary?->cc,
                'cr' => $course->courseSummary?->cr,
                // TODO END
            ];

            $results = $course->results;
            $resultCount = $results->count();


            foreach ($grades as $grade) {
                $count = $results->filter(function ($result) use ($grade) {
                    return $result->grade == $grade;
                })->count();
                $row[$grade] = $count;
            }

            $passCount = $results->filter(function ($result) {
                return $result->mark >= 50;
            })->count();

            // $failCount = $results->filter(function ($result) {
            //     return $result->mark < 50;
            // })->count();

            $failCount = $resultCount - $passCount;

            $row['passNumber'] = $passCount;
            $row['failNumber'] = $failCount;
            $row['percentagePass'] = ($passCount / $resultCount) * 100;
            $row['percentageFail'] = 100 - $row['percentagePass'];
            $row['ce'] = $resultCount;



            // $frequencyData[] = $row;
            $frequencyData->push($row);
        }

        return response($frequencyData);
    }

    public function courseSummary(StoreCourseSummaryRequest $request)
    {
        $rules = (new StoreCourseSummaryRequest())->rules();
        $validated = $request->validate($rules);

        $courseSummaryDetails = [
            'semesterId' => $validated['semesterId'],
            'cc' => $validated['cc'],
            'cr' => $validated['cr'],
            'courseId' => $validated['courseId']
        ];

        $courseSummary = CourseSummary::create($courseSummaryDetails);
        return response($courseSummary);

    }
}
