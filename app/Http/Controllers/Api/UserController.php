<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function getPermissions($id)
    {

        $user = User::find($id);
        $permissions = $user->getPermissionForCASL();

        return response()->json($permissions);
    }
}
