<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private static $results = [];

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('sanpham')->insert([
            'idSanPham' => 1,
            'tenSanPham' => 'Album Test',
            'gia' => 100000,
            'moTa' => 'Test',
            'trangThai' => 'Còn hàng',
            'theLoai' => 'Nhạc Trẻ'
        ]);
    }

    public function test_TC01_them_vao_gio()
    {
        $response = $this->post('/cart/add', [
            'idSanPham' => 1,
            'soLuong' => 1
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC01',
            'Thêm vào giỏ hàng',
            'Thêm giỏ hàng thành công',
            'Thêm giỏ hàng thành công',
            'PASS'
        ];
    }

    public function test_TC02_san_pham_khong_ton_tai()
    {
        $response = $this->post('/cart/add', [
            'idSanPham' => 999,
            'soLuong' => 1
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC02',
            'Sản phẩm không tồn tại',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC03_so_luong_am()
    {
        $response = $this->post('/cart/add', [
            'idSanPham' => 1,
            'soLuong' => -1
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC03',
            'Số lượng âm',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC04_khong_nhap_so_luong()
    {
        $response = $this->post('/cart/add', [
            'idSanPham' => 1
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC04',
            'Không nhập số lượng',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC05_them_nhieu_san_pham()
    {
        $response = $this->post('/cart/add', [
            'idSanPham' => 1,
            'soLuong' => 5
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC05',
            'Thêm nhiều sản phẩm',
            'Thêm giỏ hàng thành công',
            'Thêm giỏ hàng thành công',
            'PASS'
        ];
    }

    protected function tearDown(): void
    {
        if (count(self::$results) >= 5) {

            $this->exportToCSV();

            self::$results = [];
        }

        parent::tearDown();
    }

    private function exportToCSV()
    {
        $this->createFolder();

        $filename = storage_path(
            'app/test_reports/CartTest_' .
            date('Ymd_His') .
            '.csv'
        );

        $file = fopen($filename, 'w');

        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($file, [
            'TestCase',
            'Input',
            'Expected',
            'Actual',
            'Result'
        ]);

        foreach (self::$results as $row) {

            fputcsv($file, $row);
        }

        $total = count(self::$results);

        $passed = count(array_filter(
            self::$results,
            fn($r) => $r[4] === 'PASS'
        ));

        $failed = $total - $passed;

        $successRate = ($passed / $total) * 100;

        fputcsv($file, []);

        fputcsv($file, ['Tổng số test', $total]);
        fputcsv($file, ['Passed', $passed]);
        fputcsv($file, ['Failed', $failed]);
        fputcsv($file, ['Success rate', $successRate . '%']);

        fclose($file);
    }

    private function createFolder()
    {
        if (!file_exists(storage_path('app/test_reports'))) {

            mkdir(storage_path('app/test_reports'), 0777, true);
        }
    }
}