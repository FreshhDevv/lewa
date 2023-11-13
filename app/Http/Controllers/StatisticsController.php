<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod|coordinator|lecturer']);
    }

    /**
     * @group Statistics
     *
     * Retrieve the number of courses, users, lecturers and students
     *
     * @header Authorization Bearer {token} required A valid Sanctum token.
     *
     * @response {
     *
     *   "lecturers": 1,
     *   "students": 1,
     *   "users": 6,
     *   "courses": 21
     * }
     *
     */
    public function index()
    {
        $usersWithLecturerRoleCount = User::whereHas('roles', function ($query) {
            $query->where('name', 'lecturer');
        })->count();

        $usersWithStudentRoleCount = User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })->count();

        $users = User::get()->count();
        $courses = Course::where('facultyId', Auth::user()->faculties->value('id'))->get()->count();

        // Return the counts as a response
        return response()->json([
            'lecturers' => $usersWithLecturerRoleCount,
            'students' => $usersWithStudentRoleCount,
            'users' => $users,
            'courses' => $courses,
        ]);
    }
}
