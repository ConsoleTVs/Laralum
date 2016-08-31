<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Laralum;
use Auth;

class DocumentsController extends Controller
{
    public function showCreate($file)
    {
        Laralum::permissionToAccess('laralum.files.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.documents.create');

        Laralum::mustBeFile($file);

        if(Laralum::isDocument($file)) {
            abort(404);
        }

        # Get all the data
        $data_index = 'documents';
        require('Data/Create/Get.php');

        # Return the view
        return view('laralum/documents/create', [
            'file'      =>  $file,
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

    public function createDocument($file, Request $request)
    {
        Laralum::permissionToAccess('laralum.files.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.documents.create');

        Laralum::mustBeFile($file, '/admin/files');

        # create the document
        $row = Laralum::newDocument();

        # Save all the data
        $data_index = 'documents';
        require('Data/Create/Save.php');

        $row->user_id = Laralum::loggedInUser()->id;
        $row->name = $file;
        while(true) {
            $slug = rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
            if(!Laralum::document('slug', $slug)) {
                $row->slug = $slug;
                break;
            }
        }
        $row->save();

        return redirect()->route('Laralum::files')->with('success', trans('laralum.msg_document_created'));
    }

    public function edit($slug)
    {
        Laralum::permissionToAccess('laralum.files.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.documents.edit');

        # Check if it's the owner or su
        if(!Laralum::checkDocumentOwner('slug', $slug) and !Laralum::loggedInUser()->su) {
            abort(403, trans('laralum.error_not_allowed'));
        }

        $file = Laralum::document('slug', $slug);
        if($file) {
            $row = $file;
            $data_index = 'documents';
            require('Data/Edit/Get.php');

            return view('laralum/documents/edit', [
                'file'      =>  $file,
                'row'       =>  $row,
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

        } else {
            abort(404);
        }
    }

    public function update($slug, Request $request)
    {
        Laralum::permissionToAccess('laralum.files.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.documents.edit');

        # Check if it's the owner or su
        if(!Laralum::checkDocumentOwner('slug', $slug) and !Auth::user()->su) {
            abort(403, trans('laralum.error_not_allowed'));
        }

        $file = Laralum::document('slug', $slug);

        if($file) {

            $row = $file;
            $data_index = 'documents';
            require('Data/Edit/Save.php');

            return redirect()->route('Laralum::files')->with('success', trans('laralum.msg_document_created'));

        } else {
            abort(404);
        }
    }

    public function delete($slug)
    {
        Laralum::permissionToAccess('laralum.files.access');
        
        # Check permissions
        Laralum::permissionToAccess('laralum.documents.delete');

        # Check if it's the owner or su
        if(!Laralum::checkDocumentOwner('slug', $slug) and !Auth::user()->su) {
            abort(403, trans('laralum.error_not_allowed'));
        }

        # Delete the document
        Laralum::document('slug', $slug)->delete();

        return redirect()->route('Laralum::files')->with('success', trans('laralum.msg_document_deleted'));
    }
}
