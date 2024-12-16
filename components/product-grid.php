<?php
include("../config/config.php");

$category = $_GET['category'] ?? '';
$category_sql = "SELECT MaDanhMuc, TenDanhMuc FROM DanhMuc";
$category_stmt = $conn->prepare($category_sql);
$category_stmt->execute();
$categories = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

// get url
$price_min = $_GET['price_min'] ?? 0;
$price_max = $_GET['price_max'] ?? 1000000;
$rating = $_GET['rating'] ?? 0;
$order_by = $_GET['order_by'] ?? 'Gia ASC';

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max(1, $page);
$limit = 12;
$offset = ($page - 1) * $limit;

$count_sql = "
    SELECT COUNT(*) 
    FROM BoSat
    LEFT JOIN DanhGia ON BoSat.MaBoSat = DanhGia.MaDanhGia
    LEFT JOIN DanhMuc ON BoSat.MaDanhMuc = DanhMuc.MaDanhMuc
    WHERE (DanhMuc.TenDanhMuc LIKE '%$category%' OR '$category' = '')
      AND BoSat.Gia BETWEEN $price_min AND $price_max
    GROUP BY BoSat.MaBoSat
    HAVING AVG(DanhGia.Sao) >= $rating
";

$total_result = $conn->query($count_sql);
$total_rows = $total_result->fetchColumn();
$total_pages = ceil($total_rows / $limit); 
$sql = "
    SELECT 
        BoSat.MaBoSat, BoSat.TenBoSat, BoSat.Gia, BoSat.AnhDaiDien, BoSat.MoTa,
        DanhMuc.TenDanhMuc,
        IFNULL(AVG(DanhGia.Sao), 0) AS TrungBinhSao,
        COUNT(DanhGia.MaDanhGia) AS SoLuongDanhGia
    FROM BoSat
    LEFT JOIN DanhGia ON BoSat.MaBoSat = DanhGia.MaBoSat
    LEFT JOIN DanhMuc ON BoSat.MaDanhMuc = DanhMuc.MaDanhMuc
    WHERE (DanhMuc.TenDanhMuc LIKE '%$category%' OR '$category' = '')
      AND BoSat.Gia BETWEEN $price_min AND $price_max
    GROUP BY BoSat.MaBoSat
    HAVING TrungBinhSao >= $rating
    ORDER BY $order_by
    LIMIT $offset, $limit;
";

$result = $conn->query($sql);
?>

<div class="container mt-4">
    <div class="row">
        <!-- bo loc-->
        <div class="col-lg-3 col-md-4">
            <div class="card p-3 mb-4">
                <h4>Bộ lọc</h4>
                <form method="GET" action="products.php">
                    <div class="mb-3">
                        <label for="category" class="form-label">Danh mục</label>
                        <select name="category" class="form-control" id="category">
                            <option value="">Tất cả danh mục</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['TenDanhMuc'] ?>" <?= ($category == $cat['TenDanhMuc']) ? 'selected' : '' ?>><?= $cat['TenDanhMuc'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="price_min" class="form-label">Giá (Từ)</label>
                        <input type="number" name="price_min" class="form-control" value="<?= $price_min ?>"
                            id="price_min">
                    </div>
                    <div class="mb-3">
                        <label for="price_max" class="form-label">Giá (Đến)</label>
                        <input type="number" name="price_max" class="form-control" value="<?= $price_max ?>"
                            id="price_max">
                    </div>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Đánh giá (Từ)</label>
                        <input type="number" name="rating" class="form-control" value="<?= $rating ?>" id="rating"
                            max="5" min="0">
                    </div>
                    <button type="submit" class="btn btn-primary">Áp dụng bộ lọc</button>
                </form>
            </div>
        </div>

        <!-- grid -->
        <div class="col-lg-9 col-md-8">
            <div class="row g-4">
                <?php if ($result->rowCount() > 0): ?>
                    <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                        <?php include("../components/product-card.php"); ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">Không có sản phẩm nào phù hợp với bộ lọc của bạn.</p>
                <?php endif; ?>
            </div>

            <!-- trang -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link"
                                href="?page=1&category=<?= htmlspecialchars($category) ?>&price_min=<?= $price_min ?>&price_max=<?= $price_max ?>&rating=<?= $rating ?>&order_by=<?= $order_by ?>">First</a>
                        </li>
                        <li class="page-item"><a class="page-link"
                                href="?page=<?= $page - 1 ?>&category=<?= htmlspecialchars($category) ?>&price_min=<?= $price_min ?>&price_max=<?= $price_max ?>&rating=<?= $rating ?>&order_by=<?= $order_by ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <li class="page-item disabled"><span class="page-link"><?= $page ?> of <?= $total_pages ?></span>
                    </li>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link"
                                href="?page=<?= $page + 1 ?>&category=<?= htmlspecialchars($category) ?>&price_min=<?= $price_min ?>&price_max=<?= $price_max ?>&rating=<?= $rating ?>&order_by=<?= $order_by ?>">Next</a>
                        </li>
                        <li class="page-item"><a class="page-link"
                                href="?page=<?= $total_pages ?>&category=<?= htmlspecialchars($category) ?>&price_min=<?= $price_min ?>&price_max=<?= $price_max ?>&rating=<?= $rating ?>&order_by=<?= $order_by ?>">Last</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>