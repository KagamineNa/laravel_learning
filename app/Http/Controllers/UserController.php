<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Mechanic;
use App\Models\Post;
use DB;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Phone;
use App\Models\Category;

class UserController extends Controller
{
    private $users;
    const _PER_PAGE = 5;
    public function __construct()
    {
        $this->users = new Users();
    }
    public function index(Request $request)
    {
        $title = "Danh sach nguoi dung";
        $filters = [];
        if (!empty($request->status)) {
            $status = $request->status == "active" ? 1 : NULL;
            $filters[] = ["status", "=", $status];
        }
        if (($request->groups_id) != 0) {
            $filters[] = ["groups_id", "=", $request->groups_id];
        }
        $keyword = null;
        if (!empty($request->keywords)) {
            $keyword = $request->keywords;
        }



        $sortType = $request->input('sort-type');
        $sortField = $request->input('sort-field');
        $allowSort = ["asc", "desc"];
        if (!empty($sortType) && in_array($sortType, $allowSort)) {
            $sortType = $sortType == "asc" ? "desc" : "asc";
        } else {
            $sortType = "asc";
        }

        $usersList = $this->users->getAllUsers($filters, $keyword, $sortField, $sortType, self::_PER_PAGE);


        return view("client.user.list", compact("title", "usersList", "sortType"));
    }



    public function addNewUser()
    {
        $title = "Them nguoi dung";
        $allGroup = getAllGroups();
        return view("client.user.add", compact("title", "allGroup"));
    }
    public function handleAddUser(Request $request)
    {
        $rules = [
            'fullname' => 'required | min:5 | max:255',
            'email' => 'required | email | unique:user', // unique:ten_bang
            'groups_id' => [
                'required',
                'integer',
                function ($atribute, $value, $fail) {
                    if ($value == 0) {
                        $fail("Vui long chon nhom !");
                    }
                }
            ],
            'status' => [
                'required',
                function ($atribute, $value, $fail) {
                    if ($value != "active" && $value != "inactive") {
                        $fail("Vui long chon trang thai! ");
                    }
                }
            ]
        ];
        $message = [
            'fullname.required' => 'Vui long nhap ten nguoi dung',
            'fullname.min' => 'Ten nguoi dung phai co it nhat :min ki tu',
            'fullname.max' => 'Ten nguoi dung khong duoc qua :max ki tu',
            'email.required' => 'Vui long nhap email',
            'email.email' => 'Email khong dung dinh dang',
            'email.unique' => 'Email da ton tai',
            'groups_id.required' => 'Nhom khong duoc de trong',
            'groups_id.integer' => 'ID nhom phai la so nguyen',
            'status.required' => 'Trang thai khong duoc de trong',
        ];
        $request->validate($rules, $message);

        $dataInsert = [
            $request->fullname,
            $request->email,
            $request->groups_id,
            $request->status == "active" ? 1 : NULL,
        ];
        $this->users->addUser($dataInsert);
        return redirect()->route("user.list")->with([
            "msg" => "Them nguoi dung thanh cong",
            "type" => "success"
        ]);
    }

    public function editUser($id = 0, Request $request)
    {
        $title = "Cap nhat thong tin nguoi dung";
        $allGroup = getAllGroups();
        if (!empty($id)) {
            $userDetail = $this->users->getUserDetail($id);
            if (!empty($userDetail)) {
                $request->session()->put('id', $id); // Luu id vao session de dung khi POST, vi van de bao mat         
            } else {
                return redirect()->route("user.list")->with([
                    "msg" => "Nguoi dung khong ton tai",
                    "type" => "danger"
                ]);
            }
            return view("client.user.edit", compact("title", "userDetail", "allGroup"));
        } else {
            return redirect()->route("user.list")->with([
                "msg" => "Lien ket ID khong ton tai",
                "type" => "danger"
            ]);
        }
    }

    public function handleEditUser(Request $request)
    {
        $id = $request->session()->get('id'); // Lay id tu session, lien quan den van de bao mat
        if (empty($id)) {
            return back()->with([
                "msg" => "Lien ket ID khong ton tai",
                "type" => "danger"
            ]);
        }
        $rules = [
            'fullname' => 'required | min:5 | max:255',
            'email' => 'required | email | unique:user,email,' . $id, // unique:ten_bang
            // unique:ten_bang,ten_cot,giatri_cot_khong_muon_kiem_tra
            'groups_id' => [
                'required',
                'integer',
                function ($atribute, $value, $fail) {
                    if ($value == 0) {
                        $fail("Vui long chon nhom !");
                    }
                }
            ],
            'status' => [
                'required',
                function ($atribute, $value, $fail) {
                    if ($value != "active" && $value != "inactive") {
                        $fail("Vui long chon trang thai! ");
                    }
                }
            ]
        ];
        $message = [
            'fullname.required' => 'Vui long nhap ten nguoi dung',
            'fullname.min' => 'Ten nguoi dung phai co it nhat :min ki tu',
            'fullname.max' => 'Ten nguoi dung khong duoc qua :max ki tu',
            'email.required' => 'Vui long nhap email',
            'email.email' => 'Email khong dung dinh dang',
            'email.unique' => 'Email da ton tai',
            'groups_id.required' => 'Nhom khong duoc de trong',
            'groups_id.integer' => 'ID nhom phai la so nguyen',
            'status.required' => 'Trang thai khong duoc de trong',
        ];
        $request->validate($rules, $message);
        $dataUpdate = [
            $request->fullname,
            $request->email,
            $request->groups_id,
            $request->status == "active" ? 1 : NULL,
            date("Y-m-d H:i:s"),
        ];
        $this->users->updateUser($dataUpdate, $id);
        return redirect()->route("user.edit", ['id' => $id])->with("msg", "Cap nhat nguoi dung thanh cong");
    }

