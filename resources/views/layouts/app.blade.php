<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Băng Đĩa Nhạc - Shop')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/laravel.css') }}">

    <style>
        .alert { animation: slideDown 0.4s ease; }
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">🎵 Băng Đĩa Nhạc</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">

                <!-- SEARCH -->     
                <li class="nav-item mx-3">
                    <form class="d-flex" action="{{ route('search') }}" method="GET">
                        <input class="form-control me-2" type="search" name="q"
                               placeholder="Tìm kiếm..." value="{{ request('q') }}">
                        <button class="btn btn-outline-info">🔍</button>
                    </form>
                </li>

                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">🏠 Trang chủ</a></li>

                    @if(auth()->user()->vaiTro === 'Customer')
                        <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">🛒 Giỏ hàng</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('order.history') }}">📜 Lịch sử đơn hàng</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('trahang.index') }}">🔄 Trả hàng</a></li>
                    @endif

                    @if(auth()->user()->vaiTro === 'Admin')
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">👤 Quản lý</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.bao-cao-doanh-thu') }}">📊 Báo cáo</a></li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            {{ auth()->user()->tenNguoiDung }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- THÔNG BÁO -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show m-3">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- CONTENT -->
<div class="container mt-3">
    @yield('content')
</div>

<!-- ==================== POPUP KHUYẾN MÃI ==================== -->
@if(isset($khuyenMai) && $khuyenMai instanceof \Illuminate\Database\Eloquent\Collection && $khuyenMai->isNotEmpty())
<div id="welcomePopup" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">🎵 Chào mừng đến Băng Đĩa Nhạc</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="fw-bold text-success fs-5 mb-3">🎉 Khuyến mãi đang diễn ra:</p>

                @foreach($khuyenMai as $km)
                    <div class="mb-3 p-3 border rounded bg-light">
                        <strong>{{ $km->moTaKhuyenMai ?? 'Ưu đãi đặc biệt' }}</strong><br>
                        <span class="text-danger fw-bold fs-5">Giảm {{ $km->phanTramGiam ?? 0 }}%</span><br>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($km->ngayBatDau)->format('d/m/Y') }}
                            →
                            {{ \Carbon\Carbon::parse($km->ngayKetThuc)->format('d/m/Y') }}
                        </small>
                    </div>
                @endforeach
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Mua ngay 🎧</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- CHAT -->
<div id="chat-toggle" onclick="toggleChat()">💬</div>
<div id="chat-box">
    <div class="chat-header">
        🎧 Hỗ trợ
        <span onclick="toggleChat()">✖</span>
    </div>
    <div class="chat-body" id="chat-body">
        <div class="bot">Xin chào 👋</div>
    </div>
    <div class="chat-footer">
        <input type="text" id="chat-input" placeholder="Nhập..." onkeypress="handleEnter(event)">
        <button onclick="sendMessage()">Gửi</button>
    </div>
</div>

<!-- HOTLINE -->
<div id="hotline-btn" onclick="togglePhone()">📞</div>
<div id="phone-popup">📞 0901 234 567</div>

<!-- FOOTER -->
<footer class="text-white py-4 mt-5" style="background:#020617;">
    <div class="container d-flex justify-content-between">
        <div>🎵 Băng Đĩa Nhạc</div>
        <div>&copy; {{ date('Y') }}</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// CHAT
function toggleChat() {
    document.getElementById("chat-box").classList.toggle("active");
}
function sendMessage() {
    let input = document.getElementById("chat-input");
    let body = document.getElementById("chat-body");
    if (!input.value.trim()) return;
    body.innerHTML += `<div class="user">${input.value}</div>`;
    body.scrollTop = body.scrollHeight;
    setTimeout(() => {
        body.innerHTML += `<div class="bot">Shop sẽ phản hồi 🎧</div>`;
        body.scrollTop = body.scrollHeight;
    }, 500);
    input.value = "";
}
function handleEnter(e) {
    if (e.key === "Enter") sendMessage();
}

// HOTLINE
function togglePhone() {
    document.getElementById("phone-popup").classList.toggle("show");
}

// AUTO HIDE ALERT
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        let bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 4000);

// POPUP KHUYẾN MÃI
document.addEventListener('DOMContentLoaded', function () {
    let popupEl = document.getElementById('welcomePopup');
    if (popupEl && !sessionStorage.getItem('shownPopup')) {
        let popup = new bootstrap.Modal(popupEl);
        popup.show();
        sessionStorage.setItem('shownPopup', 'true');
    }
});
</script>

@stack('scripts')
@yield('scripts')
</body>
</html>