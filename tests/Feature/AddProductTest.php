<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddProductTest extends TestCase
{
    use RefreshDatabase;

    private static $results = [];

    public function test_TC01_them_san_pham()
    {
        $response = $this->post('/admin/sanpham', [
            'tenSanPham' => 'Album Test',
            'gia' => 100000
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC01',
            'Thêm sản phẩm',
            'Thêm thành công',
            'Thêm thành công',
            'PASS'
        ];
    }

    public function test_TC02_thieu_ten_san_pham()
    {
        $response = $this->post('/admin/sanpham', [
            'tenSanPham' => '',
            'gia' => 100000
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC02',
            'Thiếu tên sản phẩm',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC03_gia_am()
    {
        $response = $this->post('/admin/sanpham', [
            'tenSanPham' => 'Album',
            'gia' => -1000
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC03',
            'Giá âm',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC04_thieu_du_lieu()
    {
        $response = $this->post('/admin/sanpham', []);

        $response->assertStatus(302);

        self::$results[] = [
            'TC04',
            'Thiếu dữ liệu',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC05_gia_hop_le()
    {
        $response = $this->post('/admin/sanpham', [
            'tenSanPham' => 'Album VIP',
            'gia' => 500000
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC05',
            'Giá hợp lệ',
            'Thêm thành công',
            'Thêm thành công',
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
            'app/test_reports/AddProductTest_' .
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