<?php
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $idBoSat = $_POST['id'];
    session_start();
    if (isset($_SESSION['MaKhachHang'])) {
        $maKhachHang = $_SESSION['MaKhachHang'];
    } else {
        header('Location: login.php');
        exit;
    }

    $ngayTao = date('Y-m-d H:i:s');

    $query = "INSERT INTO DanhGia (Sao, MaKhachHang, MaBoSat, NgayTao, ChiTietDanhGia) 
              VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bindValue(1, $rating);
        $stmt->bindValue(2, $maKhachHang);
        $stmt->bindValue(3, $idBoSat);
        $stmt->bindValue(4, $ngayTao);
        $stmt->bindValue(5, $comment);

        if ($stmt->execute()) {
            header('Location: /pages/product-detail.php?id=' . $idBoSat);
            exit;
        } else {
            echo "Có lỗi xảy ra khi lưu đánh giá!";
        }
        $stmt->close();
    } else {
        echo "Không thể chuẩn bị câu lệnh SQL!";
    }
    $conn->close();
}
?>