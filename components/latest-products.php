<section class="container py-5">
    <h2 class="text-center mb-5">Sản Phẩm Mới Nhất</h2>

    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $sql = "
                SELECT bs.MaBoSat, bs.TenBoSat, bs.Gia, bs.MoTa, AnhDaiDien AS DuongDanAnh,
                       (SELECT AVG(Sao) FROM DanhGia WHERE MaBoSat = bs.MaBoSat) AS DiemDanhGia
                FROM BoSat bs
                ORDER BY bs.NgayTao DESC
                LIMIT 8
            ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $chunks = array_chunk($products, 4);
            $active = 'active';

            foreach ($chunks as $chunk) {
                echo '<div class="carousel-item ' . $active . '">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">';
                foreach ($chunk as $product) {
                  
                    $rating = $product['DiemDanhGia'] !== null ? round($product['DiemDanhGia']) : 0;

                    echo '
                    <div class="col">
                        <a href="pages/product-detail.php?id=' . $product['MaBoSat'] . '" class="product-link">
                            <div class="card product-card h-100">
                                <img src="' . $product['DuongDanAnh'] . '" class="card-img-top" alt="' . $product['TenBoSat'] . '">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate">' . $product['TenBoSat'] . '</h5>
                                    <p class="card-text flex-grow-1 text-truncate">' . $product['MoTa'] . '</p>
                                    
                                    <!-- Hiển thị sao đánh giá -->
                                    <div class="stars mb-2">';
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            echo '<span class="star filled">&#9733;</span>';
                        } else {
                            echo '<span class="star">&#9733;</span>';
                        }
                    }
                    echo '</div>
                                    
                                    <p class="card-price text-danger">' . number_format($product['Gia'], 0, ',', '.') . ' VND</p>
                                    <a href="cart.php?add=' . $product['MaBoSat'] . '" class="btn btn-success mt-2">Thêm vào Giỏ hàng</a>
                                </div>
                            </div>
                        </a>
                    </div>';

                }
                echo '</div></div>';
                $active = '';
            }
            ?>
        </div>

        <!-- Điều khiển chuyển slide -->
        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>


<style>
    .product-link {
        text-decoration: none;
    }

    /* Giảm opacity khi hover vào ảnh sản phẩm */
    .product-link:hover .card-img-top {
        opacity: 0.8;
    }

    .stars {
        color: #f39c12;
        /* Màu vàng cho sao */
    }

    .star {
        font-size: 1.2rem;
        margin-right: 2px;
    }

    .star.filled {
        color: #f39c12;
        /* Màu vàng cho sao đầy */
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: #333;
    }
</style>