    public function deleteUser($id = 0)
    {
        if (!empty($id)) {
            $userDetail = $this->users->getUserDetail($id);
            if (!empty($userDetail)) {
                $deleteStatus = $this->users->deleteUser($id);
                $deleteStatus ? $msg = "Xoa nguoi dung thanh cong" : $msg = "Xoa nguoi dung that bai";
                $deleteStatus ? $type = "success" : $type = "danger";
            } else {
                $msg = "Nguoi dung khong ton tai";
                $type = "danger";
            }
        } else {
            $msg = "Lien ket ID khong ton tai";
            $type = "danger";
        }
        return redirect()->route("user.list")->with([
            "msg" => $msg,
            "type" => $type
        ]);
    }

    public function relations()
    {
        // One to One: 1 user co 1 phone
        $userPhone = Users::find(11)->phone; //luu y: phone khong co () vi day la 1 thuoc tinh
        $phone_num = $userPhone->phone_number;

        $user = Phone::where('phone_number', '0376551338')->first()->user;
        $userName = $user->fullname;


        //One to Many: 1 group co the co nhieu user
        $users = Group::find(2)->users;
        if (!empty($users)) {
            foreach ($users as $user) {
                echo $user->fullname . "<br/>";
            }
        }
        $group = Users::find(11)->group;
        $groupName = $group->name;


        //has One Through: 1 carOwner co 1 car, 1 car co 1 mechanic => truy van tu carOwner den mechanic
        $carOwner = Mechanic::find(1)->owner;

        //has Many Through: 1 group co nhieu user, 1 user co nhieu (hoac 1) phone => truy van tu group den phone


        //Many to Many: 1 Post có thể viết về nhiều Categories, 1 Category có thể có nhiều bài Post viết về nó
        //Khi xây dựng mqh này, cần phải có 1 table trung gian post_category
        //Can phai co day du 3 column create_at, update_at, delete_at ???
        $posts = Category::find(1)->posts;
        foreach ($posts as $post) {
            if (!empty($post->pivot->status)) {
                echo $post->status . "<br/>";
            }
        }

        //Lấy dữ liệu dựa vào ràng buộc liên kết
        //VD: Lấy ra những bài viết có nhiều hơn 1 comment.
        $posts = Post::has('comments', '>', 1)->get(); // comments: tên của phương thức trong model Post

        //whereHas: Same same has, nhưng ta có thể custom điều kiện
        $posts = Post::whereHas('comments', function ($query) {
            $query->where('content', 'like', '%hay%');
        })->get();

        //orWhereHas

        //doesntHave: Lấy ra những bài viết không có comment nào (Nguoc lai cua has)
        $posts = Post::doesntHave('comments')->get();

        //whereDoesntHave: Nguoc lai cua whereHas
        $posts = Post::whereDoesntHave('comments', function ($query) {
            $query->where('content', 'like', '%hay%');
        })->get();

        //withCount: Đếm số lượng bản ghi liên kết
        $posts = Post::withCount('comments')->get();


        //Eager Loading: Tăng tốc độ truy vấn khi có ràng buộc liên kết
        //2 phương thức Lazy Loading và Eager Loading đều có ưu nhược điểm riêng.
        //VD: Lấy ra những bài viết có nhiều hơn 1 comment

        // DB::enableQueryLog();
        $users = Users::all(); //TH1: Khong su dung eager loading
        $users = Users::with('phone')->get(); //TH2: Su dung eager loading
        foreach ($users as $user) {
            if (!empty($user->phone)) {
                echo $user->phone->phone_number . "<br/>";
            }
        }
        // dd(DB::getQueryLog());

        //Kết hợp Lazy Loading và Eager Loading
        $users = Users::all();
        $users = $users->load('phone');
        foreach ($users as $user) {
            if (!empty($user->phone)) {
                echo $user->phone->phone_number . "<br/>";
            }
        }


        //Update
        //VD: update thêm 1 comment vào bài post có id = 1
        $post = Post::find(1);
        $post->comments()->create([
            'content' => 'Bai viet id 1 dinh chop vu than'
        ]);
        // Nhớ phải khai báo fillable và timestamp trong model Comment


    }

}
