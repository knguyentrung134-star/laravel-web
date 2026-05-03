<?php

namespace Tests\Feature;

use App\Models\SanPham;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Tắt event để tránh lỗi foreign key
        SanPham::$withoutEvents = true;

        SanPham::create([
            'idSanPham'  => 1,
            'tenSanPham' => 'Đĩa CD Nhạc Cách Mạng',
            'gia'        => 120000,
            'moTa'       => 'Nhạc cách mạng hay',
            'trangThai'  => 'Còn hàng',
            'theLoai'    => 'Nhạc Cách Mạng',
        ]);

        SanPham::create([
            'idSanPham'  => 2,
            'tenSanPham' => 'Đĩa CD Nhạc Trẻ',
            'gia'        => 95000,
            'moTa'       => 'Album nhạc trẻ 2025',
            'trangThai'  => 'Còn hàng',
            'theLoai'    => 'Nhạc Trẻ',
        ]);
    }

    /** @test */
    public function user_can_search_by_keyword()
    {
        $response = $this->get('/search?q=cách mạng');
        $response->assertStatus(200);
        $response->assertSee('Nhạc Cách Mạng');
    }

    /** @test */
    public function search_is_case_insensitive_and_partial_match()
    {
        $response = $this->get('/search?q=nhạc');
        $response->assertSee('Nhạc Cách Mạng');
        $response->assertSee('Nhạc Trẻ');
    }

    /** @test */
    public function empty_search_shows_all_products()
    {
        $response = $this->get('/search');
        $response->assertSee('Nhạc Cách Mạng');
        $response->assertSee('Nhạc Trẻ');
    }
}
