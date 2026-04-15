<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $donHangs = DonHang::where('idKhachHang', Auth::user()->khachHang->idKhachHang ?? 0)
                    ->with(['chiTietDonHangs.sanPham'])
                    ->orderBy('idDonHang', 'desc')
                    ->paginate(10);

        return view('customer.order_history', compact('donHangs'));
    }
}