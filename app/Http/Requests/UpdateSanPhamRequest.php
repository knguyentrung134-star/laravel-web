<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSanPhamRequest extends FormRequest
{
    public function rules()
    {
        return [
            'tenSanPham' => 'required|string|max:100',
            'moTa'       => 'nullable|string|max:300',
            'soLuong'    => 'required|integer|min:0',
            'gia'        => 'required|numeric|min:0',
            'hinh_anh'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'trangThai'  => 'required|in:Còn hàng,Hết hàng',
        ];
    }
}