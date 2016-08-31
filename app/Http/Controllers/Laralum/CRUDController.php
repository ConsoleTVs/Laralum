<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Schema;
use Auth;
use Laralum;

class CRUDController extends Controller
{
    public function index()
    {
        Laralum::permissionToAccess('laralum.CRUD.access');

        # Get all the tables
        $table_list = [];
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table){
            foreach ($table as $key => $value){
                array_push($table_list, $value);
            }
        }
        return view('laralum/CRUD/index', ['tables' => $table_list]);
    }

    public function table($name)
    {
        Laralum::permissionToAccess('laralum.CRUD.access');

        # Check if table exists
        if(!Schema::hasTable($name)) {
            abort(404);
        }
        $columns = Schema::getColumnListing($name);
        $rows = DB::table($name)->get();
        return view('laralum/CRUD/table', ['columns' => $columns, 'rows' => $rows, 'name' => $name]);
    }

    public function dump($name, $id)
    {
        Laralum::permissionToAccess('laralum.CRUD.access');

        # Check if table exists
        if(!Schema::hasTable($name)) {
            abort(404);
        }

        # Check if column exists
        if(!Schema::hasColumn($name, 'id')) {
            abort(404);
        }

        # Check if row exists
        if(!DB::table($name)->where('id', $id)->get()) {
            abort(404);
        }
        return dd(DB::table($name)->where('id', $id)->get());
    }

    public function row($name, $id)
    {
        Laralum::permissionToAccess('laralum.CRUD.access');

        # Check if you're allowed to edit rows
        require('Data/Edit/DevGet.php');
        if(!$allow) {
            abort(404);
        }

        # Check if table exists
        if(!Schema::hasTable($name)) {
            abort(404);
        }

        # Check if column exists
        if(!Schema::hasColumn($name, 'id')) {
            abort(404);
        }

        # Check if row exists
        if(!DB::table($name)->where('id', $id)->get()) {
            abort(404);
        }

        $row = DB::table($name)->where('id', $id)->first();

        # Get all the data
        require('Data/Edit/DevGet.php');

        return view('laralum/CRUD/row', [
            'row'       =>  $row,
            'name'      =>  $name,
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

    public function saveRow($name, $id, Request $request)
    {
        Laralum::permissionToAccess('laralum.CRUD.access');

        # Check if you're allowed to edit rows
        require('Data/Edit/DevGet.php');
        if(!$allow) {
            abort(404);
        }

        # Check if table exists
        if(!Schema::hasTable($name)) {
            abort(404);
        }

        # Check if column exists
        if(!Schema::hasColumn($name, 'id')) {
            abort(404);
        }

        # Check if row exists
        if(!DB::table($name)->where('id', $id)->get()) {
            abort(404);
        }

        $row = DB::table($name)->where('id', $id)->first();

        # Save all the data
        require('Data/Edit/DevSave.php');

        return redirect()->route('Laralum::CRUD_table', ['table' => $name])->with('success', trans('laralum.msg_row_saved', ['id' => $id]));
    }

    public function createRow($name, Request $request)
    {
        Laralum::permissionToAccess('laralum.CRUD.access');

        # Check if you're allowed to create rows
        require('Data/Create/DevGet.php');
        if(!$allow) {
            abort(404);
        }

        # Check if table exists
        if(!Schema::hasTable($name)) {
            abort(404);
        }

        # Check if column exists
        if(!Schema::hasColumn($name, 'id')) {
            abort(404);
        }

        # Get all the data
        require('Data/Create/DevSave.php');

        return redirect()->route('Laralum::CRUD_table', ['table' => $name])->with('success', trans('laralum.msg_row_created'));
    }

    public function create($name)
    {
        Laralum::permissionToAccess('laralum.CRUD.access');

        # Check if you're allowed to create rows
        require('Data/Create/DevGet.php');
        if(!$allow) {
            abort(404);
        }

        # Check if table exists
        if(!Schema::hasTable($name)) {
            abort(404);
        }

        # Check if column exists
        if(!Schema::hasColumn($name, 'id')) {
            abort(404);
        }

        # Get all the data
        require('Data/Create/DevGet.php');

        return view('laralum/CRUD/create', [
            'name'      =>  $name,
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

    public function deleteRow($name, $id)
    {
        Laralum::permissionToAccess('laralum.CRUD.access');

        # Check if table exists
        if(!Schema::hasTable($name)) {
            abort(404);
        }

        # Check if column exists
        if(!Schema::hasColumn($name, 'id')) {
            abort(404);
        }

        # Check if you're allowed to delete rows
        require('Data/DevData.php');
        if(array_key_exists($name, $data)){
            if(array_key_exists('delete', $data[$name])) {
                if(!$data[$name]['delete']){
                    abort(404);
                }
            }
        }

        $row = DB::table($name)->where('id', $id)->first();

        # Check if su
        if(property_exists($row, 'su')){
            if($row->su){
                abort(403, trans('laralum.error_not_allowed'));
            }
        }

        DB::table($name)->where('id', $id)->delete();

        return redirect()->route('Laralum::CRUD_table', ['table' => $name])->with("success", trans('laralum.msg_row_deleted'));
    }
}
