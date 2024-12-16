<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>

    <section class="vh-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 text-black">

                    <div class="px-5 ms-xl-4">
                        <i class="fas fa-crow fa-2x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
                        <span class="h1 fw-bold mb-0"><a href="/" <a
                                style="text-decoration: none; color: black;">A&V</a></span>
                    </div>

                    <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

                        <form style="width: 23rem;" method="POST" action="">

                            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Đăng nhập</h3>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name='username'
                                    placeholder="Tên đăng nhập">
                                <label for="username">Tên đăng nhập</label>
                            </div>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Mật khẩu">
                                <label for="password">Mậtkhẩu</label>
                            </div>
                            <br>
                            <div class="pt-1 mb-4">
                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-info btn-lg btn-block"
                                    type="submit">Đăng nhập</button>
                            </div>

                            <p class="small mb-5 pb-lg-2"><a class="text-muted" href="#!">Quên mật khẩu?</a></p>
                            <p>Chưa có tài khoản? <a href="./register.php" class="link-info">Đăng ký</a></p>

                        </form>

                    </div>

                </div>
                <div class="col-sm-6 px-0 d-none d-sm-block">
                    <img src="https://cdn.pixabay.com/photo/2023/04/10/10/28/blue-tongue-skink-7913420_640.jpg"
                        alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
                </div>
            </div>
        </div>
    </section>




    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
</body>

</html>

<?php
session_start();

include("../config/config.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $sql = "
                SELECT KhachHang.MaKhachHang, TenDayDu, TenDangNhap, MatKhau, Email, SoDienThoai, DiaChi, AnhDaiDien, KhachHang.NgayTao, MaGioHang
                FROM KhachHang JOIN GioHang ON KhachHang.MaKhachHang = GioHang.MaKhachHang
                WHERE TenDangNhap = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result != null) {
            if (password_verify($password, $result['MatKhau'])) {
                $_SESSION['MaKhachHang'] = $result['MaKhachHang'];
                $_SESSION['TenDayDu'] = $result['TenDayDu'];
                $_SESSION['TenDangNhap'] = $result['TenDangNhap'];
                $_SESSION['Email'] = $result['Email'];
                $_SESSION['SoDienThoai'] = $result['SoDienThoai'];
                $_SESSION['DiaChi'] = $result['DiaChi'];
                $_SESSION['AnhDaiDien'] = $result['AnhDaiDien'];
                $_SESSION['NgayTao'] = $result['NgayTao'];
                $_SESSION['MaGioHang'] = $result['MaGioHang'];
                header("Location: /");
                exit();
            } else {
                echo "<script>alert('Thông tin đăng nhập không chính xác.');</script>";
            }
        } else {
            echo "<script>alert('Thông tin đăng nhập không chính xác');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Vui lòng điền đầy đủ thông tin.');</script>";
    }
}
?>