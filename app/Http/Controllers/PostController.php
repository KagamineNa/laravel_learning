<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // $post = new Post();
        // $post->title = "Bai viet so 3";
        // $post->content = "Noi dung bai viet so 3";
        // $post->save();
    }

    public function learn()
    {
        $post = new Post();
        $post->title = "Bai viet so 3";
        $post->content = "Noi dung bai viet so 3";
        $post->save();

        $post = Post::find(1); // Lấy ra bài viết có id = 1, tìm theo khóa chính

        $post = Post::firstOrCreate([
            'id' => 1
        ], [
            'title' => 'Bai viet so 5',
            'content' => 'Noi dung bai viet so 5',
            'status' => 0
        ]); // Tìm bài viết có id = 1, nếu không có thì tạo mới

        //Xóa cứng
        Post::destroy(1); // Xóa bài viết có id = 1
        Post::destroy(1, 2, 3); // Xóa bài viết có id = 1,2,3
    }

    public function add()
    {
        Post::create([
            'title' => 'Bai viet so 4',
            'content' => 'Noi dung bai viet so 4',
            'status' => 0
        ]);

        //hoặc cũng có thể dùng Post::insert

    }

    public function update($id)
    {
        $post = Post::find($id);
        $dataUpdate = [
            'title' => 'Bai viet so 4',
            'content' => 'Noi dung bai viet so 4',
            'status' => 0
        ];
        $post->update($dataUpdate);

        // $post = Post::where('id', $id)->update($dataUpdate); // Cách 2
        // $post = Post::updateOrCreate(
        //     ['id' => $id],
        //     $dataUpdate
        // ); // Cách 3    

    }

    public function softDelete()
    {
        // muốn dùng soft delete thì phải khai báo trong model: use SoftDeletes;
        //đồng thời cần phải tạo 1 cột deleted_at trong bảng
        // mỗi lần xóa thì dữ liệu sẽ không bị xóa cứng mà chỉ bị đánh dấu là đã xóa và được cập nhật thời gian xóa
        //dùng destroy như bthg
        $post = Post::withTrashed()->get(); // Lấy ra tất cả bài viết kể cả bài đã xóa
        // Có thể check 1 bản ghi đã từng bị xóa chưa bằng ... ->trashed()
        //onlyTrashed() lấy ra tất cả bài viết đã xóa mềm
        //restore() phục hồi bài viết đã xóa mềm
        //forceDelete() xóa cứng bài viết 
    }


}
