<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFacultyRequest;
use App\Http\Requests\UpdateFacultyRequest;
use App\Models\Faculty;

/**
 * @group Admin Activities
 *
 * API endpoints of admin main functions
 * */

class AdminController extends Controller
{

    //The constructor below makes sure that only users with role of 'admin' will access any functions in this controller.
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    /**
     * Display faculties
     *
     * Gets a list of all faculties in the school
     */
    public function index()
    {
        $faculty = Faculty::all();
        return response($faculty);
    }

    public function store(StoreFacultyRequest $request)
    {
        $rules = (new StoreFacultyRequest())->rules();
        $validated = $request->validate($rules);

        $faculty = Faculty::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'slug' => strtoupper($validated['slug']),
            'about' => ucfirst($validated['about']),
        ]);

        return response($faculty);
    }

    public function show($id)
    {
        $faculty = Faculty::findOrFail($id);
        return response($faculty);
    }

    public function update(UpdateFacultyRequest $request, $id)
    {
        $validated = $request->validated();
        $facultyDetails = [
            'name' => $validated['name'],
            'code' => $validated['code'],
            'slug' => $validated['slug'],
            'about' => $validated['about'],
        ];

        $faculty = Faculty::findOrFail($id);
        $faculty->update($facultyDetails);
        return response($faculty->fresh());
    }

    public function delete($id)
    {
        $faculty = Faculty::findOrFail($id);
        $faculty->delete();
        return response(['message' => 'Faculty Deleted Successfully']);
    }
}
