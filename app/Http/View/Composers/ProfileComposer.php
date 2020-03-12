<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
//use App\Repositories\UserRepository;

class ProfileComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * Create a new profile composer.
     *
     * @param UserRepository $users
     * @return void
     */
//    public function __construct(UserRepository $users)
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        $this->users = "AAAAAA";
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('profile', ["profile"=>"This is profile data from ProfileComposer"]);
    }
}
