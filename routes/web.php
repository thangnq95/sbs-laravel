<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

#ROUTING
Route::get('basic/routing/{id}', 'UserController@show');

#REQUESTS
Route::get('basic/request/{id}', 'UserController@update'); // Test with http://localhost:8000/basic/request/2?name=Thang



#VIEW
Route::get('/baisc/view/normal', function () {
    return view('admin.profile', ['name' => 'Thang1']);
});
Route::get('baisc/view/share', function () {
    return view('greeting')->with('name', 'Thang2');
});
Route::get('baisc/view/composer', 'UserController@profile');

#URL generation
Route::get('basic/url-generation', 'UserController@exampleUrlGeneration');
Route::get('basic/url-generation/post/{post}', function () {
    //
})->name('post.show');
Route::get('basic/url-generation/post/{post}/comment/{comment}', function () {
    //
})->name('comment.show');

#VALIDATION
Route::get('basic/validation/post/create', 'PostController@create');
Route::post('basic/validation/post', 'PostController@store');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

//Protecting Routes
Route::get('profile', function () {
    // Only authenticated users may enter...
    return Auth::user();
})->middleware('verified');

//Add gr wit middleware
Route::middleware(['auth'])->group(function () {

    Route::get('/profile1', 'ProfileController@index')->name('profile');

    Route::get('user/profile', function () {
        // Uses first & second Middleware
    });
});

//Route::get('api/user', function () {
//    // Only authenticated users may enter...
//    return "API HERE";
//})->middleware('auth.basic.once');

#Protecting Routes
//Query String
Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', function(Request $request) {
        return $request->user();
    });
    Route::post('/api/user-payload', function() {
        return "AA";
    });
    Route::post('/api/user-bear-token', function(Request $request) {
        return $request->user();
    });
});

//Posts
Route::get('/post/create', 'PostController@create')->name('post-create');
Route::get('/post/update', 'PostController@update')->name('update-create');
