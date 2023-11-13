<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Department Endpoints
 *
 *
 * API endpoints for departments
 */

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    public function index()
    {

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $departments = Department::all();
        } else {

            $facultyId = $user->facultyId;
            $departments = Department::where('facultyId', $facultyId)->get();
        }
        return response($departments);
    }

    public function store(StoreDepartmentRequest $request)
    {
        $rules = (new StoreDepartmentRequest())->rules();
        $validated = $request->validate($rules);

        $department = Department::create([
            'facultyId' => $validated['facultyId'],
            'name' => $validated['name'],
            'slug' => $validated['slug']
        ]);

        return response($department);
    }

    public function show($id)
    {
        $department = Department::findOrFail($id);
        return response($department);
    }

    public function update(UpdateDepartmentRequest $request, $id)
    {
        $validated = $request->validated();
        $departmentDetails = [
            'facultyId' => $validated['facultyId'],
            'name' => $validated['name'],
            'slug' => $validated['slug']
        ];

        $department = Department::findOrFail($id);
        $department->update($departmentDetails);
        return response($department->fresh());
    }

    public function delete($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return response(['message' => 'Department Deleted Successfully']);
    }
}
