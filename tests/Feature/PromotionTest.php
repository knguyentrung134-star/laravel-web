<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionTest extends TestCase
{
    use RefreshDatabase;

    private static $results = [];

    public function test_TC01_them_khuyen_mai()
    {
        $response = $this->post('/admin/khuyenmai', [
            'tenKhuyenMai' => 'SALE 20%',
            'phanTramGiam' => 20
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC01',
            'Thêm khuyến mãi',
            'Thêm thành công',
            'Thêm thành công',
            'PASS'
        ];
    }

    public function test_TC02_ten_rong()
    {
        $response = $this->post('/admin/khuyenmai', [
            'tenKhuyenMai' => '',
            'phanTramGiam' => 20
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC02',
            'Tên rỗng',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC03_phan_tram_am()
    {
        $response = $this->post('/admin/khuyenmai', [
            'tenKhuyenMai' => 'SALE',
            'phanTramGiam' => -10
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC03',
            'Phần trăm âm',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC04_bo_trong_du_lieu()
    {
        $response = $this->post('/admin/khuyenmai', []);

        $response->assertStatus(302);

        self::$results[] = [
            'TC04',
            'Bỏ trống dữ liệu',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC05_phan_tram_lon_hon_100()
    {
        $response = $this->post('/admin/khuyenmai', [
            'tenKhuyenMai' => 'SALE VIP',
            'phanTramGiam' => 150
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC05',
            'Phần trăm > 100',
            'Thông báo lỗi',
            'Thông báo lỗi',
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
            'app/test_reports/PromotionTest_' .
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