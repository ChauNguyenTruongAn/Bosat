<section class="container py-5">
    <h2 class="text-center mb-5">Dịch Vụ Cửa Hàng</h2>

    <div id="serviceCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $sql = "
                SELECT TenDichVu, Gia, MoTa, Anh
                FROM DichVu
                WHERE 1
                ORDER BY Gia DESC
                LIMIT 8
            ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $chunks = array_chunk($services, 4);
            $active = 'active';

            foreach ($chunks as $chunk) {
                echo '<div class="carousel-item ' . $active . '">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">';
                foreach ($chunk as $service) {
                    echo '
                    <div class="col">
                        <a href="pages/service-detail.php?name=' . urlencode($service['TenDichVu']) . '" class="service-link">
                            <div class="card service-card h-100">
                                <img src="' . $service['Anh'] . '" class="card-img-top" alt="' . $service['TenDichVu'] . '">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate">' . $service['TenDichVu'] . '</h5>
                                    <p class="card-text flex-grow-1 text-truncate">' . $service['MoTa'] . '</p>
                                    
                                    <p class="card-price text-danger">' . number_format($service['Gia'], 0, ',', '.') . ' VND</p>
                                    <a href="cart.php?add=' . urlencode($service['TenDichVu']) . '" class="btn btn-primary mt-2">Đặt Dịch Vụ</a>
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


        <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<style>
    .service-link {
        text-decoration: none;
    }

    .service-link:hover .card-img-top {
        opacity: 0.8;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: #333;
    }
</style>