<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\website;
use App\Models\blogs;
use Exception;
use App\Models\lienhe;
use App\Models\product;
use App\Models\category;

class controllerApi extends Controller
{

    public function website()
    {
        try {

            $data = website::data();
            if (!$data) {
                return response()->json([
                    'data' => "trống"
                ], 404);
            }
            return response()->json([
                'data' => $data
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'message' => $error
            ], 500);
        }
    }
    public function lienhe()
    {
        try {
            $data = lienhe::data();
            if (!$data) {
                return response()->json([
                    "message" => "không có dữ liệu"
                ], 404);
            }
            return response()->json([
                "data" => $data
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "message" => $error
            ], 500);
        }
    }
    public function GetProduct()
    {

        $product = Product::all();
        return response()->json([
            "data" => $product,
        ], 200);
    }
    public function register() {}
    public function Sanphamnoibat()
    {
        try {
            $category = category::where("id", 1)->with('product.img')->get();

            return response()->json([
                "data" => $category,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "error" => $error->getMessage()
            ], 500);
        }
    }
    public function product_details($id)
    {
        try {
            $product_details = Product::with('Img', 'Sizes',  'Category')->find($id);
            if (!$product_details) {
                return response()->json([
                    'status' => 404,
                    "message" => "không có dữ liệu"
                ], 404);
            }
            return response()->json([
                'status' => '200',
                'message' => "success",
                'data' => $product_details
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "error" => $error->getMessage()
            ], 500);
        }
    }
    public function category()
    {
        try {
            $category = Category::all();
            return response()->json([
                "satus" => "thành công",
                "data" => $category,
            ], 200);
        } catch (Exception $error) {
            return response([
                "message" => $error->getMessage(),
            ], 500);
        }
    }
    public function AllProduct()
    {
        $data = product::with('Img')->with('Sizes', 'Category')->get();
        try {
            return response()->json([
                "status" => "success",
                "data" => $data,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "message" => $error->getMessage()
            ], 500);
        }
    }
    public function Search(Request $request)
    {
        try {
            $query = $request->input('query'); // Lấy từ khóa tìm kiếm từ query params

            // Tìm kiếm sản phẩm dựa trên tên hoặc mô tả sản phẩm
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('describe', 'LIKE', "%{$query}%")
                ->with('Img')
                ->get();

            // Trả về kết quả tìm kiếm
            return response()->json([
                "data" =>
                $products
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "message" => $error->getMessage()
            ], 500);
        }
    }
    public function blog_details($id)
    {
        try {
            $blog = blogs::with("blog_img")->find($id);
            return response()->json([
                "status" => "error",
                "data" => $blog,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "status" => "error",
                "message" => $error->getMessage()
            ], 500);
        }
    }
    public function blog(){
        try{
            $blogRecords = blogs::select('description')->get(); // Lấy tất cả mô tả
            $results = [];
            
            foreach ($blogRecords as $blogRecord) { // Đổi tên biến để tránh xung đột
                $description = $blogRecord->description; // Lấy mô tả
            
                // Sử dụng biểu thức chính quy để tìm <img ... /> và phần còn lại
                preg_match('/(<img[^>]*>)(.*)/', $description, $matches); // Sửa dấu '/' thành '>' cho đúng định dạng thẻ img
            
                // Kiểm tra nếu tìm thấy
                if (isset($matches[1])) {
                    $imgPart = trim($matches[1]); // Lấy phần <img ... />
                    $remainingPart = trim($matches[2]); // Lấy phần còn lại
                    
                    // Thêm kết quả vào mảng
                    $results[] = [
                        'img' => $imgPart,
                        'remaining' => $remainingPart
                    ];
                }
            }
            return response()->json([
                "status" => "success",
                "data"=>$results
            ],200);
        }catch(Exception $error){
            return response()->json([
                "status" => "error",
                "message" => $error->getMessage()
            ],500);
        }
    }
}
