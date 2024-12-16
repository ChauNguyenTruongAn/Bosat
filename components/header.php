<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A&V</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/master.css">
    <style>
        .active-header {
            text-decoration: underline;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-between py-3 border-bottom">
            <a href="/" class="d-flex align-items-center mb-2 mb-md-0 text-dark text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="#bootstrap"></use>
                </svg>
                <span class="fs-4">A&V</span>
            </a>

            <nav class="navbar navbar-expand-md">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item"><a href="/" class="nav-link">Trang chủ</a></li>
                        <li class="nav-item"><a href="/pages/products.php" class="nav-link">Sản phẩm</a></li>
                        <li class="nav-item"><a href="/pages/about.php" class="nav-link">Giới thiệu</a></li>
                        <li class="nav-item"><a href="/pages/contact.php" class="nav-link">Liên lạc</a></li>
                        <li class="nav-item"><a href="/pages/reviews.php" class="nav-link">Đánh giá</a></li>
                    </ul>
                </div>
            </nav>

            <div class="text-end">
                <?php
                session_start();
                if (isset($_SESSION['TenDayDu'])) {
                    echo '<div class="dropdown d-inline">';
                    echo '<span class="me-3" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">Xin chào, ' . htmlspecialchars($_SESSION['TenDayDu']) . '</span>';
                    echo '<ul class="dropdown-menu" aria-labelledby="userDropdown">';
                    echo '<li><a class="dropdown-item" href="/pages/profile.php">Xem hồ sơ</a></li>';
                    echo '<li><a class="dropdown-item" href="/services/logout.php">Đăng xuất</a></li>';
                    echo '</ul>';
                    echo '</div>';
                    echo '<a href="/pages/cart.php" class="link-info ms-2">Giỏ hàng</a>';
                } else {
                    echo '<a href="/pages/login.php"><button type="button" class="btn btn-outline-primary me-2">Đăng nhập</button></a>';
                    echo '<a href="/pages/register.php"><button type="button" class="btn btn-primary">Đăng ký</button></a>';
                }
                ?>
            </div>
        </header>
    </div>

    <script>
        const currentUrl = window.location.pathname;

        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentUrl) {
                link.classList.add('active-header');
            }
        });
    </script>