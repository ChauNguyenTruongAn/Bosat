<div class="col-lg-4 col-md-6 mb-4">
    <a href="product-detail.php?id=<?= $row['MaBoSat'] ?>">
        <div class="card border-0 shadow-sm">
            <div class="bg-image">
                <img src="<?= htmlspecialchars($row['AnhDaiDien']) ?>" class="w-100" alt="Product Image" />
            </div>
            <div class="card-body text-center">
                <a href="product-detail.php?id=<?= $row['MaBoSat'] ?>" class="text-reset">
                    <h5 class="card-title mb-2"><?= htmlspecialchars($row['TenBoSat']) ?></h5>
                </a>
                <a href="/pages/products.php?category=<?= urlencode($row['TenDanhMuc']) ?>&price_min=0&price_max=1000000&rating=0"
                    class="text-muted text-decoration-none">
                    <p class="mb-3"><?= htmlspecialchars($row['TenDanhMuc']) ?></p>
                </a>
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <div class="text-warning">
                        <?php
                        $ratingStars = floor($row['TrungBinhSao']);
                        for ($n = 0; $n < $ratingStars; $n++) {
                            echo '<i class="bi bi-star-fill"></i>'; 
                        }
                        $emptyStars = 5 - $ratingStars;
                        for ($n = 0; $n < $emptyStars; $n++) {
                            echo '<i class="bi bi-star"></i>';
                        }
                        ?>
                    </div>
                    <span class="ms-2 text-muted">(<?= $row['SoLuongDanhGia'] ?> đánh giá)</span>
                </div>
                <h6 class="mb-3">
                    <s class="text-muted"><?= number_format($row['Gia']) ?> </s>
                    <strong class="ms-2 text-danger"><?= number_format($row['Gia'] * 0.9) ?></strong>
                </h6>

                <form action="/services/add-to-cart.php" method="POST">
                    <input type="hidden" name="MaBoSat" value="<?= $row['MaBoSat'] ?>">
                    <input type="hidden" name="TenBoSat" value="<?= htmlspecialchars($row['TenBoSat']) ?>">
                    <input type="hidden" name="Gia" value="<?= $row['Gia'] ?>">
                    <button type="submit" class="btn btn-outline-success">Thêm vào Giỏ Hàng</button>
                </form>
            </div>
        </div>
    </a>
</div>