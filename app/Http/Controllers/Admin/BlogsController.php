<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Blog;
use Auth;

class BlogsController extends Controller
{

    public function __construct()
    {
        # Check permissions
        if(!Auth::user()->has('admin.blogs.access')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }
    }

    public function index() {
        # Get all blogs
        $blogs = Blog::all();

        # Return the view
        return view('admin/blogs/index', ['blogs' => $blogs]);
    }

    public function create()
    {
        # Check permissions
        if(!Auth::user()->has('admin.blogs.create')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        # Get all the data
        $data_index = 'blogs';
        require('Data/Create/Get.php');

        # Return the view
        return view('admin/blogs/create', [
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

    public function store(Request $request)
    {
        # Check permissions
        if(!Auth::user()->has('admin.blogs.create')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        # create the user
        $row = new Blog;

        # Save the data
        $data_index = 'blogs';
        require('Data/Create/Save.php');

        $row->user_id = Auth::user()->id;
        $row->save();

        # Return the admin to the blogs page with a success message
        return redirect('/admin/blogs')->with('success', "The blog has been created");
    }

    public function posts($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.blogs.posts')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        # Find the blog
        $blog = Blog::findOrFail($id);

        # Get the blog posts
        $posts = $blog->posts;

        # Return the view
        return view('admin/blogs/posts', ['posts' => $posts, 'blog' => $blog]);
    }

    public function edit($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.blogs.edit')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        # Find the blog
        $row = Blog::findOrFail($id);

        # Get all the data
        $data_index = 'blogs';
        require('Data/Edit/Get.php');

        # Return the edit form
        return view('admin/blogs/edit', [
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
        if(!Auth::user()->has('admin.blogs.edit')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        # Find the blog
        $row = Blog::findOrFail($id);

        if($row->user_id == Auth::user()->id or Auth::user()->su) {
            # The user who's trying to modify the post is able to do such because it's the owner or it's su

            # Save the data
            $data_index = 'blogs';
            require('Data/Edit/Save.php');

            # Return the admin to the blogs page with a success message
            return redirect('/admin/blogs')->with('success', "The blog has been edited");
        } else {
            #The user is not allowed to delete the blog
            return redirect('admin/blogs')->with('warning', "You are not allowed to perform this action");
        }
    }

    public function destroy($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.blogs.delete')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }

        # Find The Blog
        $blog = Blog::findOrFail($id);

    	if($blog->user_id == Auth::user()->id or Auth::user()->su) {
            # The user who's trying to delete the post is able to do such because it's the owner or it's su

            # Delete posts
            foreach($blog->posts as $post) {
                $post->delete();
            }

            # Delete blog
            $blog->delete();

            # Return a redirect
            return redirect('admin/blogs')->with('success', "The blog has been deleted");
        } else {
            #The user is not allowed to delete the blog
            return redirect('admin/blogs')->with('warning', "You are not allowed to perform this action");
        }
    }
}
