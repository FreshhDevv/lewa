<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentCourseRequest;
use App\Http\Requests\UpdateStudentCourseRequest;
use App\Models\Student;
use App\Models\StudentCourse;
use Illuminate\Http\Request;

/**
 * @group Student Courses Endpoints
 *
 *
 * API endpoints for student courses
 */

class StudentCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod|coordinator|lecturer']);
    }

    public function index($studentId)
    {
        $student = Student::findOrFail($studentId);
        $courses = $student->courses;

        return response(['courses' => $courses]);
    }

    public function store(StoreStudentCourseRequest $request, $studentId)
    {
        $rules = (new StoreStudentCourseRequest())->rules();
        $validated = $request->validate($rules);

        $studentCourseDetails = [
            'courseId' => $validated['courseId'],
            'semesterId' => $validated['semesterId'],
            'created_at' => now(),
            'updated_at' => now()
        ];

        $student = Student::findOrFail($studentId);
        $student->courses()->attach($student->id, $studentCourseDetails);
        return response(['message' => 'Course added successfully']);
    }

    public function update(UpdateStudentCourseRequest $request, $studentId)
    {
        $rules = (new UpdateStudentCourseRequest())->rules();
        $request->validate($rules);

        $studentCourseDetails = [
            'updated_at' => now(),
        ];

        $student = Student::findOrFail($studentId);
        $student->courses()->update($studentCourseDetails);

        return response(['message' => 'Course Updated successfully']);
    }

    public function delete($id)
    {
        $studentCourse = StudentCourse::findOrFail($id);
        $studentCourse->delete();
        return response(['message' => 'Student Course Deleted Successfully']);
    }
}
