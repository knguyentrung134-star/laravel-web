<?php   
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;

class KhachHangController extends Controller
{
    // 📌 Danh sách khách hàng
    public function index(Request $request)
    {
        $query = NguoiDung::where('vaiTro', 'Customer');

        // 🔍 tìm kiếm
        if ($request->q) {
            $query->where('tenNguoiDung', 'like', '%' . $request->q . '%');
        }

        $khachHangs = $query->paginate(10);

        return view('admin.khachhang.index', compact('khachHangs'));
    }

    // 📌 Form thêm
    public function create()
    {
        return view('admin.khachhang.create');
    }

    // 📌 Lưu
    public function store(Request $request)
    {
        $request->validate([
            'tenNguoiDung' => 'required',
            'email' => 'required|email|unique:nguoidung,email',
            'matKhau' => 'required|min:6'
        ]);

        NguoiDung::create([
            'tenNguoiDung' => $request->tenNguoiDung,
            'email' => $request->email,
            'matKhau' => bcrypt($request->matKhau), // 🔥 đúng field
            'vaiTro' => 'Customer'
        ]);

        return redirect()->route('admin.khachhang.index')
            ->with('success', 'Thêm khách hàng thành công');
    }

    // 📌 Sửa
    public function edit($id)
    {
        $kh = NguoiDung::findOrFail($id);
        return view('admin.khachhang.edit', compact('kh'));
    }

    // 📌 Update
    public function update(Request $request, $id)
    {
        $kh = NguoiDung::findOrFail($id);

        $request->validate([
            'tenNguoiDung' => 'required',
            'email' => 'required|email'
        ]);

        $kh->tenNguoiDung = $request->tenNguoiDung;
        $kh->email = $request->email;

        // 🔥 nếu nhập mật khẩu mới thì update
        if ($request->filled('matKhau')) {
            $kh->matKhau = bcrypt($request->matKhau);
        }

        $kh->save();

        return redirect()->route('admin.khachhang.index')
            ->with('success', 'Cập nhật thành công');
    }

    // 📌 Xóa
    public function destroy($id)
    {
        NguoiDung::destroy($id);
        return back()->with('success', 'Đã xóa khách hàng');
    }
}