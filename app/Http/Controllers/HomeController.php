<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class HomeController extends Controller
{
    private $post;
    private $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(POST $post, User $user)
    {
        // $this->middleware('auth');
        #this is doing the same as the middleware in the routes
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUser();

        return view('users.home')
                ->with('home_posts', $home_posts)
                ->with('suggested_users', $suggested_users);
    }

    #Get the posts of the users that the Auth user is following
    private function getHomePosts()
    {
        $all_posts   = $this->post->latest()->get();
        $home_posts = []; // in case the $home_posts is empty, it will not return null, but empty instead

        foreach($all_posts as $post)
        {
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id ){
                $home_posts[]   = $post;
            }
        }
    

    return $home_posts;
    }

    #Get the users that the Auth user is not following
    private function getSuggestedUser()
    {
        $all_users       = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach($all_users as $user)
        {
            if(!$user->isFollowed()) //! is means not.
            {
                $suggested_users[] = $user;
            } 
        }

        return array_slice($suggested_users, 0, 3);
        //array_slice(x,y,z)
        //x == array
        //y == offset/starting index
        //z == length/how many
    }

    public function search(Request $request)
    {
        $users = $this->user->where('name', 'like', '%'. $request->search.'%')->get();
        return view('users.search')->with('users',$users)->with('search', $request->search);
    }

}
