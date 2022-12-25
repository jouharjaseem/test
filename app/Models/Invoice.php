<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoice';
    public function insert_data(string $table, array $data): string
    {
        return  DB::table($table)->insertGetId($data);
    }
    public function update_data(string $table,String $id, array $data)
    {
          DB::table($table)->where("id",$id)->update($data);
    }
    public function delete_data(string $table,String $id)
    {
         DB::table($table)->where("master_id",$id)->delete();
    }
    public function delete_data_main(string $table,String $id)
    {
         DB::table($table)->where("id",$id)->delete();
    }
}
