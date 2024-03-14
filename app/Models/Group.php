<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Users;

class Group extends Model
{
    use HasFactory;

    protected $table = "groups";

    public function getAllGroup()
    {
        $groups = DB::table($this->table)->orderBy("name", "ASC")->get();
        return $groups;
    }

    public function users()
    {
        return $this->hasMany(Users::class, 'groups_id', 'id');
    }
}
