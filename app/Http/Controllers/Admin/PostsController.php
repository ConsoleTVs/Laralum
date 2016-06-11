<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use Auth;

class PostsController extends Controller
{
    public function __construct()
    {
        # Check permissions
        if(!Auth::user()->has('admin.posts.access')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }
    }

    public function index($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.posts.view')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        $post = Post::findOrFail($id);

        $post->addView();

        return view('admin/blogs/posts/index', ['post' => $post]);
    }

    public function graphics($id){
        # Check permissions
        if(!Auth::user()->has('admin.posts.graphics')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        $post = Post::findOrFail($id);

        return view('admin/blogs/posts/graphics', ['post' => $post]);
    }

    public function create($id)
    {

        # Check permissions
        if(!Auth::user()->has('admin.posts.create')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        $data_index = 'posts';
        require('Data/Create/Get.php');

        return view('admin/blogs/posts/create', [
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
            'encrypted' =>  $encrypted,
            'hashed'    =>  $hashed,
            'masked'    =>  $masked,
            'table'     =>  $table,
            'code'      =>  $code,
            'wysiwyg'   =>  $wysiwyg,
        ]);
    }

    public function store($id, Request $request)
    {
        # Check permissions
        if(!Auth::user()->has('admin.posts.create')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        # create the user
        $row = new Post;

        # Save the data
        $data_index = 'posts';
        require('Data/Create/Save.php');

        $row->user_id = Auth::user()->id;
        $row->blog_id = $id;
        $row->save();

        # Return the admin to the posts page with a success message
        return redirect(url('/admin/blogs', [$id]))->with('success', "The post has been created");
    }

    public function edit($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.posts.edit')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        $row = Post::findOrFail($id);

        $data_index = 'posts';
        require('Data/Edit/Get.php');

        return view('admin/blogs/posts/edit',[
            'row'       =>  $row,
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
            'empty'     =>  $empty,
            'encrypted' =>  $encrypted,
            'hashed'    =>  $hashed,
            'masked'    =>  $masked,
            'table'     =>  $table,
            'code'      =>  $code,
            'wysiwyg'   =>  $wysiwyg,
        ]);
    }

    public function update($id, Request $request)
    {
        # Check permissions
        if(!Auth::user()->has('admin.posts.edit')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        $row = Post::findOrFail($id);

        $data_index = 'posts';
        require('Data/Edit/Save.php');

        $row->edited_by = Auth::user()->id;
        $row->save();

        return redirect(url('/admin/blogs', [$row->blog->id]))->with('success', "The post has been updated");
    }

    public function destroy($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.posts.delete')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        # Find The Post
        $post = Post::findOrFail($id);
        $blog_id = $post->blog->id;

        if($post->author->id == Auth::user()->id or $post->blog->user->id == Auth::user()->id) {
            $post->delete();
        }

        return redirect(url('/admin/blogs', [$blog_id]))->with('success', "The post has been deleted");
    }

}
