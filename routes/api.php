<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\controllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get("/website", [controllerApi::class, "website"]);
Route::get("/lienhe", [controllerApi::class, "lienhe"]);
Route::middleware('auth:sanctum')->group(function () {
    Route::get("/me", [AuthController::class, "me"]);
    Route::post('/user/logout', [AuthController::class, 'logout']);
    Route::get("/manager", [AuthController::class, "getManager"]);
    Route::post('/user/themsanpham', [AuthController::class, 'themsanpham']);
    Route::post('/user/suasanpham', [AuthController::class, 'suasanpham']);
    Route::post('/user/xoasanpham', [AuthController::class, 'xoasanpham']);
    Route::get('/user/category', [AuthController::class, 'category']);
    Route::get('/user/getAllProduct', [AuthController::class,'getAllProduct' ]);
    Route::post('//user/deleteProduct', [AuthController::class,'deleteProduct' ]);
    Route::post('/user/deleteProducts', [AuthController::class,'deleteProducts' ]);
    Route::post('/user/addProduct',[AuthController::class,'addProduct']);
    Route::get('/user/getProduct',[AuthController::class,'getProduct']);
    Route::get('/user/checkout/{id}',[AuthController::class,'checkout']);
    Route::post('/user/payment',[AuthController::class,'payment']);
    Route::post('/user/CreateBlog',[AuthController::class,'CreateBlog']);
    Route::post('/user/upImg',[AuthController::class,'upImg']);
});
Route::get("/GetProduct", [controllerApi::class, "GetProduct"]);
Route::post('/user/login', [AuthController::class, 'login']);
Route::get("/website", [controllerApi::class, "website"]);
Route::get("/lienhe", [controllerApi::class, "lienhe"]);
Route::post('/user/register', [AuthController::class, 'register']);
Route::get("/sanphamnoibat",[controllerApi::class,'Sanphamnoibat']);
Route::get("/product_details/{id}",[controllerApi::class,'product_details']);
Route::get("/category",[controllerApi::class,'category']);
Route::get("/AllProduct",[controllerApi::class,'AllProduct']);
Route::get("/Product/Search",[controllerApi::class,'Search']);
Route::get("/websiteTitle",[controllerApi::class,'websiteTitle']);
Route::get("/blog_details/{id}",[controllerApi::class,'blog_details']);
Route::get("/blog",[controllerApi::class,'blog']);
