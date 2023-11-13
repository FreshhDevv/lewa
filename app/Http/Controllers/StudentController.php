<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Student;
use App\Models\User;
use App\Models\UserFacultyDepartment;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;


/**
 * @group Student Endpoints
 *
 *
 * API endpoints for students
 */

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod|coordinator|lecturer|examiner|supportStaff']);
    }

/**
 *
 * Get all students.
 *
 *
 * @header Authorization Bearer {token} required A valid Sanctum token.
 *
 * @response {
 *     "students": [
 *       {
 *         "id": 1,
 *         "userId": 3,
 *         "gender": "male",
 *         "status": "single",
 *         "dob": "1985-02-02",
 *         "placeOfBirth": "Lake Alberto",
 *         "address": "Buea",
 *         "phone": "+1.720.344.2464",
 *         "region": null,
 *         "nationalIdentification": 123456789,
 *         "country": "Cameroon",
 *         "matriculationNumber": "FE19A102",
 *         "level": "200",
 *         "year": "1970",
 *         "program": "software",
 *         "certificateObtained": "A level",
 *         "yearObtained": "2018",
 *         "guardianFirstName": "Olivier",
 *         "guardianLastName": "Twist",
 *         "guardianEmail": "anabelle.hand@cartwright.net",
 *         "guardianAddress": "North Orion",
 *         "guardianPhone": "+1-484-369-2847",
 *         "created_at": "2023-07-11T12:20:36.000000Z",
 *         "updated_at": "2023-07-11T12:20:36.000000Z",
 *         "user": {
 *           "id": 3,
 *           "firstName": "Billy",
 *           "lastName": "Hans",
 *           "email": "billyhans@gmail.com",
 *           "profilePicture": null,
 *           "status": "active",
 *           "created_at": "2023-07-11T12:20:36.000000Z",
 *           "updated_at": "2023-07-11T12:20:36.000000Z",
 *           "faculties": [
 *              {
 *                "id": 1,
 *                "name": "Faculty of Engineering and Technology",
 *                "code": 516,
 *                "slug": "FET",
 *                "about": "Qui omnis et quibusdam. Vel eos odio aspernatur. Sunt sunt sed beatae earum molestiae.",
 *                "created_at": "2023-07-11T12:20:36.000000Z",
 *                "updated_at": "2023-07-11T12:20:36.000000Z",
 *                "pivot": {
 *                  "userId": 3,
 *                  "facultyId": 1
 *                 }
 *               }
 *             ],
 *            "departments": [
 *              {
 *                "id": 1,
 *                "facultyId": 1,
 *                "name": "Computer Engineering",
 *                "slug": "ce",
 *                "created_at": "2023-07-11T12:20:36.000000Z",
 *                "updated_at": "2023-07-11T12:20:36.000000Z",
 *                "pivot": {
 *                "userId": 3,
 *                "departmentId": 1
 *                }
 *              }
 *            ]
 *          }
 *        }
 *     ]
 * }
 */

    public function index()
    {
        // Get all students with their associated user information
        $students = Student::with(['user', 'user.faculties', 'user.departments'])->get();
        // dd($students);

        // Return a response with the students and user information
        return response(['students' => $students]);
    }

    /**
     *
     * Store a new student.
     *
     * @header Authorization Bearer {token} required A valid Sanctum token.
     * @bodyParam firstName string required The first name of the student.
     * @bodyParam lastName string required The last name of the student.
     * @bodyParam email string required The email address of the student.
     * @bodyParam profile_picture file A profile picture for the student.
     * @bodyParam gender string required The gender of the student.
     * @bodyParam status string required The status of the student.
     * @bodyParam dob date required The date of birth of the student.
     * @bodyParam placeOfBirth string required The place of birth of the student.
     * @bodyParam address string required The address of the student.
     * @bodyParam phone string required The phone number of the student.
     * @bodyParam region string nullable The region of the student.
     * @bodyParam nationalIdentification string required The national identification number of the student.
     * @bodyParam country string required The country of the student.
     * @bodyParam matriculationNumber string required The matriculation number of the student.
     * @bodyParam level string required The level of the student.
     * @bodyParam year integer required The year of the student.
     * @bodyParam program string required The program of the student.
     * @bodyParam certificateObtained string required The certificate obtained by the student.
     * @bodyParam yearObtained integer required The year the certificate was obtained by the student.
     * @bodyParam guardianFirstName string required The first name of the student's guardian.
     * @bodyParam guardianLastName string required The last name of the student's guardian.
     * @bodyParam guardianEmail string required The email address of the student's guardian.
     * @bodyParam guardianAddress string required The address of the student's guardian.
     * @bodyParam guardianPhone string required The phone number of the student's guardian.
     * @bodyParam facultyId integer required The ID of the student's faculty.
     * @bodyParam departmentId integer required The ID of the student's department.
     *
     * @response {
     *   "id": 1,
     *   "firstName": "John",
     *   "lastName": "Doe",
     *   "email": "johndoe@example.com",
     *   "profilePicture": "http://example.com/profile.jpg",
     *   "created_at": "2023-07-10T12:00:00Z",
     *   "updated_at": "2023-07-10T12:00:00Z"
     * }
     */
    public function store(StoreUserRequest $request)
    {
        $rules = (new StoreUserRequest())->rules();
        $rules = array_merge($rules, (new StoreStudentRequest())->rules());

        $validated = $request->validate($rules);

        $userDetails = [
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'profilePicture' => NULL
        ];

        if (isset($validated['profile_picture'])) {
            $uploadedPath = $validated['profile_picture']->store('public/user-images');
            $userDetails['profile_picture'] = asset('storage' . str_replace('public', '', $uploadedPath));
        }

        $userStudent = User::create($userDetails)->assignRole(Role::where('name', 'student')->first());

        $studentDetails = [
            'userId' => $userStudent->id,
            'gender' => $validated['gender'],
            'status' => $validated['status'],
            'dob' => $validated['dob'],
            'placeOfBirth' => $validated['placeOfBirth'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'region' => $validated['region'],
            'nationalIdentification' => $validated['nationalIdentification'],
            'country' => $validated['country'],
            'matriculationNumber' => $validated['matriculationNumber'],
            'level' => $validated['level'],
            'year' => $validated['year'],
            'program' => $validated['program'],
            'certificateObtained' => $validated['certificateObtained'],
            'yearObtained' => $validated['yearObtained'],
            'guardianFirstName' => $validated['guardianFirstName'],
            'guardianLastName' => $validated['guardianLastName'],
            'guardianEmail' => $validated['guardianEmail'],
            'guardianAddress' => $validated['guardianAddress'],
            'guardianPhone' => $validated['guardianPhone'],
        ];

        Student::create($studentDetails);
        UserFacultyDepartment::create([
            'userId' => $userStudent->id,
            'facultyId' => $validated['facultyId'],
            'departmentId' => $validated['departmentId'],
        ]);

        return response($userStudent);
    }



    /**
     * Display the specified student with their associated user information, faculties, and departments.
     *
     * @urlParam id required The ID of the student to retrieve.
     * @header Authorization Bearer {token} required A valid Sanctum token.

     * @authenticated
     *
     * @response {
*          "student": [
     *         {
     *             "id": 1,
     *             "userId": 3,
     *              "gender": "male",
     *              "status": "single",
     *              "dob": "1985-02-02",
     *              "placeOfBirth": "Lake Alberto",
     *              "address": "Buea",
     *              "phone": "+1.720.344.2464",
     *              "region": null,
     *              "nationalIdentification": 123456789,
     *              "country": "Cameroon",
     *              "matriculationNumber": "FE19A102",
     *              "level": "200",
     *              "year": "1970",
     *              "program": "software",
     *              "certificateObtained": "A level",
     *              "yearObtained": "2018",
     *              "guardianFirstName": "Olivier",
     *              "guardianLastName": "Twist",
     *              "guardianEmail": "anabelle.hand@cartwright.net",
     *              "guardianAddress": "North Orion",
     *              "guardianPhone": "+1-484-369-2847",
     *              "created_at": "2023-07-11T12:20:36.000000Z",
     *              "updated_at": "2023-07-11T12:20:36.000000Z",
     *              "user": {
     *                  "id": 3,
     *                  "firstName": "Billy",
     *                  "lastName": "Hans",
     *                  "email": "billyhans@gmail.com",
     *                  "profilePicture": null,
     *                  "status": "active",
     *                  "created_at": "2023-07-11T12:20:36.000000Z",
     *                  "updated_at": "2023-07-11T12:20:36.000000Z",
     *              "faculties": [
     *                {
     *                  "id": 1,
     *                  "name": "Faculty of Engineering and Technology",
     *                  "code": 516,
     *                  "slug": "FET",
     *                  "about": "Qui omnis et quibusdam. Vel eos odio aspernatur. Sunt sunt sed beatae earum molestiae.",
     *                  "created_at": "2023-07-11T12:20:36.000000Z",
     *                  "updated_at": "2023-07-11T12:20:36.000000Z",
     *                  "pivot": {
     *                    "userId": 3,
     *                    "facultyId": 1
     *                  }
     *                }
     *              ],
     *              "departments": [
     *              {
     *                  "id": 1,
     *                  "facultyId": 1,
     *                  "name": "Computer Engineering",
     *                  "slug": "ce",
     *                  "created_at": "2023-07-11T12:20:36.000000Z",
     *                  "updated_at": "2023-07-11T12:20:36.000000Z",
     *                  "pivot": {
     *                  "userId": 3,
     *                  "departmentId": 1
     *                  }
     *              }
     *            ]
     *          }
     *        }
     *     ]
     *   },
     *   "role": "student"
     * }
     * @response 404 {
     *   "message": "The specified student does not exist."
     * }
     */

    public function show($id)
    {
        $student = Student::with(['user', 'user.faculties', 'user.departments'])->findOrFail($id);
        // dd($student->user);
        return response([
            'student' => $student,
            'role' => $student->user->roles->first()->name,
        ]);
    }

    /**
     *
     * Update student.
     *
     * @header Authorization Bearer {token} required A valid Sanctum token.
     * @bodyParam firstName string required The first name of the student.
     * @bodyParam lastName string required The last name of the student.
     * @bodyParam email string required The email address of the student.
     * @bodyParam profile_picture file A profile picture for the student.
     * @bodyParam gender string required The gender of the student.
     * @bodyParam status string required The status of the student.
     * @bodyParam dob date required The date of birth of the student.
     * @bodyParam placeOfBirth string required The place of birth of the student.
     * @bodyParam address string required The address of the student.
     * @bodyParam phone string required The phone number of the student.
     * @bodyParam region string nullable The region of the student.
     * @bodyParam nationalIdentification string required The national identification number of the student.
     * @bodyParam country string required The country of the student.
     * @bodyParam matriculationNumber string required The matriculation number of the student.
     * @bodyParam level string required The level of the student.
     * @bodyParam year integer required The year of the student.
     * @bodyParam program string required The program of the student.
     * @bodyParam certificateObtained string required The certificate obtained by the student.
     * @bodyParam yearObtained integer required The year the certificate was obtained by the student.
     * @bodyParam guardianFirstName string required The first name of the student's guardian.
     * @bodyParam guardianLastName string required The last name of the student's guardian.
     * @bodyParam guardianEmail string required The email address of the student's guardian.
     * @bodyParam guardianAddress string required The address of the student's guardian.
     * @bodyParam guardianPhone string required The phone number of the student's guardian.
     * @bodyParam facultyId integer required The ID of the student's faculty.
     * @bodyParam departmentId integer required The ID of the student's department.
     *
     * @response {
     *   "id": 1,
     *   "firstName": "John",
     *   "lastName": "Doe",
     *   "email": "johndoe@example.com",
     *   "profilePicture": "http://example.com/profile.jpg",
     *   "created_at": "2023-07-10T12:00:00Z",
     *   "updated_at": "2023-07-10T12:00:00Z"
     * }
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $rules = (new UpdateUserRequest())->rules();

        $rules = array_merge($rules, (new UpdateStudentRequest())->rules());

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

        $userStudent = User::findOrFail($id);
        $userStudent->update($userDetails);

        Student::where('user_id', $userStudent->id)->delete();

        $studentDetails = [
            'userId' => $userStudent->id,
            'gender' => $validated['gender'],
            'status' => $validated['status'],
            'dob' => $validated['dob'],
            'placeOfBirth' => $validated['placeOfBirth'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'region' => $validated['region'],
            'nationalIdentification' => $validated['nationalIdentification'],
            'country' => $validated['country'],
            'matriculationNumber' => $validated['matriculationNumber'],
            'level' => $validated['level'],
            'year' => $validated['year'],
            'program' => $validated['program'],
            'certificateObtained' => $validated['certificateObtained'],
            'yearObtained' => $validated['yearObtained'],
            'guardianFirstName' => $validated['guardianFirstName'],
            'guardianLastName' => $validated['guardianLastName'],
            'guardianEmail' => $validated['guardianEmail'],
            'guardianAddress' => $validated['guardianAddress'],
            'guardianPhone' => $validated['guardianPhone'],
        ];

        Student::create($studentDetails);
        return response($userStudent->fresh());
    }

    /**
     *
     * Delete a student by ID.
     *
     * @header Authorization Bearer {token} required A valid Sanctum token.
     * @urlParam id integer required The ID of the student to delete.
     *
     * @response {
     *   "message": "Student Deleted Successfully"
     * }
     */

    public function delete($id)
    {
        $examiner = User::findOrFail($id);
        $examiner->delete();
        return response(['message' => 'Examiner Deleted Successfully']);
    }

}
