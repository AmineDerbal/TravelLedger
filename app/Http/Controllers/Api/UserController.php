<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\User\BasicUserResource;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json(BasicUserResource::collection($users));
    }

    public function getPermissions($id)
    {

        $user = User::find($id);
        $permissions = $user->getPermissionForCASL();

        return response()->json($permissions);
    }

    public function getUserRoles()
    {
        return response()->json(['user','admin']);
    }
}
