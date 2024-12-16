<?php
session_start();
include('../config/config.php');

if (isset($_POST['MaBoSat']) && isset($_POST['Gia'])) {

    $maBoSat = $_POST['MaBoSat'];
    $gia = $_POST['Gia'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $stmt = $conn->prepare("SELECT MaBoSat, TenBoSat, MaDanhMuc, Gia, GiaVon, MoTa, AnhDaiDien, NgayTao FROM BoSat WHERE MaBoSat = ?");
    $stmt->execute([$maBoSat]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $found = false;
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['MaBoSat'] == $maBoSat) {
                $_SESSION['cart'][$index]['SoLuong'] += 1;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'MaBoSat' => $maBoSat,
                'TenBoSat' => $product['TenBoSat'],
                'MaDanhMuc' => $product['MaDanhMuc'],
                'Gia' => $product['Gia'],
                'GiaVon' => $product['GiaVon'],
                'MoTa' => $product['MoTa'],
                'AnhDaiDien' => $product['AnhDaiDien'],
                'NgayTao' => $product['NgayTao'],
                'SoLuong' => 1
            ];
        }


        $maGiohang = $_SESSION['MaGioHang'];



        $stmt = $conn->prepare("SELECT SoLuong FROM ChiTietGioHangSanPham WHERE MaBoSat = ? AND MaGioHang = ?");
        $stmt->execute([$maBoSat, $maGiohang]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT SoLuong FROM BoSat WHERE BoSat.MaBoSat = ?");
        $stmt->execute([$maBoSat]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);

        $inventory = $count['SoLuong'];

        if ($result) {
            if ($result['SoLuong'] >= $inventory) {
                echo "<script>alert('Số lượng vượt quá trong kho');</script>";
                $newResult = $conn->prepare("UPDATE ChiTietGioHangSanPham SET SoLuong = ? WHERE MaBoSat = ? AND MaGioHang = ?");
                $newResult->execute([$inventory, $maBoSat, $maGiohang]);
                $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
                //header('Location: ' . $referer);
                die();
            }
            $newResult = $conn->prepare("UPDATE ChiTietGioHangSanPham SET SoLuong = ? WHERE MaBoSat = ? AND MaGioHang = ?");
            $newResult->execute([$result['SoLuong'] + 1, $maBoSat, $maGiohang]);
            $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
            header('Location: ' . $referer);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO ChiTietGioHangSanPham (MaBoSat, SoLuong, MaGioHang) VALUES (?, ?, ?)");
        $stmt->execute([$maBoSat, 1, $maGiohang]);

        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header('Location: ' . $referer);
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>