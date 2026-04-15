<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Băng Đĩa Nhạc - Shop')</title>

    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; }

        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">🎵 Băng Đĩa Nhạc</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">

                <!-- SEARCH -->
                <li class="nav-item mx-3">
                    <form class="d-flex" action="{{ route('search') }}" method="GET">
                        <input class="form-control me-2" 
                               type="search" 
                               name="q" 
                               placeholder="Tìm kiếm băng đĩa..." 
                               value="{{ request('q') }}" 
                               style="width: 250px;">
                        <button class="btn btn-outline-light" type="submit">🔍</button>
                    </form>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">🛒 Giỏ hàng</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('order.history') }}">
                            📜 Lịch sử mua hàng
                        </a>
                    </li>

                    <!-- ADMIN -->
                    @if(auth()->user()->vaiTro === 'Admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Quản trị</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.khuyenmai.index') }}">
                                🎟️ Khuyến mãi
                            </a>
                        </li>

                        <!-- ✅ FIX: đưa vào đúng chỗ -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.bao-cao-doanh-thu') }}">
                                <i class="fas fa-chart-bar"></i> Báo cáo
                            </a>
                        </li>
                    @endif

                    <!-- USER -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            {{ auth()->user()->tenNguoiDung }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Đăng ký</a>
                    </li>
                @endguest

            </ul>
        </div>
    </div>
</nav>

<!-- ALERT -->
<div class="container mt-3">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<!-- CONTENT -->
<div class="container mt-2">
    @yield('content')
</div>

<!-- FOOTER -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container d-flex justify-content-between">
        <div>
            <h5>🎵 Băng Đĩa Nhạc</h5>
            <p class="small">Cửa hàng chính hãng</p>
        </div>
        <div class="text-end">
            <p class="small mb-0">&copy; {{ date('Y') }} BTL Laravel</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>