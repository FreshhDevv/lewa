<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoordinatorRequest;
use App\Http\Requests\UpdateCoordinatorRequest;
use App\Models\User;
use App\Models\UserFacultyDepartment;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * @group Coordinator Endpoints
 *
 * API endpoints of the coordinator
 */

class CoordinatorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod']);
    }

    public function index()
    {
        $role = Role::where('name', 'coordinator')->first();

        // Get all users with the 'coordinator' role
        $deans = $role->users;
        return response($deans);
    }

    public function store(StoreCoordinatorRequest $request)
    {
        $rules = (new StoreCoordinatorRequest())->rules();
        $validated = $request->validate($rules);

        $coordinatorDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'profilePicture' => NULL
        ];

        if (isset($validated['profilePicture'])) {
            $uploadedPath = $validated['profilePicture']->store('public/user-images');
            $coordinatorDetails['profilePicture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        $coordinator = User::create($coordinatorDetails)->assignRole(Role::where('name', 'coordinator')->first());

        UserFacultyDepartment::create([
            'userId' => $coordinator->id,
            'facultyId' => $validated['facultyId'],
            'departmentId' => $validated['departmentId'],
        ]);
        return response($coordinator);
    }

    public function show($id)
    {
        $hod = User::findOrFail($id);
        return response([
            'hod' => $hod,
            'role' => $hod->roles->first()->name,
        ]);
    }

    public function update(UpdateCoordinatorRequest $request, $id)
    {
        $validated = $request->validated();
        $coordinatorDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ];

        if (isset($validated['profilePicture'])) {
            $uploadedPath = $validated['profilePicture']->store('public/user-images');
            $coordinatorDetails['profilePicture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        if ($validated['password']) {
            $coordinatorDetails['password'] = bcrypt($validated['password']);
        }

        $coordinator = User::findOrFail($id);
        $coordinator->update($coordinatorDetails);
        $coordinatorFaculty = UserFacultyDepartment::where('userId', $coordinator->id)->first();

        $coordinatorFaculty->user_id = $coordinator->id;
        $coordinatorFaculty->faculty_id = $validated['facultyId'];
        $coordinatorFaculty->department_id = $validated['departmentId'];
        $coordinatorFaculty->save();

        return response($coordinator->fresh());
    }

    public function delete($id)
    {
        $coordinator = User::findOrFail($id);
        $coordinator->delete();
        return response(['message' => 'Coordinator Deleted Successfully']);
    }

}
