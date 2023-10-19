<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category)
    {
        $this->post     = $post;
        $this->category = $category;
    }

    public function create()
    {
        $all_categories = $this->category->all();
        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    public function store(Request $request)
    {
        #1. Validate all form data

        $request->validate([
            'category'    => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image'       => 'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        #2. save the post
        $this->post->user_id = Auth::user()->id;
        $this->post->image   = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        //image to base64 code
        //we need to use longtext()
        $this->post->description = $request->description;
        $this->post->save();

        #save the categories to the category_post table
        foreach($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
        }

        $this->post->categoryPost()->createMany($category_post);
        //categoryPost()will connect to the categorypost table
        //createMany()is a save method.
        //save() vs create() method.

        #4. go back to homepage
        return redirect()->route('index');

    }

    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('users.posts.show')->with('post', $post);
    }

    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        #if the auth user is Not the owner of the post, redirect to homepage.
        if(Auth::user()->id != $post->user->id){
            return redirect()->route('index');
        }

        $all_categories = $this->category->all();

        #get all the category IDs of this post. save in an array
        $selected_categories = [];
        foreach($post->categoryPost as $category_post){
            $selected_categories[] = $category_post->category_id;
        }

        return view('users.posts.edit')
                ->with('post', $post)
                ->with('all_categories', $all_categories)
                ->with('selected_categories', $selected_categories);

    }

    public function update(Request $request, $id)
    {
        #1.always validate the data from the form
        $request->validate([
            'category'      => 'required|array|between:1,3',
            'description'   => 'required|min:1|max:1000',
            'image'         => 'mimes:jpg,png,jpeg,gid|max:1048'
        ]);

        #2. update the post
        $post              = $this->post->findOrFail($id);
        $post->description = $request->description;

        //if there is a new image
        if($request->image){
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' .base64_encode(file_get_contents($request->image));
        }

        $post->save();

        #3. Delete all the record from category_post related to this post
        $post->categoryPost()->delete();
        //user the relationship Post::categoryPost() to select the records related to a post
        //Equivalent: DELETE FROM category_post WHERE post_id = $id

        #4. save the new categories to categories to category_post table
        foreach($request->category as $category_id){
            $category_post [] = ['category_id' => $category_id];
        }

        $post->categoryPost()->createMany($category_post);

        #5. redirect to show post page
        return redirect()->route('post.show', $id);

    }

    public function destroy($id)
    {
        $post = $this->post->findOrFail($id);

        $post->forceDelete();

        return redirect()->route('index');

    }



}
