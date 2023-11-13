<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Courses
 *
 *
 * API endpoints for courses
 */

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod|coordinator|lecturer|supportStaff']);
    }

/**
 * Retrieve the list of courses.
 * @header Authorization Bearer {token} required A valid Sanctum token.

 * @authenticated
 * @response 200 {
 *      "id": 1,
 *      "facultyId": 1,
 *      "userId": 4,
 *      "semesterId": 1,
 *      "name": "ANALYSIS",
 *      "courseCode": "CEF 201",
 *      "level": "200",
 *      "status": "compulsory",
 *      "creditValue": 4,
 *      "created_at": "2023-07-12T21:26:29.000000Z",
 *      "updated_at": "2023-07-12T21:26:29.000000Z",
 *      "lecturer": {
 *          "id": 4,
 *          "firstName": "Dr",
 *          "lastName": "Chrome",
 *          "email": "chrome@gmail.com",
 *          "profilePicture": null,
 *          "status": "active",
 *          "created_at": "2023-07-12T21:26:29.000000Z",
 *          "updated_at": "2023-07-12T21:26:29.000000Z"
 *      },
 *      "faculty": {
 *          "id": 1,
 *          "name": "Faculty of Engineering",
 *          "created_at": "2023-07-12T21:26:29.000000Z",
 *          "updated_at": "2023-07-12T21:26:29.000000Z"
 *      }
 * }
 *
 * @response 401 {
 *      "message": "Unauthenticated."
 * }
 *
 *
 */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $courses = Course::with('user')->get();
        } else {

            $facultyId = $user->faculties()->pluck('faculties.id');
            $courses = Course::where('facultyId', $facultyId)->with(['user', 'faculty'])->get();
        }
        return response($courses);
    }

    public function store(StoreCourseRequest $request)
    {
        $user = Auth::user();
        $rules = (new StoreCourseRequest())->rules();
        $validated = $request->validate($rules);

        if($user->hasRole('admin')) {
        $faculty = Course::create([
            'facultyId' => $validated['facultyId'],
            'userId' => $validated['userId'],   // Teh userId here is for the lecturer
            'semesterId' => $validated['semesterId'],
            'name' => $validated['name'],
            'courseCode' => $validated['courseCode'],
            'creditValue' => $validated['creditValue'],
            'level' => $validated['level'],
            'status' => $validated['status'],
        ]);
    } else {
            $faculty = Course::create([
                'facultyId' => $user->facultyId,
                'userId' => $validated['userId'],
                'semesterId' => $validated['semesterId'],
                'name' => $validated['name'],
                'courseCode' => $validated['courseCode'],
                'creditValue' => $validated['creditValue'],
                'level' => $validated['level'],
                'status' => $validated['status'],
            ]);
    }

        return response($faculty);
    }

/**
 * Display the specified course.
 *
 * @urlParam id integer required The ID of the course to display.

 * @header Authorization Bearer {token} required A valid Sanctum token.

 * @authenticated

 * @response 200 {
 *      "id": 1,
 *      "facultyId": 1,
 *      "userId": 4,
 *      "semesterId": 1,
 *      "name": "ANALYSIS",
 *      "courseCode": "CEF 201",
 *      "level": "200",
 *      "status": "compulsory",
 *      "creditValue": 4,
 *      "created_at": "2023-07-12T21:26:29.000000Z",
 *      "updated_at": "2023-07-12T21:26:29.000000Z",
 *      "user": {
 *          "id": 4,
 *          "firstName": "Dr",
 *          "lastName": "Chrome",
 *          "email": "chrome@gmail.com",
 *          "profilePicture": null,
 *          "status": "active",
 *          "created_at": "2023-07-12T21:26:29.000000Z",
 *          "updated_at": "2023-07-12T21:26:29.000000Z"
 *      },
 *      "faculty": {
 *          "id": 1,
 *          "name": "Faculty of Engineering",
 *          "created_at": "2023-07-12T21:26:29.000000Z",
 *          "updated_at": "2023-07-12T21:26:29.000000Z"
 *      }
 * }
 *
 * @response 401 {
 *      "message": "Unauthenticated."
 * } */
    public function show($id)
    {
        $courses = Course::with(['user', 'faculty'])->findOrFail($id);
        return response($courses);
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        $validated = $request->validated();
        $courseDetails = [
            'facultyId' => $validated['facultyId'],
            'userId' => $validated['userId'],   // Teh userId here is for the lecturer
            'semesterId' => $validated['semesterId'],
            'name' => $validated['name'],
            'courseCode' => $validated['courseCode'],
            'creditValue' => $validated['creditValue'],
            'level' => $validated['level'],
            'status' => $validated['status'],
        ];
        $course = Course::findOrFail($id);
        $course->update($courseDetails);
        return response($course->fresh());
    }


    /**
     *
     * Delete a course by ID.
     *
     * @header Authorization Bearer {token} required A valid Sanctum token.
     * @urlParam id integer required The ID of the course to delete.
     *
     * @authenticated
     *
     * @response {
     *   "message": "Course Deleted Successfully"
     * }
     */
    public function delete($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response(['message' => 'Course Deleted Successfully']);
    }
}
