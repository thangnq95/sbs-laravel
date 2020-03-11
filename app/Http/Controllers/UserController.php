<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        return view('user.profile', ['user' => $id]);
//        return view('user.profile', ['user' => User::findOrFail($id)]);
    }
}
