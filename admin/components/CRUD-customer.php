<?php

include("../config/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    // Kiểm tra giá trị TenDangNhap đã tồn tại hay chưa
                    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM KhachHang WHERE TenDangNhap = ?");
                    $checkStmt->execute([$_POST['username']]);
                    $exists = $checkStmt->fetchColumn();

                    if ($exists) {
                        echo "<script>alert('Tên đăng nhập đã tồn tại! Vui lòng chọn tên đăng nhập khác.');</script>";
                        break;
                    }

                    // Nếu không tồn tại, thực hiện thêm mới
                    $stmt = $conn->prepare("INSERT INTO KhachHang (TenDayDu, TenDangNhap, MatKhau, Email, SoDienThoai, DiaChi, Quyen) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $_POST['full_name'],
                        $_POST['username'],
                        password_hash($_POST['password'], PASSWORD_DEFAULT),
                        $_POST['email'],
                        $_POST['phone'],
                        $_POST['address'],
                        $_POST['quyen']
                    ]);
                    break;


                case 'edit':
                    // Kiểm tra TenDangNhap mới có bị trùng không (trừ bản ghi hiện tại)
                    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM KhachHang WHERE TenDangNhap = ? AND MaKhachHang != ?");
                    $checkStmt->execute([$_POST['username'], $_POST['id']]);
                    $exists = $checkStmt->fetchColumn();

                    if ($exists) {
                        echo "<script>alert('Tên đăng nhập đã tồn tại! Vui lòng chọn tên đăng nhập khác.');</script>";
                        break;
                    }


                    $stmt = $conn->prepare("UPDATE KhachHang SET TenDayDu = ?, TenDangNhap = ?, MatKhau = ?, Email = ?, SoDienThoai = ?, DiaChi = ?, Quyen = ? WHERE MaKhachHang = ?");
                    $stmt->execute([
                        $_POST['full_name'],
                        $_POST['username'],
                        password_hash($_POST['password'], PASSWORD_DEFAULT),
                        $_POST['email'],
                        $_POST['phone'],
                        $_POST['address'],
                        $_POST['quyen'],
                        $_POST['id']
                    ]);
                    break;

                case 'delete':
                    $stmt = $conn->prepare("DELETE FROM KhachHang WHERE MaKhachHang = ?");
                    $stmt->execute([$_POST['id']]);
                    break;
            }
        }
    }

}

$result = $conn->prepare("SELECT Quyen FROM KhachHang WHERE MaKhachHang = ? ");
$result->bindParam(1, $_SESSION["MaKhachHang"]);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$customers = $conn->query("SELECT * FROM KhachHang")->fetchAll(PDO::FETCH_ASSOC);

if (!isset($_SESSION['MaKhachHang']) || $row['Quyen'] != 1) {
    header("Location: /pages/login.php");
    exit();
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $stmt = $conn->prepare("INSERT INTO KhachHang (TenDayDu, TenDangNhap, MatKhau, Email, SoDienThoai, DiaChi, Quyen) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['full_name'],
                    $_POST['username'],
                    password_hash($_POST['password'], PASSWORD_DEFAULT),
                    $_POST['email'],
                    $_POST['phone'],
                    $_POST['address'],
                    $_POST['quyen']
                ]);
                break;
            case 'edit':
                $stmt = $conn->prepare("UPDATE KhachHang SET TenDayDu = ?, TenDangNhap = ?, MatKhau = ?, Email = ?, SoDienThoai = ?, DiaChi = ?, Quyen = ? WHERE MaKhachHang = ?");
                $stmt->execute([
                    $_POST['full_name'],
                    $_POST['username'],
                    password_hash($_POST['password'], PASSWORD_DEFAULT),
                    $_POST['email'],
                    $_POST['phone'],
                    $_POST['address'],
                    $_POST['quyen'],
                    $_POST['id']
                ]);
                break;
            case 'delete':
                $stmt = $conn->prepare("DELETE FROM KhachHang WHERE MaKhachHang = ?");
                $stmt->execute([$_POST['id']]);
                break;
        }
    }
}

// Fetch customers
$customers = $conn->query("SELECT * FROM KhachHang")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Quản Lý Khách Hàng</h1>
        <hr>
        <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModal">Thêm Khách Hàng</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên Đầy Đủ</th>
                    <th>Tên Đăng Nhập</th>
                    <th>Email</th>
                    <th>Số Điện Thoại</th>
                    <th>Địa Chỉ</th>
                    <th>Quyền</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= $customer['MaKhachHang'] ?></td>
                        <td><?= $customer['TenDayDu'] ?></td>
                        <td><?= $customer['TenDangNhap'] ?></td>
                        <td><?= $customer['Email'] ?></td>
                        <td><?= $customer['SoDienThoai'] ?></td>
                        <td><?= $customer['DiaChi'] ?></td>
                        <td><?= $customer['Quyen'] == 1 ? 'Admin' : 'Khách' ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $customer['MaKhachHang'] ?>">Sửa</button>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $customer['MaKhachHang'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $customer['MaKhachHang'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Sửa Khách Hàng</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id" value="<?= $customer['MaKhachHang'] ?>">

                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Tên Đầy Đủ</label>
                                            <input type="text" class="form-control" name="full_name"
                                                value="<?= $customer['TenDayDu'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Tên Đăng Nhập</label>
                                            <input type="text" class="form-control" name="username"
                                                value="<?= $customer['TenDangNhap'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Mật Khẩu</label>
                                            <input type="password" class="form-control" name="password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email"
                                                value="<?= $customer['Email'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Số Điện Thoại</label>
                                            <input type="text" class="form-control" name="phone"
                                                value="<?= $customer['SoDienThoai'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Địa Chỉ</label>
                                            <input type="text" class="form-control" name="address"
                                                value="<?= $customer['DiaChi'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="quyen" class="form-label">Quyền</label>
                                            <select class="form-select" name="quyen" required>
                                                <option value="0" <?= $customer['Quyen'] == 0 ? 'selected' : '' ?>>Khách
                                                </option>
                                                <option value="1" <?= $customer['Quyen'] == 1 ? 'selected' : '' ?>>Admin
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-primary">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Khách Hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Tên Đầy Đủ</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên Đăng Nhập</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật Khẩu</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số Điện Thoại</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa Chỉ</label>
                            <input type="text" class="form-control" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="quyen" class="form-label">Quyền</label>
                            <select class="form-select" name="quyen" required>
                                <option value="0">Khách</option>
                                <option value="1">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>