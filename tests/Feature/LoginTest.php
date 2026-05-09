<?php

namespace Tests\Feature;

use App\Models\NguoiDung;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private static $results = [];

    protected function setUp(): void
    {
        parent::setUp();

        NguoiDung::create([
            'tenNguoiDung' => 'admin',
            'email' => 'admin@gmail.com',
            'matKhau' => Hash::make('123456')
        ]);
    }

    public function test_TC01_dang_nhap_hop_le()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '123456'
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC01',
            'Đăng nhập đúng',
            'Đăng nhập thành công',
            'Đăng nhập thành công',
            'PASS'
        ];
    }

    public function test_TC02_sai_mat_khau()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '111111'
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC02',
            'Sai mật khẩu',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC03_email_rong()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '123456'
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC03',
            'Email rỗng',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC04_mat_khau_rong()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => ''
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC04',
            'Mật khẩu rỗng',
            'Thông báo lỗi',
            'Thông báo lỗi',
            'PASS'
        ];
    }

    public function test_TC05_tai_khoan_khong_ton_tai()
    {
        $response = $this->post('/login', [
            'email' => 'abc@gmail.com',
            'password' => '123456'
        ]);

        $response->assertStatus(302);

        self::$results[] = [
            'TC05',
            'Tài khoản không tồn tại',
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
            'app/test_reports/LoginTest_' .
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