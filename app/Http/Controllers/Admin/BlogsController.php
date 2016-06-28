<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Blog;
use Auth;
use App\Role;
use App\Blog_Role;

class BlogsController extends Controller
{

    public function __construct()
    {
        # Check permissions
        Laralum::permissionToAccess('admin.blogs.access');
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
        Laralum::permissionToAccess('admin.blogs.create', '/admin/blogs');

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
        Laralum::permissionToAccess('admin.blogs.create', '/admin/blogs');

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
        Laralum::permissionToAccess('admin.blogs.posts', '/admin/blogs');

        # Check blog permissions
        if(!Auth::user()->has_blog($id) and !Auth::user()->owns_blog($id)){
            return redirect('/admin/blogs')->with('warning', "You are not allowed to perform this action")->send();
        }

        # Find the blog
        $blog = Blog::findOrFail($id);

        # Get the blog posts
        $posts = $blog->posts;

        # Return the view
        return view('admin/blogs/posts', ['posts' => $posts, 'blog' => $blog]);
    }

    public function roles($id){
        # Check if blog owner
        if(!Auth::user()->owns_blog($id)){
            return redirect('/admin/blogs')->with('warning', "You are not allowed to perform this action")->send();
        }

        $blog = Blog::findOrFail($id);

        $roles = Role::all();

        return view('admin/blogs/roles', ['blog' => $blog, 'roles' => $roles]);
    }


    public function updateRoles($id, Request $request)
    {
        # Check if blog owner
        if(!Auth::user()->owns_blog($id)){
            return redirect('/admin/blogs')->with('warning', "You are not allowed to perform this action")->send();
        }

		# Find the user
    	$blog = Blog::findOrFail($id);

    	# Get all roles
    	$roles = Role::all();

    	# Change user's roles
    	foreach($roles as $role) {

            if($request->input($role->id)){
                # The admin selected that role

                # Check if the blog was already in that role
                if($this->checkRole($blog->id, $role->id)) {
                    # The blog is already in that role, so no change is made
                } else {
                    # Add the blog to the selected role
                    $this->addRel($blog->id, $role->id);
                }
            } else {
                # The admin did not select that role

                # Check if the blog is in that role
                if($this->checkRole($blog->id, $role->id)) {
                    # The blog is that role, so as the admin did not select it, we need to delete the relationship
                    $this->deleteRel($blog->id, $role->id);
                } else {
                    # The blog is not in that role and the admin did not select it
                }
            }
    	}

    	# Return Redirect
        return redirect('admin/blogs')->with('success', "The blog's roles has been updated");
    }

    public function checkRole($blog_id, $role_id)
    {
    	# This function returns true if the specified user is found in the specified role and false if not

    	if(Blog_Role::whereBlog_idAndRole_id($blog_id, $role_id)->first()) {
    		return true;
    	} else {
    		return false;
    	}

    }

    public function deleteRel($blog_id, $role_id)
    {
    	$rel = Blog_Role::whereBlog_idAndRole_id($blog_id, $role_id)->first();
    	if($rel) {
    		$rel->delete();
    	}
    }

    public function addRel($blog_id, $role_id)
    {
    	$rel = Blog_Role::whereBlog_idAndRole_id($blog_id, $role_id)->first();
    	if(!$rel) {
    		$rel = new Blog_Role;
    		$rel->blog_id = $blog_id;
    		$rel->role_id = $role_id;
    		$rel->save();
    	}
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.blogs.edit', '/admin/blogs');

        # Check if blog owner
        if(!Auth::user()->owns_blog($id)){
            return redirect('/admin/blogs')->with('warning', "You are not allowed to perform this action")->send();
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
        Laralum::permissionToAccess('admin.blogs.edit', '/admin/blogs');

        # Check if blog owner
        if(!Auth::user()->owns_blog($id)){
            return redirect('/admin/blogs')->with('warning', "You are not allowed to perform this action")->send();
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
        Laralum::permissionToAccess('admin.blogs.delete', '/admin/blogs');

        # Check if blog owner
        if(!Auth::user()->owns_blog($id)){
            return redirect('/admin/blogs')->with('warning', "You are not allowed to perform this action")->send();
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
