<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Document;
use Laralum;
use Auth;

class DocumentsController extends Controller
{
    public function showCreate($file)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.documents.create');

        Laralum::mustBeFile($file);

        if(Laralum::isDocument($file)) {
            abort(404);
        }

        # Get all the data
        $data_index = 'documents';
        require('Data/Create/Get.php');

        # Return the view
        return view('admin/files/create', [
            'file'      =>  $file,
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

    public function createDocument($file, Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.documents.create');

        Laralum::mustBeFile($file, '/admin/files');

        # create the document
        $row = new Document;

        # Save all the data
        $data_index = 'documents';
        require('Data/Create/Save.php');

        $row->user_id = Auth::user()->id;
        $row->name = $file;
        while(true) {
            $slug = rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
            if(!Document::where('slug', $slug)->first()) {
                $row->slug = $slug;
                break;
            }
        }
        $row->save();

        return redirect('admin/files')->with('success', "The document has been created");
    }

    public function edit($slug)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.documents.edit');

        # Check if it's the owner or su
        if(!Laralum::checkDocumentOwner('slug', $slug) and !Auth::user()->su) {
            return redirect('/admin/files')->with('warning', "You have no rights to enter this page");
        }

        $file = Laralum::document('slug', $slug);
        if($file) {
            $row = $file;
            $data_index = 'documents';
            require('Data/Edit/Get.php');

            return view('admin/files/edit', [
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
            ]);

        } else {
            abort(404);
        }
    }

    public function update($slug, Request $request)
    {

        # Check permissions
        Laralum::permissionToAccess('admin.documents.edit');

        # Check if it's the owner or su
        if(!Laralum::checkDocumentOwner('slug', $slug) and !Auth::user()->su) {
            return redirect('/admin/files')->with('warning', "You have no rights to enter this page");
        }

        $file = Laralum::document('slug', $slug);

        if($file) {

            $row = $file;
            $data_index = 'documents';
            require('Data/Edit/Save.php');

            return redirect('admin/files')->with('success', "The document has been edited");

        } else {
            abort(404);
        }
    }

    public function delete($slug)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.documents.delete');

        # Check if it's the owner or su
        if(!Laralum::checkDocumentOwner('slug', $slug) and !Auth::user()->su) {
            return redirect('/admin/files')->with('warning', "You have no rights to enter this page");
        }

        # Delete the document
        Laralum::document('slug', $slug)->delete();

        return redirect('/admin/files')->with('success', "The document has been deleted");
    }
}
