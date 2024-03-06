<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Rules\Upercase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class MasterLayoutController extends Controller
{
    public $data = [];
    public function index()
    {

    }

    public function mainWebsite()
    {
        $this->data["title"] = "Home Page";
        $this->data["message"] = "Dat hang thanh cong mat rui";
        $user = DB::select("SELECT * FROM user WHERE email = :email", ["email" => "thungan160903@gmail.com"]);
        // dd($user);
        return view("client.home", $this->data);

    }

    public function products()
    {
        $this->data["title"] = "San Pham";
        return view("client.products", $this->data);
    }

    public function getAdd()
    {
        $this->data["title"] = "Them San Pham";
        return view("client.add", $this->data);
    }
    public function postAdd(Request $request)// hoac co the su dung ProductRequest $request de check validate 
    {
        // dd($request->all());
        $rule = [
            "name" => [
                "required",
                "min:5",
            ],//hoac co the dung new UperCase (custom Rule trong file Rules)
            "price" => ["required", "numeric"],
        ];
        $message = [
            "name.required" => "Vui long nhap ten san pham",
            "name.min" => "Ten phai co it nhat :min ky tu",
            "price.required" => "Vui long nhap gia san pham",
            "price.numeric" => "Gia san pham phai la so",
        ];
        $atribute = [
            "name" => "Ten san pham",
            "price" => "Gia san pham",
        ];
        // $validator = Validator::make($request->all(), $rule, $message, $atribute);
        // $validator->validate();
        $request->validate($rule, $message);
        return response()->json(["status" => "Success"]);
        // if ($validator->fails()) {
        //     $validator->errors()->add("msg", "Co loi xay ra, vui long kiem tra lai");
        // } else {
        //     return redirect()->route("products")->with('msg', 'Them san pham thanh cong');// dien name route vao day
        // }

        // request()->flash();
        // return back()->withErrors($validator);
    }
}
