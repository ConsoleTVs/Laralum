<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laralum;

class CommentsController extends Controller
{

    public function create($id, Request $request)
    {
        Laralum::permissionToAccess('laralum.posts.comments');

        $post = Laralum::post('id', $id);

        # Check blog permissions
        Laralum::mustHaveBlog($post->blog->id);

        # Check if comments are enabled
        if($post->logged_in_comments or $post->anonymous_comments) {

            # create the user
            $row = Laralum::newComment();

            # Save the data
            $data_index = 'comments';
            require('Data/Create/Save.php');

            $row->post_id = $post->id;

            if($post->logged_in_comments) {
                $row->user_id = Laralum::loggedInUser()->id;
            }
            $row->save();

            return redirect()->route('Laralum::posts', ['id' => $post->id])->with('success', trans('laralum.msg_comment_created'));
        } else {
            abort(403, trans('laralum.msg_comment_disabled'));
        }
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('laralum.posts.comments');

        $row = Laralum::comment('id', $id);

        $post = $row->post;

        # Check blog permissions
        Laralum::mustHaveBlog($post->blog->id);

        $data_index = 'comments';
        require('Data/Edit/Get.php');

        if($row->author){
            $fields = array_diff($fields, array("name", "email"));
        }

        return view('laralum/comments/edit',[
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
        Laralum::permissionToAccess('laralum.posts.comments');

        $row = Laralum::comment('id', $id);

        $post = $row->post;

        # Check blog permissions
        Laralum::mustHaveBlog($post->blog->id);

        $data_index = 'comments';
        require('Data/Edit/Save.php');

        return redirect()->route('Laralum::posts', ['id' => $post->id])->with('success', trans('laralum.msg_comment_edited'));
    }

    public function destroy($id)
    {
        Laralum::permissionToAccess('laralum.posts.comments');
        
        $comment = Laralum::comment('id', $id);

        $post = $comment->post;

        # Check blog permissions
        Laralum::mustHaveBlog($post->blog->id);

        $comment->delete();

        return redirect()->route('Laralum::posts', ['id' => $post->id])->with('success', trans('laralum.msg_comment_deleted'));
    }
}
