<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laralum;

class PostsController extends Controller
{

    public function index($id)
    {
        Laralum::permissionToAccess('laralum.posts.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.posts.view');

        $post = Laralum::post('id', $id);

        # Check blog permissions
        Laralum::mustHaveBlog($post->blog->id);

        $post->addView();

        $data_index = 'comments';
        require('Data/Create/Get.php');

        if($post->logged_in_comments){
            $fields = array_diff($fields, array("name", "email"));
        }

        $comments = $post->comments()->orderBy('created_at', 'desc')->get();

        return view('laralum/posts/index', [
            'comments'  =>  $comments,
            'post'      =>  $post,
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

    public function graphics($id)
    {
        Laralum::permissionToAccess('laralum.posts.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.posts.graphics');

        $post = Laralum::post('id', $id);

        # Check blog permissions
        Laralum::mustHaveBlog($post->blog->id);

        return view('laralum/posts/graphics', ['post' => $post]);
    }

    public function create($id)
    {
        Laralum::permissionToAccess('laralum.posts.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.posts.create');

        # Check blog permissions
        Laralum::mustHaveBlog($id);

        $blog = Laralum::blog('id', $id);

        $data_index = 'posts';
        require('Data/Create/Get.php');

        return view('laralum/posts/create', [
            'blog'      =>  $blog,
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

    public function store($id, Request $request)
    {
        Laralum::permissionToAccess('laralum.posts.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.posts.create');

        # Check blog permissions
        Laralum::mustHaveBlog($id);

        # create the user
        $row = Laralum::newPost();

        # Save the data
        $data_index = 'posts';
        require('Data/Create/Save.php');

        $row->user_id = Laralum::loggedInUser()->id;
        $row->blog_id = $id;
        $row->save();

        # Return the admin to the posts page with a success message
        return redirect()->route('Laralum::blogs_posts', ['id' => $id])->with('success', trans('laralum.msg_post_created'));
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('laralum.posts.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.posts.edit');

        $row = Laralum::post('id', $id);

        # Check blog permissions
        Laralum::mustHaveBlog($row->blog->id);

        $data_index = 'posts';
        require('Data/Edit/Get.php');

        return view('laralum/posts/edit',[
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
        Laralum::permissionToAccess('laralum.posts.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.posts.edit');

        $row = Laralum::post('id', $id);

        # Check blog permissions
        Laralum::mustHaveBlog($row->blog->id);

        $data_index = 'posts';
        require('Data/Edit/Save.php');

        $row->edited_by = Laralum::loggedInUser()->id;
        $row->save();

        return redirect()->route('Laralum::blogs_posts', ['id' => $row->blog->id])->with('success', trans('laralum.msg_post_updated'));
    }


    public function destroy($id)
    {
        Laralum::permissionToAccess('laralum.posts.access');
        
        # Check permissions
        Laralum::permissionToAccess('laralum.posts.delete');

        # Find The Post
        $post = Laralum::post('id', $id);

        # Check blog permissions
        Laralum::mustHaveBlog($post->blog->id);

        $post->delete();

        return redirect()->route('Laralum::blogs_posts', ['id' => $post->blog->id])->with('success', trans('laralum.msg_post_deleted'));
    }

}
