<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeanRequest;
use App\Http\Requests\UpdateDeanRequest;
use App\Models\User;
use App\Models\UserFacultyDepartment;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * @group Dean Endpoints
 *
 *
 * API endpoints for dean
 */

class DeanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    public function index()
    {
        $role = Role::where('name', 'dean')->first();

        // Get all users with the 'dean' role
        $deans = $role->users;
        return response($deans);
    }

    public function store(StoreDeanRequest $request)
    {
        $rules = (new StoreDeanRequest())->rules();
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

        $dean = User::create($userDetails)->assignRole(Role::where('name', 'dean')->first());

        UserFacultyDepartment::create([
            'userId' => $dean->id,
            'facultyId' => $validated['facultyId'],
        ]);
        return response($dean);
    }

    public function show($id)
    {
        $dean = User::findOrFail($id);
        return response($dean);
    }

    public function update(UpdateDeanRequest $request, $id)
    {
        $validated = $request->validated();
        $deanDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ];

        if (isset($validated['profilePicture'])) {
            $uploadedPath = $validated['profilePicture']->store('public/user-images');
            $deanDetails['profilePicture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        if ($validated['password']) {
            $deanDetails['password'] = bcrypt($validated['password']);
        }

        $dean = User::findOrFail($id);
        $dean->update($deanDetails);
        $deanFaculty = UserFacultyDepartment::where('userId', $dean->id)->first();

        $deanFaculty->userId = $dean->id;
        $deanFaculty->facultyId = $validated['facultyId'];
        $deanFaculty->save();

        return response($dean->fresh());
    }

    public function delete($id)
    {
        $dean = User::findOrFail($id);
        $dean->delete();
        return response(['message' => 'Dean Deleted Successfully']);
    }
}
