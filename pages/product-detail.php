<?php
include('../config/config.php'); 
include("../components/header.php");

$productId = isset($_GET['id']) ? $_GET['id'] : 1;

$stmt = $conn->prepare("SELECT * FROM BoSat WHERE MaBoSat = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT TenDanhMuc FROM DanhMuc JOIN BoSat On DanhMuc.MaDanhMuc = BoSat.MaDanhMuc WHERE MaBoSat = ? ");
$stmt->execute([$productId]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM Anh WHERE MaBoSat = ?");
$stmt->execute([$productId]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT AVG(Sao) AS averageRating FROM DanhGia WHERE MaBoSat = ?");
$stmt->execute([$productId]);
$averageRating = $stmt->fetch(PDO::FETCH_ASSOC)['averageRating'];

if ($averageRating === null) {
    $averageRating = 0;
}

$stmt = $conn->prepare("SELECT dg.Sao, dg.ChiTietDanhGia, kh.TenDayDu, dg.NgayTao 
                        FROM DanhGia dg 
                        JOIN KhachHang kh ON dg.MaKhachHang = kh.MaKhachHang 
                        WHERE dg.MaBoSat = ? 
                        ORDER BY dg.NgayTao DESC");
                        
$stmt->execute([$productId]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $customerId = 1;

    $stmt = $conn->prepare("INSERT INTO DanhGia (MaBoSat, MaKhachHang, Sao, ChiTietDanhGia, NgayTao) 
                            VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$productId, $customerId, $rating, $comment]);

    $stmt = $conn->prepare("SELECT AVG(Sao) AS averageRating FROM DanhGia WHERE MaBoSat = ?");
    $stmt->execute([$productId]);
    $averageRating = $stmt->fetch(PDO::FETCH_ASSOC)['averageRating'];

    if ($averageRating === null) {
        $averageRating = 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0-alpha1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .thumbnail {
            cursor: pointer;
            transition: transform 0.3s;
        }

        .thumbnail:hover {
            transform: scale(1.1);
        }

        .product-description p {
            font-size: 1.1rem;
            color: #555;
        }

        .product-price p {
            font-size: 1.6rem;
            font-weight: bold;
            color: #e63946;
        }

        .star-rating {
            margin-top: 15px;
        }

        .stars {
            font-size: 1.5rem;
            color: #ffcc00;
        }

        .fa-star {
            color: #ddd;
            transition: color 0.3s;
        }

        .fa-star.filled {
            color: #ffcc00;
        }

        .average-rating {
            font-size: 1.1rem;
            color: #333;
            margin-left: 10px;
        }

        .star-rating .fa-star:hover {
            color: #ffd700;
        }

        .modal-body img {
            width: 100%;
            height: auto;
        }

        .review {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .review-author {
            font-weight: bold;
            color: #333;
        }

        .review-date {
            font-size: 0.9rem;
            color: #777;
        }

        .review-content {
            font-size: 1rem;
            margin-top: 10px;
        }

        .form-group {
            margin-top: 20px;
        }

        /* Flex container cho phần bình luận và mục gửi bình luận */
        .reviews-container {
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .reviews-list {
            flex: 0 0 60%;
        }

        .review-form {
            flex: 0 0 35%;
        }

        .review-form h4 {
            margin-bottom: 20px;
        }

        /* Đánh giá sao - không có hover trong phần mô tả sản phẩm */
        .star-rating .fa-star {
            cursor: pointer;
        }

        /* Style cho các ngôi sao */
        .rating-group {
            display: flex;
            justify-content: flex-end;
            flex-direction: row-reverse;
            gap: 10px;
            margin-bottom: 15px;
        }

        .rating-group input {
            display: none;
        }

        .rating-group label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.3s;
        }

        /* Khi sao được chọn, chuyển sang màu vàng */
        .rating-group input:checked~label,
        .rating-group input:checked+label {
            color: #ffcc00;
        }

        /* Style cho phần bình luận */
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: none;
            font-size: 1rem;
        }

        .form-group textarea:focus {
            border-color: #ffcc00;
        }

        /* Button gửi đánh giá */
        .btn-submit {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffcc00;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
        }

        .btn-submit:hover {
            background-color: #e6b800;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/" class="text-muted text-decoration-none">
                        Trang chủ
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="/pages/products.php?category=<?= urlencode($category['TenDanhMuc']) ?>&price_min=0&price_max=1000000&rating=0"
                        class="text-muted text-decoration-none">

                        <?= htmlspecialchars($category['TenDanhMuc'] ?? 'Không có tên danh mục') ?>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= htmlspecialchars($product['TenBoSat']); ?>
                </li>
            </ol>
        </nav>


        <div class="row">
            <div class="col-md-6">
                <div class="border mb-3" style="overflow: hidden;">
                    <img id="main-image" src="<?php echo htmlspecialchars($product['AnhDaiDien']); ?>"
                        alt="Ảnh đại diện" class="img-fluid rounded"
                        style="object-fit: cover; width: 100%; height: 400px;">
                </div>
                <div class="row">
                    <?php $counter = 0;
                    foreach ($images as $image) { ?>
                        <?php if ($counter < 3) { ?>
                            <div class="col-4 mb-2">
                                <img src="<?php echo htmlspecialchars($image['DuongDanAnh']); ?>" alt="Thumbnail"
                                    class="img-thumbnail thumbnail"
                                    onclick="changeImage('<?php echo htmlspecialchars($image['DuongDanAnh']); ?>')">
                            </div>
                        <?php }
                        $counter++;
                    } ?>
                </div>
            </div>


            <div class="col-md-6">
                <div class="product-description mb-4">
                    <strong>Mô tả:</strong>
                    <p><?php echo htmlspecialchars($product['MoTa']); ?></p>
                </div>

                <div class="product-price mb-4">
                    <p class="fs-4 text-danger fw-bold">
                      Giá:   <?php echo number_format($product['Gia'], 0, ',', '.') . ' VND'; ?>
                    </p>
                </div>

                <div class="star-rating">
                    <div class="stars">
                        <?php
                        $averageRating = round($averageRating, 1);
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $averageRating) {
                                echo '<span class="fa fa-star filled"></span>';
                            } else {
                                echo '<span class="fa fa-star"></span>';
                            }
                        }
                        ?>
                  
                        <span class="average-rating">(<?php echo $averageRating; ?> sao)</span>
                    </div>
                </div>
                <hr>
                <form action="/services/add-to-cart.php" method="POST">
                    <input type="hidden" name="MaBoSat" value="<?= $product['MaBoSat'] ?>">
                    <input type="hidden" name="TenBoSat" value="<?= htmlspecialchars($product['TenBoSat']) ?>">
                    <input type="hidden" name="Gia" value="<?= $product['Gia'] ?>">
                    <button type="submit" class="btn btn-outline-success">Thêm vào Giỏ Hàng</button>
                </form>
            </div>

        </div>

        <div class="reviews-container mt-4">
            <div class="reviews-list">
                <h4>Đánh giá và bình luận</h4>
                <?php foreach ($reviews as $review) { ?>
                    <div class="review">
                        <div class="star-rating">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $review['Sao']) {
                                    echo '<span class="fa fa-star"></span>';
                                } else {
                                    echo '<span class="fa fa-star-o"></span>';
                                }
                            }
                            ?>
                        </div>
                 
                        <p class="review-author"><?php echo htmlspecialchars($review['TenDayDu']); ?></p>
                        <p class="review-date"><?php echo date('d-m-Y', strtotime($review['NgayTao'])); ?></p>
                 
                        <p class="review-content"><?php echo nl2br(htmlspecialchars($review['ChiTietDanhGia'])); ?></p>
                    </div>
                <?php } ?>
            </div>


            <div class="review-form">
                <h4>Gửi đánh giá của bạn</h4>
                <form action="../services/send-comment.php" method="POST">
                    <div class="form-group">
                        <label>Đánh giá sao</label>
                        <input type="hidden" name="id" value="<?php echo $product['MaBoSat'] ?>">
                        <div class="rating-group">

                            <input type="radio" name="rating" id="star-5" value="5">
                            <label for="star-5">&#9733;</label>

                            <input type="radio" name="rating" id="star-4" value="4">
                            <label for="star-4">&#9733;</label>

                            <input type="radio" name="rating" id="star-3" value="3">
                            <label for="star-3">&#9733;</label>

                            <input type="radio" name="rating" id="star-2" value="2">
                            <label for="star-2">&#9733;</label>

                            <input type="radio" name="rating" id="star-1" value="1" required>
                            <label for="star-1">&#9733;</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="comment">Bình luận</label>
                        <textarea name="comment" id="comment" rows="4" placeholder="Nhập bình luận của bạn..."
                            required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Gửi Đánh Giá</button>
                </form>


            </div>
        </div>
    </div>

    <script>
        function changeImage(imageUrl) {
            document.getElementById('main-image').src = imageUrl;
        }

        function selectStar(rating) {
            document.getElementById('rating').value = rating;

            // Cập nhật màu sao
            let stars = document.querySelectorAll('.star-rating .fa-star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('filled');
                } else {
                    star.classList.remove('filled');
                }
            });
        }
    </script>

    <?php include("../components/footer.php"); ?>