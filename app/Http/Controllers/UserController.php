<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Users;

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

}
