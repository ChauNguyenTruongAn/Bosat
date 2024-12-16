<?php
include("../config/config.php");
include("../components/header.php");

$MaKhachHang = $_SESSION['MaKhachHang'] ?? null;
if (!$MaKhachHang) {
    echo "<p class='text-center'>Bạn cần đăng nhập để sử dụng giỏ hàng.</p>";
    include("../components/footer.php");
    exit();
}

if (!isset($_SESSION['MaGioHang'])) {
    $stmt = $conn->prepare("SELECT MaGioHang FROM GioHang WHERE MaKhachHang = ?");
    $stmt->execute([$MaKhachHang]);
    $gioHang = $stmt->fetch();

    if (!$gioHang) {
        $stmt = $conn->prepare("INSERT INTO GioHang (MaKhachHang) VALUES (?)");
        $stmt->execute([$MaKhachHang]);
        $_SESSION['MaGioHang'] = $conn->lastInsertId();
    } else {
        $_SESSION['MaGioHang'] = $gioHang['MaGioHang'];
    }
}

function addToCart($MaKhachHang, $MaBoSat, $SoLuong, $conn)
{
    $stmt = $conn->prepare("SELECT MaGioHang FROM GioHang WHERE MaKhachHang = ?");
    $stmt->execute([$MaKhachHang]);
    $gioHang = $stmt->fetch();

    if (!$gioHang) {
        $stmt = $conn->prepare("INSERT INTO GioHang (MaKhachHang) VALUES (?)");
        $stmt->execute([$MaKhachHang]);
        $MaGioHang = $conn->lastInsertId();
    } else {
        $MaGioHang = $gioHang['MaGioHang'];
    }

    $stmt = $conn->prepare(
        "INSERT INTO ChiTietGioHangSanPham (MaGioHang, MaBoSat, SoLuong) 
        VALUES (?, ?, ?) 
        ON DUPLICATE KEY UPDATE SoLuong = SoLuong + ?"
    );
    $stmt->execute([$MaGioHang, $MaBoSat, $SoLuong, $SoLuong]);
}

