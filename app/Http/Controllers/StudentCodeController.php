<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentCodeRequest;
use App\Http\Requests\UpdateStudentCodeRequest;
use App\Models\StudentCode;
use Illuminate\Http\Request;

/**
 * @group Student Code
 *
 * Student code endpoints
 * */

class StudentCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|supportStaff|lecturer']);
    }

    public function index()
    {
        $studentCodes = StudentCode::all();
        return response($studentCodes);
    }

    public function store(StoreStudentCodeRequest $request)
    {
        $rules = (new StoreStudentCodeRequest())->rules();
        $validated = $request->validate($rules);
        $studentCode = StudentCode::create([
            'studentId' => $validated['studentId'],
            'semesterId' => $validated['semesterId'],
            'courseId' => $validated['courseId'],
            'studentCode' => $validated['studentCode'],
        ]);

        return response($studentCode);
    }

    public function show($id)
    {
        $studentCode = StudentCode::findOrFail($id);
        return response($studentCode);
    }

    public function update(UpdateStudentCodeRequest $request, $id)
    {
        $validated = $request->validated();
        $studentCodeDetails = [
            'studentId' => $validated['studentId'],
            'semesterId' => $validated['semesterId'],
            'courseId' => $validated['courseId'],
            'studentCode' => $validated['studentCode'],
        ];

        $studentCode = StudentCode::findOrFail($id);
        $studentCode->update($studentCodeDetails);
        return response($studentCode->fresh());
    }

    public function delete($id)
    {
        $studentCode = StudentCode::findOrFail($id);
        $studentCode->delete();
        return response(['message' => 'Student code Deleted Successfully']);
    }
}
