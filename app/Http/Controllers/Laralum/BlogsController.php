<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Blog_Role;
use Laralum;

class BlogsController extends Controller
{

    public function index() {
        Laralum::permissionToAccess('laralum.blogs.access');

        # Get all blogs
        $blogs = Laralum::blogs();

        # Return the view
        return view('laralum/blogs/index', ['blogs' => $blogs]);
    }

    public function create()
    {
        Laralum::permissionToAccess('laralum.blogs.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.blogs.create');

        # Get all the data
        $data_index = 'blogs';
        require('Data/Create/Get.php');

        # Return the view
        return view('laralum/blogs/create', [
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
            'encrypted' =>  $encrypted,
            'hashed'    =>  $hashed,
            'masked'    =>  $masked,
            'table'     =>  $table,
            'code'      =>  $code,
            'wysiwyg'   =>  $wysiwyg,
            'relations' =>  $relations,
        ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('laralum.blogs.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.blogs.create');

        # create the user
        $row = Laralum::newBlog();

        # Save the data
        $data_index = 'blogs';
        require('Data/Create/Save.php');

        $row->user_id = Laralum::loggedInUser()->id;
        $row->save();

        # Return the admin to the blogs page with a success message
        return redirect()->route('Laralum::blogs')->with('success', "The blog has been created");
    }

    public function posts($id)
    {
        Laralum::permissionToAccess('laralum.blogs.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.blogs.posts');

        # Check blog permissions
        Laralum::mustHaveBlog($id);

        # Find the blog
        $blog = Laralum::blog('id', $id);

        # Get the blog posts
        $posts = $blog->posts;

        # Return the view
        return view('laralum/blogs/posts', ['posts' => $posts, 'blog' => $blog]);
    }

    public function roles($id)
    {
        Laralum::permissionToAccess('laralum.blogs.access');

        # Check if blog owner
        Laralum::mustOwnBlog($id);

        $blog = Laralum::blog('id', $id);

        $roles = Laralum::roles();

        return view('laralum/blogs/roles', ['blog' => $blog, 'roles' => $roles]);
    }


    public function updateRoles($id, Request $request)
    {
        Laralum::permissionToAccess('laralum.blogs.access');

        # Check if blog owner
        Laralum::mustOwnBlog($id);

        $blog = Laralum::blog('id', $id);

        $roles = Laralum::roles();

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
        return redirect()->route('Laralum::blogs')->with('success', "The blog's roles has been updated");
    }

    public function checkRole($blog_id, $role_id)
    {
        Laralum::permissionToAccess('laralum.blogs.access');

    	# This function returns true if the specified user is found in the specified role and false if not

    	if(Blog_Role::whereBlog_idAndRole_id($blog_id, $role_id)->first()) {
    		return true;
    	} else {
    		return false;
    	}

    }

    public function deleteRel($blog_id, $role_id)
    {
        Laralum::permissionToAccess('laralum.blogs.access');

    	$rel = Blog_Role::whereBlog_idAndRole_id($blog_id, $role_id)->first();
    	if($rel) {
    		$rel->delete();
    	}
    }

    public function addRel($blog_id, $role_id)
    {
        Laralum::permissionToAccess('laralum.blogs.access');

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
        Laralum::permissionToAccess('laralum.blogs.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.blogs.edit');

        # Check if blog owner
        Laralum::mustOwnBlog($id);

        # Find the blog
        $row = Laralum::blog('id', $id);

        # Get all the data
        $data_index = 'blogs';
        require('Data/Edit/Get.php');

        # Return the edit form
        return view('laralum/blogs/edit', [
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
            'relations' =>  $relations,
        ]);
    }

    public function update($id, Request $request)
    {
        Laralum::permissionToAccess('laralum.blogs.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.blogs.edit');

        # Check if blog owner
        Laralum::mustOwnBlog($id);

        # Find the blog
        $row = Laralum::blog('id', $id);

        if($row->user_id == Laralum::loggedInUser()->id or Laralum::loggedInUser()->su) {
            # The user who's trying to modify the post is able to do such because it's the owner or it's su

            # Save the data
            $data_index = 'blogs';
            require('Data/Edit/Save.php');

            # Return the admin to the blogs page with a success message
            return redirect()->route('Laralum::blogs')->with('success', "The blog has been edited");
        } else {
            #The user is not allowed to delete the blog
            abort(403, trans('laralum.error_not_allowed'));
        }
    }

    public function destroy($id)
    {
        Laralum::permissionToAccess('laralum.blogs.access');
        
        # Check permissions
        Laralum::permissionToAccess('laralum.blogs.delete');

        # Check if blog owner
        Laralum::mustOwnBlog($id);

        # Find The Blog
        $blog = Laralum::blog('id', $id);

    	if($blog->user_id == Laralum::loggedInUser()->id or Laralum::loggedInUser()->su) {
            # The user who's trying to delete the post is able to do such because it's the owner or it's su

            # Delete posts
            foreach($blog->posts as $post) {
                $post->delete();
            }

            # Delete blog
            $blog->delete();

            # Return a redirect
            return redirect()->route('Laralum::blogs')->with('success', "The blog has been deleted");
        } else {
            #The user is not allowed to delete the blog
            abort(403, trans('laralum.error_not_allowed'));
        }
    }
}
