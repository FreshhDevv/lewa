<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHodRequest;
use App\Http\Requests\UpdateHodRequest;
use App\Models\User;
use App\Models\UserFacultyDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

/**
 * @group HOD Endpoints
 *
 *
 * API endpoints for hod
 */

class HodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|dean']);
    }
    public function index()
    {
        $role = Role::where('name', 'hod')->first();

        // Get all users with the 'hod' role
        $hods = $role->users;
        return response($hods);
    }

    public function store(StoreHodRequest $request)
    {
        $rules = (new StoreHodRequest())->rules();
        $validated = $request->validate($rules);

        $hodDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'profilePicture' => NULL
        ];

        if (isset($validated['profilePicture'])) {
            $uploadedPath = $validated['profilePicture']->store('public/user-images');
            $hodDetails['profilePicture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        $hod = User::create($hodDetails)->assignRole(Role::where('name', 'hod')->first());

        UserFacultyDepartment::create([
            'userId' => $hod->id,
            'facultyId' => $validated['facultyId'],
            'departmentId' => $validated['departmentId'],
        ]);
        return response($hod);
    }

    public function show($id)
    {
        $hod = User::findOrFail($id);
        return response([
            'hod' => $hod,
            'role' => $hod->roles->first()->name,
        ]);
    }

    public function update(UpdateHodRequest $request, $id)
    {
        $validated = $request->validated();
        $hodDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ];

        if (isset($validated['profilePicture'])) {
            $uploadedPath = $validated['profilePicture']->store('public/user-images');
            $hodDetails['profilePicture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        if ($validated['password']) {
            $hodDetails['password'] = bcrypt($validated['password']);
        }

        $hod = User::findOrFail($id);
        $hod->update($hodDetails);
        $hodFaculty = UserFacultyDepartment::where('userId', $hod->id)->first();

        $hodFaculty->userId = $hod->id;
        $hodFaculty->facultyId = $validated['facultyId'];
        $hodFaculty->departmentId = $validated['departmentId'];
        $hodFaculty->save();

        return response($hod->fresh());
    }

    public function delete($id)
    {
        $hod = User::findOrFail($id);
        $hod->delete();
        return response(['message' => 'Hod Deleted Successfully']);
    }

}