function getCart($MaKhachHang, $conn)
{
    $stmt = $conn->prepare(
        "SELECT b.TenBoSat, b.Gia, c.SoLuong, b.AnhDaiDien, c.MaBoSat
        FROM GioHang g
        JOIN ChiTietGioHangSanPham c ON g.MaGioHang = c.MaGioHang
        JOIN BoSat b ON c.MaBoSat = b.MaBoSat
        WHERE g.MaKhachHang = ?"
    );
    $stmt->execute([$MaKhachHang]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function updateCart($MaGioHang, $MaBoSat, $SoLuong, $conn)
{
    if ($SoLuong > 0) {
        $stmt = $conn->prepare("UPDATE ChiTietGioHangSanPham SET SoLuong = ? WHERE MaGioHang = ? AND MaBoSat = ?");
        $stmt->execute([$SoLuong, $MaGioHang, $MaBoSat]);
    } else {
        $stmt = $conn->prepare("DELETE FROM ChiTietGioHangSanPham WHERE MaGioHang = ? AND MaBoSat = ?");
        $stmt->execute([$MaGioHang, $MaBoSat]);
    }
}

function checkout($MaKhachHang, $conn)
{
    $conn->beginTransaction();
    try {
        $stmt = $conn->prepare("INSERT INTO DonHang (MaKhachHang) VALUES (?)");
        $stmt->execute([$MaKhachHang]);
        $MaDonHang = $conn->lastInsertId();

        $stmt = $conn->prepare(
            "INSERT INTO ChiTietDonHangSanPham (MaDonHang, MaBoSat, SoLuong, Gia)
            SELECT ?, c.MaBoSat, c.SoLuong, b.Gia
            FROM ChiTietGioHangSanPham c
            JOIN BoSat b ON c.MaBoSat = b.MaBoSat
            WHERE c.MaGioHang = (
                SELECT MaGioHang FROM GioHang WHERE MaKhachHang = ?
            )"
        );
        $stmt->execute([$MaDonHang, $MaKhachHang]);

        $stmt = $conn->prepare("DELETE FROM ChiTietGioHangSanPham WHERE MaGioHang = (
            SELECT MaGioHang FROM GioHang WHERE MaKhachHang = ?");
        $stmt->execute([$MaKhachHang]);

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $MaBoSat = $_POST['MaBoSat'];
        $SoLuong = $_POST['SoLuong'];
        addToCart($MaKhachHang, $MaBoSat, $SoLuong, $conn);
    } elseif (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $MaBoSat => $SoLuong) {
            updateCart($_SESSION['MaGioHang'], $MaBoSat, $SoLuong, $conn);
        }
    } elseif (isset($_POST['checkout'])) {
        checkout($MaKhachHang, $conn);
        echo "<p class='text-center'>Thanh toán thành công!</p>";
    }
}

$products = getCart($MaKhachHang, $conn);
$total = 0;
foreach ($products as $product) {
    $total += $product['Gia'] * $product['SoLuong'];
}

$shipping_fee = 20000;
$total_with_shipping = $total + $shipping_fee;

?>

<style>
    @media (min-width: 1025px) {
        .h-custom {
            height: 100vh !important;
        }
    }

    .card-registration .select-input.form-control[readonly]:not([disabled]) {
        font-size: 1rem;
        line-height: 2.15;
        padding-left: .75em;
        padding-right: .75em;
    }

    .card-registration .select-arrow {
        top: 13px;
    }
</style>

<section class="h-100 h-custom" style="background-color: #fffff;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12">
                <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                    <div class="card-body p-0">
                        <div class="row g-0">
         
                            <div class="col-lg-8">
                                <div class="p-5">
                                    <div class="d-flex justify-content-between align-items-center mb-5">
                                        <h1 class="fw-bold mb-0">Giỏ hàng</h1>
                                        <h6 class="mb-0 text-muted"><?php echo count($products); ?> sản phẩm</h6>
                                    </div>
                                    <hr class="my-4">

                                    <?php foreach ($products as $product): ?>
                                        <div class="row mb-4 d-flex justify-content-between align-items-center">
                                            <div class="col-md-2 col-lg-2 col-xl-2">
                                                <img src="<?php echo htmlspecialchars($product['AnhDaiDien']); ?>"
                                                    class="img-fluid rounded-3" alt="Product Image">
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-xl-3">
                                                <h6 class="text-muted">Shirt</h6>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($product['TenBoSat']); ?></h6>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                                    onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                    <i class="fas fa-minus"></i>
                                                </button>

                                                <input type="number" name="quantity[<?php echo $product['MaBoSat']; ?>]"
                                                    value="<?php echo $product['SoLuong']; ?>" min="1"
                                                    class="form-control form-control-sm" />

                                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                                    onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                <h6 class="mb-0">
                                                    <?php echo number_format($product['Gia'] * $product['SoLuong'], 0, ',', '.'); ?>
                                                    VND
                                                </h6>
                                            </div>
                                            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                <a href="#!" class="text-muted"><i class="fas fa-times"></i></a>
                                            </div>
                                        </div>
                                        <hr class="my-4">
                                    <?php endforeach; ?>

                                    <div class="pt-5">
                                        <h6 class="mb-0"><a href="/" class="text-body"><i
                                                    class="fas fa-long-arrow-alt-left me-2"></i>Quay về trang chủ</a>
                                        </h6>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-4 bg-body-tertiary">
                                <div class="p-5">
                                    <h3 class="fw-bold mb-5 mt-2 pt-1">Hóa đơn</h3>
                                    <hr class="my-4">

                                    <div class="d-flex justify-content-between mb-4">
                                        <h5 class="text-uppercase">Sản phẩm <?php echo count($products); ?></h5>
                                        <h5><?php echo number_format($total, 0, ',', '.'); ?> VND</h5>
                                    </div>

                                    <h5 class="text-uppercase mb-3">Vận chuyển</h5>
                                    <div class="mb-4 pb-2">
                                        <select class="form-select form-select-lg mb-3"
                                            aria-label="Large select example">
                                            <option selected>Hình thức vận chuyển</option>
                                            <option value="1">Giao hàng tiêu chuẩn 20.000đ</option>
                                        </select>
                                    </div>

                                    <h5 class="text-uppercase mb-3">Mã giảm giá</h5>
                                    <div class="mb-5">
                                        <div data-mdb-input-init class="form-outline">
                                            <input type="text" id="promo_code" class="form-control form-control-lg" />
                                            <label class="form-label" for="promo_code">Nhập mã giảm giá</label>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-between mb-5">
                                        <h5 class="text-uppercase">Tổng tiền</h5>
                                        <h5><?php echo number_format($total_with_shipping, 0, ',', '.'); ?> VND</h5>
                                    </div>

                                    <button type="submit" name="checkout" class="btn btn-dark btn-block btn-lg">Thanh
                                        toán</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
echo "<br><br><br><br><br>";
include("../components/outstanding-products.php");
include("../components/services.php");
include("../components/footer.php");
 ?>