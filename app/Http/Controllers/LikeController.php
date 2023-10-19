<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    private $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function store($post_id){
        $this->like->user_id = Auth::user()->id;
        $this->like->post_id = $post_id;
        $this->like->save();

        return redirect()->back();
    }

    public function destroy($post_id)
    {
        $this->like
                ->where('user_id', Auth::user()->id)
                ->where('post_id', $post_id)
                //where() is an array which store in the like
                ->delete();
                //specific to delete
                //specify which column to dlete
                // = assign, == compare value, === compare with datatype

        return redirect()->back();

        //Make a where clause inside
        //use delete method
        //add a route and use the route.
    }
}
