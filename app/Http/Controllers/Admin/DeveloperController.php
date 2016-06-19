<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Schema;
use Auth;

class DeveloperController extends Controller
{
    public function __construct()
    {
        # Check permissions
        if(!Auth::user()->has('admin.developer.access')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }
    }

    public function index()
    {
        # Get all the tables
        $table_list = [];
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table){
            foreach ($table as $key => $value){
                array_push($table_list, $value);
            }
        }
        return view('admin/developer/index', ['tables' => $table_list]);
    }

    public function table($name)
    {
        # Check if table exists
        if(!Schema::hasTable($name)) {
            abort(404);
        }
        $columns = Schema::getColumnListing($name);
        $rows = DB::table($name)->get();
        return view('admin/developer/table', ['columns' => $columns, 'rows' => $rows, 'name' => $name]);
    }

    public function dump($name, $id)
    {
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

        return view('admin/developer/row', [
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
        ]);
    }

    public function saveRow($name, $id, Request $request)
    {
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

        return redirect(url('admin/developer', [$name]))->with('success', "The row #$id has been saved!");
    }

    public function createRow($name, Request $request)
    {
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

        return redirect(url('admin/developer', [$name]))->with('success', "The row has been created");
    }

    public function create($name)
    {
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

        return view('admin/developer/create', [
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
        ]);
    }

    public function deleteRow($name, $id)
    {

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
        if($row->su){
            return redirect(url('admin/developer', [$name]))->with('info', "You're not allowed to delete this row");
        }

        DB::table($name)->where('id', $id)->delete();

        return redirect(url('admin/developer', [$name]))->with("success", "The row has been deleted");
    }
}
