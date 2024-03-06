<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct()
    {

    }
    //Hien thi danh sach danh muc method GET
    public function index(Request $request)
    {
        // $allrequest = $request->all();
        // dd($allrequest);
        // $name = $request->input("name");
        // dd($name);
        // $query = $request->query();
        // dd($query);
        $name = $request->name;
        dd($name);
        // return view("client.categories.list");
    }

    //Lay ra 1 danh muc method GET
    public function getCategory($id)
    {
        return view("client.categories.edit", compact("id"));
    }

    //Sua 1 danh muc method POST
    public function updateCategory($id)
    {
        return "Sua danh muc co id = $id thanh cong!";
    }

    //show form them du lieu method GET
    public function showForm()
    {

        return view("client.categories.add");
    }

    //Xu ly them du lieu method POST
    public function handleAddCategory(Request $request)
    {
        // $name = $request->name;
        // $email = $request->email; // lay du lieu tu form
        // // dd($name);
        // dd($email);
        $request->flash();//luu lai du lieu vua nhap, chi ton tai trong 1 session, sau khi refresh trang se mat
        return redirect(route("categories.add"))->with("status", "Them danh muc thanh cong!");
        // return "Them danh muc thanh cong!";
    }

    //Xoa 1 danh muc method DELETE
    public function deleteCategory($id)
    {
        return "Xoa danh muc co id = $id thanh cong!";
    }

    //hien thi form upload file
    public function uploadFile()
    {
        return view("client.categories.file");
    }

    public function handleUploadFile(Request $request)
    {
        if ($request->hasFile("file")) {
            if ($request->file("file")->isValid()) {
                $file = $request->file("file");
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs("public", $filename);
                return "Upload file thanh cong!";
            } else {
                return "File khong hop le!";
            }
        } else {
            return "Chua co file!";
        }
    }
}
