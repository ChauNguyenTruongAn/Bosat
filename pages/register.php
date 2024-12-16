<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/register.css">
</head>

<body>

    <div class="container">
        <div class="d-flex align-items-center">
            <div class="col-md-6 d-none d-md-block">
                <img src="https://cdn.pixabay.com/photo/2023/08/19/05/31/green-sea-turtle-8199770_640.jpg"
                    alt="Register image" class="w-100 h-100" style="object-fit: cover;">
            </div>
            <div class="col-md-6">
                <div class="form-container m-5">
                    <h2 class="form-title">Đăng ký</h2>
                    <form method="POST" action="">

                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="firstName" name='firstName'
                                        placeholder="Họ" required>
                                    <label for="firstName">Họ</label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="lastName" name='lastName'
                                        placeholder="Tên" required>
                                    <label for="lastName">Tên</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name='email' placeholder="Email"
                                required>
                            <label for="email">Email</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name='username'
                                placeholder="Tên đăng nhập" required>
                            <label for="username">Tên đăng nhập</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Mật khẩu" required>
                            <label for="password">Mật khẩu</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                placeholder="Nhập lại mật khẩu" required>
                            <label for="confirmPassword">Nhập lại mật khẩu</label>
                        </div>

                        <div class="d-flex justify-content-center mb-3">
                            <button class="btn btn-primary btn-lg" type="submit">Đăng ký</button>
                        </div>

                        <p class="text-center">Đã có tài khoản? <a href="./login.php" class="link-info">Đăng nhập</a>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <?php
    session_start();

    include("../config/config.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($password !== $confirmPassword) {
            echo "<script>alert('Mật khẩu không khớp.');</script>";
        } else {

            $sql = "SELECT * FROM KhachHang WHERE TenDangNhap = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $username);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result != null) {
                echo "<script>alert('Tên đăng nhập đã tồn tại.');</script>";
            } else {

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


                $sql = "INSERT INTO KhachHang (TenDayDu, TenDangNhap, MatKhau, Email) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $fullName = $lastName . " " . $firstName; // Sử dụng dấu chấm để nối chuỗi
                $stmt->bindValue(1, $fullName);
                $stmt->bindValue(2, $username);
                $stmt->bindValue(3, $hashedPassword);
                $stmt->bindValue(4, $email);



                if ($stmt->execute()) {
                    $sql = "INSERT INTO GioHang (MaKhachHang) VALUES (?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(1, $conn->lastInsertId());
                    $stmt->execute();
                    echo "<script>alert('Đăng ký thành công!');</script>";
                    header("Location: ./login.php");
                    exit();
                } else {
                    echo "<script>alert('Đã xảy ra lỗi. Vui lòng thử lại.');</script>";
                }
            }

            $stmt->close();
        }
    }