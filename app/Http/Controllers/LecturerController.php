<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLecturerRequest;
use App\Http\Requests\UpdateLecturerRequest;
use App\Models\User;
use App\Models\UserFacultyDepartment;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * @group Lecturer Endpoints
 *
 *
 * API endpoints for lecturers
 */

class LecturerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod|coordinator']);
    }

    public function index()
    {
        $role = Role::where('name', 'lecturer')->first();

        // Get all users with the 'lecturer' role
        $lecturer = $role->users;
        return response($lecturer);
    }

    public function store(StoreLecturerRequest $request)
    {
        $rules = (new StoreLecturerRequest())->rules();
        $validated = $request->validate($rules);

        $lecturerDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'profilePicture' => NULL
        ];

        if (isset($validated['profilePicture'])) {
            $uploadedPath = $validated['profilePicture']->store('public/user-images');
            $lecturerDetails['profilePicture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        $lecturer = User::create($lecturerDetails)->assignRole(Role::where('name', 'lecturer')->first());

        UserFacultyDepartment::create([
            'userId' => $lecturer->id,
            'facultyId' => $validated['facultyId'],
            'departmentId' => $validated['departmentId'],
        ]);
        return response($lecturer);
    }

    public function show($id)
    {
        $lecturer = User::findOrFail($id);
        return response([
            'lecturer' => $lecturer,
            'role' => $lecturer->roles->first()->name,
        ]);
    }

    public function update(UpdateLecturerRequest $request, $id)
    {
        $validated = $request->validated();
        $lecturerDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ];

        if (isset($validated['profilePicture'])) {
            $uploadedPath = $validated['profilePicture']->store('public/user-images');
            $lecturerDetails['profilePicture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        if ($validated['password']) {
            $lecturerDetails['password'] = bcrypt($validated['password']);
        }

        $lecturer = User::findOrFail($id);
        $lecturer->update($lecturerDetails);
        $lecturerFaculty = UserFacultyDepartment::where('user_id', $lecturer->id)->first();

        $lecturerFaculty->userId = $lecturer->id;
        $lecturerFaculty->facultyId = $validated['facultyId'];
        $lecturerFaculty->departmentId = $validated['departmentId'];
        $lecturerFaculty->save();

        return response($lecturer->fresh());
    }

    public function delete($id)
    {
        $lecturer = User::findOrFail($id);
        $lecturer->delete();
        return response(['message' => 'Lecturer Deleted Successfully']);
    }
}
