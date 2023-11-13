<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportStaffRequest;
use App\Http\Requests\UpdateSupportStaffRequest;
use App\Models\User;
use App\Models\UserFacultyDepartment;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * @group Support Staff
 *
 * Support Staff marks endpoints
 * */

class SupportStaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod|coordinator']);
    }

    public function index()
    {
        $role = Role::where('name', 'support-staff')->first();

        // Get all users with the 'support-staff' role
        $supportStaff = $role->users;
        return response($supportStaff);
    }

    public function store(StoreSupportStaffRequest $request)
    {
        $rules = (new StoreSupportStaffRequest())->rules();
        $validated = $request->validate($rules);

        $supportStaffDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'profilePicture' => NULL
        ];

        if (isset($validated['profilePicture'])) {
            $uploadedPath = $validated['profilePicture']->store('public/user-images');
            $supportStaffDetails['profilePicture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        $supportStaff = User::create($supportStaffDetails)->assignRole(Role::where('name', 'supportStaff')->first());

        UserFacultyDepartment::create([
            'userId' => $supportStaff->id,
            'facultyId' => $validated['facultyId'],
            'departmentId' => $validated['departmentId'],
        ]);
        return response($supportStaff);
    }

    public function show($id)
    {
        $supportStaff = User::findOrFail($id);
        return response([
            'supportStaff' => $supportStaff,
            'role' => $supportStaff->roles->first()->name,
        ]);
    }

    public function update(UpdateSupportStaffRequest $request, $id)
    {
        $validated = $request->validated();
        $supportStaffDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ];

        if (isset($validated['profilePicture'])) {
            $uploadedPath = $validated['profilePicture']->store('public/user-images');
            $supportStaffDetails['profilePicture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        if ($validated['password']) {
            $supportStaffDetails['password'] = bcrypt($validated['password']);
        }

        $supportStaff = User::findOrFail($id);
        $supportStaff->update($supportStaffDetails);
        $supportStaffFaculty = UserFacultyDepartment::where('userId', $supportStaff->id)->first();

        $supportStaffFaculty->userId = $supportStaff->id;
        $supportStaffFaculty->facultyId = $validated['facultyId'];
        $supportStaffFaculty->departmentId = $validated['departmentId'];
        $supportStaffFaculty->save();

        return response($supportStaff->fresh());
    }

    public function delete($id)
    {
        $dean = User::findOrFail($id);
        $dean->delete();
        return response(['message' => 'Support Staff Deleted Successfully']);
    }

}
