<?php
include("../config/config.php");
session_start();

if (!isset($_SESSION["MaKhachHang"])) {
    header("Location: /login.php");
    exit();
}

$result = $conn->prepare("SELECT Quyen FROM KhachHang WHERE MaKhachHang = ? ");
$result->bindParam(1, $_SESSION["MaKhachHang"]);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);

if ($row && $row["Quyen"] == 0) {
    header("Location: /");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 15px;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }

        .sidebar h4 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="#" class="text-danger">Quản Lý Khách Hàng</a>
        <a href="./reptile-manager.php">Quản Lý Bò Sát</a>
        <a href="#">Quản Lý Đơn Hàng</a>
        <a href="../services/logout.php">Đăng Xuất</a>
    </div>

    <div class="content">
        <?php
        include("./components/CRUD-customer.php");
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>