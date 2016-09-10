<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Schema;
use DB;
use Laralum;

class APIController extends Controller
{
    /**
    * Shows the API List
    */
    public function index()
    {
        return view('laralum/API/index');
    }

    /**
    * Shows the API json
    *
    * @param string $table
    * @param string $accessor
    * @param string $data
    */
    public function show($table, $accessor = null, $data = null)
    {
        $api = Laralum::apiData();

        if(!Schema::hasTable($table) or !array_key_exists($table, $api)){
            abort(404);
        }

        if(!$api[$table]['enabled']){
            return ['error' => trans('api.api_disabled')];
        }

        $columns = Schema::getColumnListing($table);

        if($accessor){

            if($accessor == 'latest') {
                $rows = DB::table($table)->orderBy('id', 'desc')->limit(1)->get();
            } elseif($accessor == 'latests'){
                if(!$data){
                    $data = 5;
                }
                $rows = DB::table($table)->orderBy('id', 'desc')->limit($data)->get();
            } else {
                if(!Schema::hasColumn($table, $accessor)){
                    abort(404);
                }
                $rows = DB::table($table)->where($accessor, $data)->get();
            }

        } else {
            $rows = DB::table($table)->get();
        }

        $final_columns = [];

        foreach($columns as $column){
            if(in_array($column, $api[$table]['show'])){
                array_push($final_columns, $column);
            }
        }

        $final_rows = [];
        foreach($rows as $row){
            $final_row = [];
            foreach($final_columns as $column){
                $final_row[$column] = $row->$column;
            }
            array_push($final_rows, $final_row);
        }

        return $final_rows;
    }
}
