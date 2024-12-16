<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $stmt = $conn->prepare("INSERT INTO BoSat (TenBoSat, MaDanhMuc, Gia, GiaVon, SoLuong, MoTa, AnhDaiDien, NgayTao) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([
                    $_POST['name'],
                    $_POST['category_id'],
                    $_POST['price'],
                    $_POST['cost_price'],
                    $_POST['quantity'],
                    $_POST['description'],
                    $_POST['image']
                ]);
                break;
            case 'edit':
                $stmt = $conn->prepare("UPDATE BoSat SET TenBoSat = ?, MaDanhMuc = ?, Gia = ?, GiaVon = ?, SoLuong = ?, MoTa = ?, AnhDaiDien = ? WHERE MaBoSat = ?");
                $stmt->execute([
                    $_POST['name'],
                    $_POST['category_id'],
                    ($_POST['price'] < 0 ? $_POST['price'] * -1 : $_POST['price']),
                    $_POST['cost_price'],
                    $_POST['quantity'],
                    $_POST['description'],
                    $_POST['image'],
                    $_POST['id']
                ]);
                break;
            case 'delete':
                $stmt = $conn->prepare("DELETE FROM BoSat WHERE MaBoSat = ?");
                $stmt->execute([$_POST['id']]);
                break;
        }
    }
}

// Fetch reptiles with category details
$query = "SELECT BoSat.MaBoSat, BoSat.TenBoSat, DanhMuc.MaDanhMuc, DanhMuc.TenDanhMuc, DanhMuc.MoTa AS DanhMucMoTa, BoSat.Gia, BoSat.GiaVon, BoSat.SoLuong, BoSat.MoTa, BoSat.AnhDaiDien, BoSat.NgayTao
          FROM BoSat
          JOIN DanhMuc ON BoSat.MaDanhMuc = DanhMuc.MaDanhMuc";
$reptiles = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT MaDanhMuc, TenDanhMuc FROM DanhMuc";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Bò Sát</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Quản Lý Bò Sát</h1>
        <hr>
        <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModal">Thêm Bò Sát</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên Bò Sát</th>
                    <th>Danh Mục</th>
                    <th>Giá</th>
                    <th>Giá Vốn</th>
                    <th>Số Lượng</th>
                    <th>Mô Tả</th>
                    <th>Ảnh Đại Diện</th>
                    <th>Ngày Tạo</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reptiles as $reptile): ?>
                    <tr>
                        <td><?= $reptile['MaBoSat'] ?></td>
                        <td><?= $reptile['TenBoSat'] ?></td>
                        <td><?= $reptile['TenDanhMuc'] ?></td>
                        <td><?= $reptile['Gia'] ?></td>
                        <td><?= $reptile['GiaVon'] ?></td>
                        <td><?= $reptile['SoLuong'] ?></td>
                        <td><?= $reptile['MoTa'] ?></td>
                        <td><img src="<?= $reptile['AnhDaiDien'] ?>" alt="Ảnh Đại Diện" style="width: 100px;"></td>
                        <td><?= $reptile['NgayTao'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $reptile['MaBoSat'] ?>">Sửa</button>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $reptile['MaBoSat'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $reptile['MaBoSat'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Sửa Bò Sát</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id" value="<?= $reptile['MaBoSat'] ?>">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Tên Bò Sát</label>
                                            <input type="text" class="form-control" name="name"
                                                value="<?= $reptile['TenBoSat'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Danh Mục</label>
                                            <select class="form-control" name="category_id" required>
                                                <?php

                                                foreach ($categories as $category) {
                                                    echo '<option value="' . htmlspecialchars($category['MaDanhMuc']) . '">'
                                                        . htmlspecialchars($category['TenDanhMuc']) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Giá</label>
                                            <input type="text" class="form-control" name="price"
                                                value="<?= $reptile['Gia'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cost_price" class="form-label">Giá Vốn</label>
                                            <input type="text" class="form-control" name="cost_price"
                                                value="<?= $reptile['GiaVon'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Số Lượng</label>
                                            <input type="number" class="form-control" name="quantity"
                                                value="<?= $reptile['SoLuong'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Mô Tả</label>
                                            <textarea class="form-control" name="description" rows="3"
                                                required><?= $reptile['MoTa'] ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Ảnh Đại Diện</label>
                                            <input type="text" class="form-control" name="image"
                                                value="<?= $reptile['AnhDaiDien'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-primary">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Bò Sát</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên Bò Sát</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Danh Mục</label>
                            <select class="form-control" name="category_id" required>
                                <?php
                                foreach ($categories as $category) {
                                    echo '<option value="' . htmlspecialchars($category['MaDanhMuc']) . '">'
                                        . htmlspecialchars($category['TenDanhMuc']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Giá</label>
                            <input type="text" class="form-control" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="cost_price" class="form-label">Giá Vốn</label>
                            <input type="text" class="form-control" name="cost_price" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Số Lượng</label>
                            <input type="number" class="form-control" name="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô Tả</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh Đại Diện</label>
                            <input type="text" class="form-control" name="image" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>