<?php

use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\MasterLayoutController;
use Illuminate\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", function () {
    return "Hello World!";
})->name("home");
//Client Routes
Route::prefix("categories")->group(function () {
    //Danh sach chuyen muc
    Route::get("/", [CategoriesController::class, "index"])->name("categories.list");
    //Lay ra 1 chuyen muc (Ap dung show form sua chuyen muc)
    Route::get("/edit/{id}", [CategoriesController::class, "getCategory"])->name("categories.edit");
    //Sua 1 chuyen muc
    Route::post("/edit/{id}", [CategoriesController::class, "updateCategory"]);
    //Show form them chuyen muc
    Route::get("/add", [CategoriesController::class, "showForm"])->name("categories.add");
    //Xu ly them chuyen muc
    Route::post("/add", [CategoriesController::class, "handleAddCategory"]);
    //Xoa 1 chuyen muc
    Route::delete("/delete/{id}", [CategoriesController::class, "deleteCategory"]);
    //Upload file
    Route::get("/upload", [CategoriesController::class, "uploadFile"])->name("categories.upload");
    //Xu ly file sau khi upload
    Route::post("/upload", [CategoriesController::class, "handleUploadFile"]);
});


Route::prefix("/masterlayout")->group(function () {
    Route::get("/", [MasterLayoutController::class, "mainWebsite"])->name("home");
    Route::get("/products", [MasterLayoutController::class, "products"])->name("products");
    Route::get("/add", [MasterLayoutController::class, "getAdd"])->name("add");
    Route::post("/add", [MasterLayoutController::class, "postAdd"])->name("post-add");
});

//Users routes
Route::prefix("user")->group(function () {
    Route::get("/", [UserController::class, "index"])->name("user.list");
    Route::get("/add", [UserController::class, "addNewUser"])->name("user.add");
    Route::post("/add", [UserController::class, "handleAddUser"]);
    Route::get("/edit/{id}", [UserController::class, "editUser"])->name("user.edit");
    Route::post("/update", [UserController::class, "handleEditUser"])->name("user.update");
    Route::get("/delete/{id}", [UserController::class, "deleteUser"])->name("user.delete");
});

//Admin Routes

Route::middleware("adminpermission")->prefix("admin")->group(function () {
    Route::resource("/products", ProductsController::class);
});

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::any('/home', function (Request $request) {
//     echo request()->method();
// });

// Route::get('/home', function () {
//     return view('home');
// });

// Route::get('/home/{slug}-{id}.html', function ($slug = null, $id = null) {
//     $content = "this id is: " . $id . "<br/>";
//     $content .= "this slug is: " . $slug;
//     return $content;
// })->where('slug', '[a-z0-9-]+')->where('id', '[0-9]+');
