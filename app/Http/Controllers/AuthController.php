<?php

namespace App\Http\Controllers;

use App\Models\address;
use App\Models\blogs;
use App\Models\Img;
use App\Models\blog_img;
use App\Models\User;
use App\Models\orders;
use App\Models\category;
use App\Models\product;
use App\Models\sizes;
use App\Models\cart;
use Cloudinary\Transformation\Quality;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;
use PHPUnit\Metadata\Uses;

use function PHPSTORM_META\map;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                "email" => "required|email",
                "password" => "required"
            ]);
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'message' => 'Tài khoản không tồn tại'
                ], 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    "message" => "Password không chính xác"
                ], 403);
            }

            Auth::login($user);
            $role = Auth::user()->role;
            $TokenResult = $user->createToken("authToken")->plainTextToken;

            return response()->json([
                'status' => 'success',
                'access_token' => $TokenResult,
                'token_type' => 'Bearer',
                'Role' => $role,
            ]);
        } catch (Exception $error) {
            return response()->json([
                "message" => $error->getMessage(),
            ], 500); // 500 - Internal Server Error
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                "name" => "required",
                "email" => "required|email",
                "password" => "required",
                "confirm_password" => "required|same:password", // kiểm tra mật khẩu xác nhận
            ]);

            $checkEmail = User::where("email", $request->email)->first();
            if ($checkEmail) {
                return response()->json([
                    'message' => 'Email đã tồn tại'
                ], 403);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 1, // Mặc định role là 1 nếu không truyền từ request
            ]);

            return response()->json([
                "status" => "success",
                "message" => "Đăng ký thành công",
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "status" => "error",
                "message" => $error->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete(); // Xóa tất cả các token của người dùng
            return response()->json(['message' => 'Logged out successfully'], 200);
        }

        return response()->json(['message' => 'User not authenticated'], 401);
    }
    public function me()
    {

        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            "message" => "đăng nhập thành công"
        ], 200);
    }

    public function Taolienhe() {}
    public function themsanpham(Request $request)
    {
        if (Auth::user()->role != 2) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        DB::beginTransaction();
        try {
            $request->validate([
                "name" => "required",
                "gia" => "required",
                "describe" => "required",
                "category_id" => "required",
                'img1' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'img2' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
                'img3' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
                'img4' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
                'sizes' => 'required|array',
                'sizes.*.size' => 'required|string|max:10',
                'sizes.*.quantity' => 'required|integer|min:1',
                'sizes.*.colors' => 'required',
            ]);

            $product = new product();
            $product->name = $request->name;
            $product->gia = $request->gia;
            $product->describe = $request->describe;
            $product->category_id = $request->category_id;
            $product->save();
            $productID = $product->id;

            $images = ['img1', 'img2', 'img3', 'img4'];
            $img = new Img();

            foreach ($images as $image) {
                $uploadedFileUrl = $this->upload($request->file($image));
                $img->{$image} = $uploadedFileUrl;
                $img->product_id = $productID;
                $uploadedFileUrls[] = $uploadedFileUrl;
                $img->save();
            }
            $product->save();
            foreach ($request->input('sizes') as $size) {
                sizes::create([

                    'sizes' => $size['size'],
                    'quanlity' => $size['quantity'],
                    'colors' => $size['colors'],
                    'product_id' => $productID,
                ]);
            }

            DB::commit();

            return response()->json(['status' => "success"], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function upload($image)
    {
        // Upload ảnh và trả về đường dẫn
        return Cloudinary::upload($image->getRealPath())->getSecurePath();
    }

    public function suasanpham(Request $request)
    {

        if (Auth::user()->role != 2) {
            return response()->json([
                "error" => "Unauthorized",
            ], 401);
        }
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required|integer',
                'name' => 'required',
                'gia' => 'required',
                'code' => 'required',
                'danhmuc' => 'required',
                'img1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img2' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img3' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img4' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048,',

            ]);
            $product = product::find($request->id);
            $product->name = $request->name;
            $product->gia = $request->gia;
            $product->code = $request->code;
            $product->danhmuc = $request->danhmuc;
            $product->save();


            $img = Img::where('productID', $request->id)->first();
            if (!$img) {
                $img = new Img();
                $img->productID = $request->id;
            }
            $images = ['img1', 'img2', 'img3', 'img4'];
            foreach ($images as $image) {
                $uploadedFileUrl = $this->upload($request->file($image));
                $img->{$image} = $uploadedFileUrl;
                $uploadedFileUrls[] = $uploadedFileUrl;
                $img->save();
            }
            $product->save();
            DB::commit();

            return response()->json([
                "status" => "success",
            ], 200);
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                "error" => $error->getMessage()
            ], 500);
        }
    }
    public function getManager()
    {
        if (Auth::user()->role != 2) {
            return response()->json([
                "error" => "Unauthorized",
            ], 401);
        }
        $user = Auth::user()->id;
        $data = User::where("id", $user)->select('name', "email")->get();
        return response()->json([
            "data" => $data,
        ]);
    }
    public function category()
    {

        $data = Category::all();
        if (Auth::user()->role != 2) {
            return response()->json([
                "error" => "Unauthorized",
            ], 401);
        }
        return response()->json([
            "data" => $data,
        ], 200);
    }
    public function getAllProduct()
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
    public function deleteProduct(Request $request)
    {
        try {
            request()->validate([
                'id' => 'required'
            ]);
            $product = product::find($request->id)->delete();
            return response()->json([
                "message" => "Product deleted successfully",
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "message" => $error->getMessage()
            ], 500);
        }
    }
    public function deleteProducts(Request $request)
    {
        try {
            request()->validate([
                'id' => 'required|array',
                'id.*.id' => 'required|integer',
            ]);
            foreach ($request->input('id') as $id) {
                product::destroy($id);
            }
            return response()->json([
                "message" => "Product deleted successfully",
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "message" => $error->getMessage()
            ], 500);
        }
    }
    public function addProduct(Request $request)
    {
        if (!Auth::user()) {
            return response()->json([
                "error" => "Unauthorized",
            ], 401);
        }
        try {
            $request->validate([
                "quanlity" => "required|integer",
                "price" => "required|numeric",
                "product_id" => "required|exists:products,id",
                "size" => "required",
                "color" => "required",
            ]);

            // Tìm sản phẩm
            $product = product::find($request->product_id);

            // Kiểm tra nếu sản phẩm tồn tại
            if (!$product) {
                return response()->json([
                    "message" => "Product not found.",
                ], 404);
            }
            $id = Auth::user()->id;
            $addCart = cart::addCart($request->quanlity, $request->price, $request->product_id, $id, $request->size, $request->color);
            return response()->json([
                "status" => "success",
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "message" => $error->getMessage(),
            ], 500);
        }
    }
    public function getProduct()
    {
        try {
            $id = Auth::user()->id;
            $data = cart::where("user_id", $id)->with("product.img")->get();
            return response()->json([
                "data" => $data,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "message" => $error->getMessage()
            ], 500);
        }
    }
    public function checkout($id)
    {
        try {
            $product_details = Product::with('Sizes', 'colors', 'Category')->find($id);
            if ($product_details == null) {
                return response()->json([
                    "status" => "not found",
                ], 404);
            }
            return response()->json([
                "status" => "success",
                "data" => $product_details,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                "status" => "error",
                "message" => $error->getMessage(),
            ], 500);
        }
    }
    public function payment(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                "name" => "required",
                "size" => "required",
                "color" => "required",
                "quality" => "required",
                "price" => "required",
                "payment" => "required",
                "product_id" => "required",
                "thanhpho" => "required",
                "quanhuyen" => "required",
                "xaphuong" => "required",
                "chitiet" => "required|string",
                "nguoinhan" => "required",
                "sdt" => "required|numeric"
            ]);
            $user_id = Auth::user()->id;
            $product = product::getID($request->product_id);
            if (!$product) {
                return response()->json([
                    "status" => "not found",
                ], 404);
            }
            $order = orders::order($request->name, $request->size, $request->color, $request->quality, $request->price, $user_id, $request->payment, $request->product_id);
            $id = $order->id;
            $address = address::address($request->thanhpho, $request->quanhuyen, $request->xaphuong, $request->chitiet, $request->nguoinhan, $id, $request->sdt);
            DB::commit();
            return response()->json([
                "status" => "success",
            ], 200);
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                "status" => "error",
                "message" => $error->getMessage(),
            ], 500);
        }
    }
    public function CreateBlog(Request $request)
    {
        try {
            $request->validate([
                "title" => "required",
                "description" => "required",
            ]);
        } catch (Exception $error) {
            return response()->json([
                "status" => "error",
                "message" => $error->getMessage(),
            ], 500);
        }
    }
    public function upImg(Request $request)
    {
        DB::beginTransaction();
        try {
            // Xác thực dữ liệu
            $request->validate([
                
                'description' => 'required|string',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' // xác thực cho nhiều hình ảnh
            ]);

            // Tạo blog mới
            $blog = blogs::create([
               
                'description' => $request->description,
            ]);
            $id = $blog->id; // Lấy ID của blog mới tạo

            // Khởi tạo mảng để lưu đường dẫn ảnh đã tải lên
            $uploadedFileUrls = [];

            // Xử lý từng hình ảnh
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $uploadedFileUrl = $this->upload($image); // Tải lên từng hình ảnh
                    $uploadedFileUrls[] = $uploadedFileUrl; // Thêm đường dẫn vào mảng

                    // Lưu thông tin hình ảnh vào bảng blog_images
                    $img = new blog_img(); // Khởi tạo một đối tượng BlogImage
                    $img->imgBlog = $uploadedFileUrl; // Gán đường dẫn hình ảnh
                    $img->blog_id = $id; // Gán ID blog

                    $img->save(); // Lưu hình ảnh vào cơ sở dữ liệu
                }
            }

            DB::commit(); // Xác nhận giao dịch
            return response()->json([
                "status" => "success",
                "message" => "Blog created successfully!",
                "uploaded_images" => $uploadedFileUrls // Trả về đường dẫn hình ảnh đã tải lên
            ], 200);
        } catch (\Exception $error) {
            DB::rollBack(); // Quay lại giao dịch nếu có lỗi
            return response()->json([
                "status" => "error",
                "message" => $error->getMessage(),
            ], 500);
        }
    }
}
