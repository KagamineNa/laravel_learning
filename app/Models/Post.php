<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    //Quy tắc đặt tên: Post thì tên bảng trong sql là posts
    //MealCategory thì tên bảng trong sql là meal_categories
    //Nếu không muốn tuân theo quy tắc đặt tên thì phải khai báo tên bảng
    protected $table = "posts";
    protected $primaryKey = "id"; // Khai báo khóa chính, Nếu không khai báo j thì mặc định là id

    // public $timestamps = false; // Khai báo không sử dụng mặc định 2 cột created_at và updated_at

    // const CREATED_AT = 'create_at'; // thay đổi tên cột mặc định created_at

    // protected $attributes = [
    //     'status' => 1
    // ]; // Khai báo giá trị mặc định cho cột status

    // Mặc định có 2 cột là created_at và updated_at
    public $timestamps = true;
    protected $fillable = ['title', 'content', 'status']; // Khai báo các cột có thể gán giá trị

    public function comments()
    {
        return $this->hasMany(
            Comment::class, // Model đích
            "post_id", // Khóa ngoại của bảng comments
            "id" // Khóa chính của bảng posts
        );
    }
}
