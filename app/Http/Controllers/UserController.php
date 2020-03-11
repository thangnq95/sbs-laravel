<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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


    /**
     * Update a user.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //Testing with url : http://localhost:8000/user/2?name=Thang
        //Request Path & Method
        $uri = $request->path();
        if ($request->is('user/*')) {
            var_dump("This is user/*");
            // Without Query String...
            $url = $request->url();
            var_dump($url);
            // With Query String...
            $url = $request->fullUrl();
            var_dump($url);
        }

        //Retrieving The Request Method
        $method = $request->method();
        if ($request->isMethod('post')) {
            var_dump("This is POST");
        }
        var_dump($method);

        //Retrieving Input
        $input = $request->all();
        var_dump($input);
        $name = $request->input('name');
        var_dump($name);
        $name = $request->input('name', 'Default value name');
        var_dump($name);

        //Determining If An Input Value Is Present
        if ($request->has('name')) {
            var_dump("Requets has name.");
        }else{
            var_dump("Requets hasn't name.");
        }

        $request->flash();
    }
}
