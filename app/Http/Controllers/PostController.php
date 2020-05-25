<?php

namespace App\Http\Controllers;

use App\Model\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function index()
    {
        $post = Post::findOrFail(1);
        dd([$post, $post->comments]);
    }

    /**
     * Show the form to create a new blog post.
     *
     * @return Response
     */
    public function create()
    {
        $user = Auth::user();
        if (Gate::allows('create-post', $user)) {
            echo "You can create post now";
            return view('post.create');
        } else {
            echo "You can't create post now.\nPlease login first";
        }
    }

    /**
     * Show the form to update a blog post.
     *
     * @return Response
     */
    public function update()
    {
        if (Gate::allows('update-post')) {
            echo "You can update post now";
            return view('post.create');
        } else {
            echo "You can't update post now.\nPlease login first";
        }
    }

    /**
     * Store a new blog post.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Validate and store the blog post...
        $validatedData = $request->validate([
            'title' => 'required|unique:posts|max:255',
            'content' => 'required',
        ]);
        $post = new Post([
            'title' => $request->get('title'),
            'content' => $request->get('content')
        ]);
        $post->save();
        return $post;
    }
}
