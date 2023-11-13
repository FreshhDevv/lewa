<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * @group Users
 *
 * Users endpoint
 * */

class UserController extends Controller
{
    //The constructor below makes sure that only users with role of 'admin' will access any functions in this controller.
    public function __construct()
    {
        $this->middleware(['role:admin|dean|hod|coordinator']);
    }
/**
 * @group Users
 *
 * Retrieve a list of all users with the 'admin', 'dean', 'hod', 'coordinator', 'lecturer', 'examiner', or 'supportStaff' role.
 *
 * @header Authorization Bearer {token} required A valid Sanctum token.
 *
 * @response {
 *     "users": [
 *         {
 *           "id": 2,
 *           "firstName": "John",
 *           "lastName": "Doe",
 *           "email": "johndoe@example.com",
 *           "profilePicture": null,
 *           "created_at": "2023-07-10T12:00:00Z",
 *           "updated_at": "2023-07-10T12:00:00Z"
 *           "faculties": [
 *              {
 *                "id": 1,
 *                "name": "Faculty of Engineering and Technology",
 *                "code": 516,
 *                "slug": "FET",
 *                "about": "Qui omnis et quibusdam. Vel eos odio aspernatur. Sunt sunt sed beatae earum molestiae.",
 *                "created_at": "2023-07-10T12:00:00Z",
 *                "updated_at": "2023-07-10T12:00:00Z",
 *                "pivot" : {
 *                   "userId": 2,
 *                   "facultyId: 1,
 *                 }
 *               }
 *             ],
 *            "departments": [
 *              {
 *                "id": 1,
 *                "name": "Computer Engineering",
 *                "slug": "ce",
 *                "created_at": "2023-07-10T12:00:00Z",
 *                "updated_at": "2023-07-10T12:00:00Z",
 *                "pivot" : {
 *                  "userId": 2,
 *                  "facultyId: 1,
 *                 }
 *               }
 *            ],
 *         },
 *       ]
 * }
*/

    public function index()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'dean', 'hod', 'coordinator', 'lecturer', 'examiner', 'supportStaff']);
        })
            ->with(['roles', 'faculties', 'departments'])
            ->get();

        foreach ($users as $user) {
            $response[] = [
                'user' => $user,
                'role' => $user->roles->pluck('name'),
                'faculties' => $user->faculties,
                'departments' => $user->departments,
            ];
        }

        return response()->json([
            'users' => $response,
        ]);
    }
}
