<section class="container py-5">
    <h2 class="text-center mb-5">Đánh Giá Của Khách Hàng</h2>
    <div class="row">
        <?php
        // Truy vấn lấy các đánh giá từ cơ sở dữ liệu
        $sql = "
            SELECT dg.ChiTietDanhGia, dg.Sao, dg.NgayTao, kh.TenDayDu 
            FROM DanhGia dg
            JOIN KhachHang kh ON dg.MaKhachHang = kh.MaKhachHang
            ORDER BY dg.NgayTao DESC
            LIMIT 5
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Hiển thị các đánh giá
        foreach ($reviews as $review) {
            echo '
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($review['TenDayDu']) . '</h5>
                        <p class="card-text">' . htmlspecialchars($review['ChiTietDanhGia']) . '</p>
                        <div class="stars mb-2">';

            // Hiển thị sao đánh giá
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $review['Sao']) {
                    echo '<span class="star filled">&#9733;</span>';
                } else {
                    echo '<span class="star">&#9733;</span>';
                }
            }

            echo '
                        </div>
                        <p class="text-muted">' . date("d-m-Y H:i", strtotime($review['NgayTao'])) . '</p>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</section>

<style>
    .stars {
        color: #f39c12;
        /* Màu vàng cho sao */
    }

    .star {
        font-size: 1.5rem;
        margin-right: 3px;
    }

    .star.filled {
        color: #f39c12;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
    }

    .card-text {
        font-size: 1rem;
        color: #555;
    }
</style>