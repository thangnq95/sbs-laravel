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

Route::get('/', function () {
    return view('welcome');
});


#Basic routing
Route::get('foo', function () {
    return 'Hello World';
});
//The Default Route Files
Route::get('/user', 'UserController@index');

//Available Router Methods
Route::match(['get', 'post'], '/', function () {
    //
});

Route::any('/', function () {
    //
});

#Redirect Routes
Route::redirect('/here', '/there');
Route::redirect('/here', '/there1', 301);
Route::permanentRedirect('/here', '/there');


#View Routes
Route::view('/welcome', 'welcome');



#Route Parameters
#Required Parameters
Route::get('user/{id}', function ($id) {
    return 'User '.$id;
});
Route::get('posts/{post}/comments/{comment}', function ($postId, $commentId) {
    //
});
#Optional Parameters
Route::get('user/{name?}', function ($name = null) {
    return $name;
});

Route::get('user/{name?}', function ($name = 'John') {
    return $name;
});

#Regular Expression Constraints
Route::get('user/{name}', function ($name) {
    //
})->where('name', '[A-Za-z]+');

Route::get('user/{id}', function ($id) {
    //
})->where('id', '[0-9]+');

Route::get('user/{id}/{name}', function ($id, $name) {
    //
})->where(['id' => '[0-9]+', 'name' => '[a-z]+']);
//Global Constraints => app/Providers/RouteServiceProvider.php Line 26
Route::get('user/{id}', function ($id) {
    // Only executed if {id} is numeric...
});
//Encoded Forward Slashes
Route::get('search/{search}', function ($search) {
    return $search;
})->where('search', '.*');

#Named Routes
Route::get('user/profile', function () {
    //
})->name('profile');
Route::get('user/profile', 'UserProfileController@show')->name('profile');

#Route Groups
#Middleware
Route::middleware(['first', 'second'])->group(function () {
    Route::get('/', function () {
        // Uses first & second Middleware
    });

    Route::get('user/profile', function () {
        // Uses first & second Middleware
    });
});
#Namespaces
Route::namespace('Admin')->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace
});
#Sub-Domain Routing
Route::domain('{account}.myapp.com')->group(function () {
    Route::get('user/{id}', function ($account, $id) {
        //
    });
});
#Route Prefixes
Route::prefix('admin')->group(function () {
    Route::get('users', function () {
        // Matches The "/admin/users" URL
    });
});
#Route Name Prefixes
Route::name('admin.')->group(function () {
    Route::get('users', function () {
        // Route assigned name "admin.users"...
    })->name('users');
});
#Route Model Binding
#Implicit Binding
Route::get('api/users/{user}', function (App\User $user) {
    return $user->email;
});
#Customizing The Key Name

#Fallback Routes
//The fallback route should always be the last route registered by your application.
Route::fallback(function () {
    //
});
