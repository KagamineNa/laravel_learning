<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Users;

class UserController extends Controller
{
    private $users;

    public function __construct()
    {
        $this->users = new Users();
    }
    public function index()
    {
        $title = "Danh sach nguoi dung";
        $usersList = $this->users->getAllUsers();
        $this->users->learnQueryBuilder();

        return view("client.user.list", compact("title", "usersList"));
    }

    public function addNewUser()
    {
        $title = "Them nguoi dung";
        return view("client.user.add", compact("title"));
    }
    public function handleAddUser(Request $request)
    {
        $rules = [
            'fullname' => 'required | min:5 | max:255',
            'email' => 'required | email | unique:user', // unique:ten_bang
        ];
        $message = [
            'fullname.required' => 'Vui long nhap ten nguoi dung',
            'fullname.min' => 'Ten nguoi dung phai co it nhat :min ki tu',
            'fullname.max' => 'Ten nguoi dung khong duoc qua :max ki tu',
            'email.required' => 'Vui long nhap email',
            'email.email' => 'Email khong dung dinh dang',
            'email.unique' => 'Email da ton tai',
        ];
        $request->validate($rules, $message);

        $dataInsert = [
            $request->fullname,
            $request->email,
            date("Y-m-d H:i:s"),
        ];
        $this->users->addUser($dataInsert);
        return redirect()->route("user.list")->with("msg", "Them nguoi dung thanh cong");
    }

    public function editUser($id = 0, Request $request)
    {
        $title = "Cap nhat thong tin nguoi dung";
        if (!empty($id)) {
            $userDetail = $this->users->getUserDetail($id);
            if (!empty($userDetail[0])) {
                $request->session()->put('id', $id); // Luu id vao session de dung khi POST, vi van de bao mat
                $userDetail = $userDetail[0];
            } else {
                return redirect()->route("user.list")->with("msg", "Nguoi dung khong ton tai");
            }
            return view("client.user.edit", compact("title", "userDetail"));
        } else {
            return redirect()->route("user.list")->with("msg", "Lien ket ID khong ton tai");
        }
    }

    public function handleEditUser(Request $request)
    {
        $id = $request->session()->get('id'); // Lay id tu session, lien quan den van de bao mat
        if (empty($id)) {
            return back()->with("msg", "Lien ket ID khong ton tai");
        }
        $rules = [
            'fullname' => 'required | min:5 | max:255',
            'email' => 'required | email | unique:user,email,' . $id, // unique:ten_bang
            // unique:ten_bang,ten_cot,giatri_cot_khong_muon_kiem_tra
        ];
        $message = [
            'fullname.required' => 'Vui long nhap ten nguoi dung',
            'fullname.min' => 'Ten nguoi dung phai co it nhat :min ki tu',
            'fullname.max' => 'Ten nguoi dung khong duoc qua :max ki tu',
            'email.required' => 'Vui long nhap email',
            'email.email' => 'Email khong dung dinh dang',
            'email.unique' => 'Email da ton tai',
        ];
        $request->validate($rules, $message);
        $dataUpdate = [
            $request->fullname,
            $request->email,
            date("Y-m-d H:i:s"),
        ];
        $this->users->updateUser($dataUpdate, $id);
        return redirect()->route("user.edit", ['id' => $id])->with("msg", "Cap nhat nguoi dung thanh cong");
    }

    public function deleteUser($id = 0)
    {
        if (!empty($id)) {
            $userDetail = $this->users->getUserDetail($id);
            if (!empty($userDetail[0])) {
                $deleteStatus = $this->users->deleteUser($id);
                $deleteStatus ? $msg = "Xoa nguoi dung thanh cong" : $msg = "Xoa nguoi dung that bai";
            } else {
                $msg = "Nguoi dung khong ton tai";
            }
        } else {
            $msg = "Lien ket ID khong ton tai";
        }
        return redirect()->route("user.list")->with("msg", $msg);
    }

}
