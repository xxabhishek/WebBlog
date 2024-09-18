<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function assignRoleToUser($userId)
    {
        $user = User::find($userId);
        $user->assignRole('admin'); // Or 'user'
    }
}
