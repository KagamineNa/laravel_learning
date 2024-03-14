<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "categories";

    public function posts()
    {
        return $this->belongsToMany(
            Post::class, // Model đích
            "posts_categories", // Bảng trung gian
            "category_id", // 2 khóa ngoại của bảng trung gian
            "post_id"
        )->withPivot("created_at", "status"); // Nếu muốn lấy thêm cac cot khac từ bảng trung gian
    }
}
