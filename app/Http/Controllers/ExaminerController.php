<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExaminerRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateExaminerRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Examiner;
use App\Models\User;
use App\Models\UserFacultyDepartment;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * @group Examiner Endpoints
 *
 *
 * API endpoints for examiners
 */

class ExaminerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod|coordinator|lecturer']);
    }

    public function index()
    {
        $role = Role::where('name', 'examiner')->first();

        // Get all users with the 'examiner' role
        $examiners = $role->users;
        return response($examiners);
    }

    public function store(StoreUserRequest $request)
    {
        $rules = (new StoreUserRequest())->rules();
        $rules = array_merge($rules, (new StoreExaminerRequest())->rules());

        $validated = $request->validate($rules);


        $userDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'profilePicture' => NULL
        ];

        if (isset($validated['profilePicture'])) {
            $uploadedPath = $validated['profilePicture']->store('public/user-images');
            $userDetails['profilePicture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        $userExaminer = User::create($userDetails)->assignRole(Role::where('name', 'examiner')->first());

        $examinerDetails = [
            'userId' => $userExaminer->id,
            'semesterId' => $validated['semesterId'],
        ];

        Examiner::create($examinerDetails);

        UserFacultyDepartment::create([
            'userId' => $userExaminer->id,
            'facultyId' => $validated['facultyId'],
            'departmentId' => $validated['departmentId'],
        ]);
        return response($userExaminer);
    }

    public function show($id)
    {
        $examiner = Examiner::findOrFail($id);
        return response([
            'examiner' => $examiner->user,
            'role' => $examiner->user->roles->first()->name,
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $rules = (new UpdateUserRequest())->rules();

        $rules = array_merge($rules, (new UpdateExaminerRequest())->rules());

        $validated = $request->validate($rules);

        $userDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'profilePicture' => NULL
        ];

        if (isset($validated['profilePicture'])) {
            $uploadedPath = $validated['profilePicture']->store('public/user-images');
            $userDetails['profilePicture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        $userExaminer = User::findOrFail($id);
        $userExaminer->update($userDetails);

        Examiner::where('userId', $userExaminer->id)->delete();

        $examinerDetails = [
            'userId' => $userExaminer->id,
            'semesterId' => $validated['semesterId'],
        ];

        Examiner::create($examinerDetails);

        return response($userExaminer->fresh());
    }

    public function delete($id)
    {
        $examiner = User::findOrFail($id);
        $examiner->delete();
        return response(['message' => 'Examiner Deleted Successfully']);
    }

}
