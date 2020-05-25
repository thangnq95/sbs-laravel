<?php

namespace App\Http\Controllers;

use App\Model\Supplier;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function index()
    {
        $supplier = Supplier::find(1);

        dd($supplier->userHistory);
        die();
    }
    /**
     * Show the profile for the given user.
     *
     * @param int $id
     * @return View
     */
    public function show(Request $request, $id)
    {
        $request->session()->put('key', 'value');
        $request->session()->flash('status', 'Task was successful!');
        $data = $request->session()->all();
        var_dump($data);
    }


    /**
     * Dump basic request with assume update a user.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //Testing with url : http://localhost:8000/basic/request/2?name=Thang
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

    public function profile()
    {
        Log::info('Showing user profile for user: 1111111');
        return view('profile', ['user' => 1111111]);
    }

    public function exampleUrlGeneration()
    {
        var_dump(url("/posts/1"));
        var_dump(url()->current());
        var_dump(url()->full());
        var_dump(url()->previous());
        var_dump(route('post.show', ['post' => 1]));
        var_dump(route('comment.show', ['post' => 1, 'comment' => 3]));
    }
}
