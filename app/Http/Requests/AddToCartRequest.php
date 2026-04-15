<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function rules()
    {
        return [
            'idSanPham' => 'required|exists:sanpham,idSanPham',
            'soLuong'   => 'nullable|integer|min:1',
        ];
    }
}