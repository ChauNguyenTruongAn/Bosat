<section style="background-color: #eee;">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-body-tertiary rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User Profile</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="<?php echo isset($_SESSION['AnhDaiDien']) ? $_SESSION['AnhDaiDien'] : 'https://cdn.pixabay.com/photo/2021/07/25/08/03/account-6491185_640.png'; ?>"
                            alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <h5 class="my-3">
                            <?php echo isset($_SESSION['TenDayDu']) ? htmlspecialchars($_SESSION['TenDayDu']) : 'Tên người dùng'; ?>
                        </h5>
                        <p class="text-muted mb-1">Khách hàng thân thiết</p>
           
                        
                        <p class="text-muted mb-4">
                            <?php echo isset($_SESSION['DiaChi']) ? htmlspecialchars($_SESSION['NgayTao']) : ''; ?>
                        </p>

                    </div>
                </div>
                <div class="card mb-4 mb-lg-0">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush rounded-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fas fa-globe fa-lg text-warning"></i>
                                <p class="mb-0">
                                    <?php echo isset($_SESSION['Email']) ? htmlspecialchars($_SESSION['Email']) : 'Email'; ?>
                                </p>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-github fa-lg text-body"></i>
                                <p class="mb-0">
                                    <?php echo isset($_SESSION['TenDangNhap']) ? htmlspecialchars($_SESSION['TenDangNhap']) : 'Tên đăng nhập'; ?>
                                </p>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-twitter fa-lg" style="color: #55acee;"></i>
                                <p class="mb-0">
                                    <?php echo isset($_SESSION['SoDienThoai']) ? htmlspecialchars($_SESSION['SoDienThoai']) : 'Số điện thoại'; ?>
                                </p>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-instagram fa-lg" style="color: #ac2bac;"></i>
                                <p class="mb-0">Instagram</p> 
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-facebook-f fa-lg" style="color: #3b5998;"></i>
                                <p class="mb-0">Facebook</p> 
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Họ và tên</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">
                                    <?php echo isset($_SESSION['TenDayDu']) ? htmlspecialchars($_SESSION['TenDayDu']) : 'Tên người dùng'; ?>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">
                                    <?php echo isset($_SESSION['Email']) ? htmlspecialchars($_SESSION['Email']) : 'Email'; ?>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Số điện thoại 1</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">
                                    <?php echo isset($_SESSION['SoDienThoai']) ? htmlspecialchars($_SESSION['SoDienThoai']) : 'Số điện thoại'; ?>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Số điện thoại 2</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">
                                    <?php echo isset($_SESSION['SoDienThoai']) ? htmlspecialchars($_SESSION['SoDienThoai']) : 'Số điện thoại'; ?>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Địa chỉ giao hàng</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">
                                    <?php echo isset($_SESSION['DiaChi']) ? htmlspecialchars($_SESSION['DiaChi']) : 'Địa chỉ'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4 mb-md-0">
                        <div class="card-body">
                            <p class="mb-4"><span class="text-primary font-italic me-1">Hoạt động</span> Gần đây</p>

                            <p class="mb-1" style="font-size: .77rem;">Đơn hàng gần nhất</p>
                            <div class="progress rounded" style="height: 5px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">#123456 - Hoàn thành</div>
                            </div>

                            <p class="mt-4 mb-1" style="font-size: .77rem;">Tổng số đơn hàng</p>
                            <div class="progress rounded" style="height: 5px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">15 đơn hàng</div>
                            </div>

                            <p class="mt-4 mb-1" style="font-size: .77rem;">Điểm tích lũy</p>
                            <div class="progress rounded" style="height: 5px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 75%"
                                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">750/1000 điểm</div>
                            </div>

                            <p class="mt-4 mb-1" style="font-size: .77rem;">Số lần đăng nhập</p>
                            <div class="progress rounded mb-2" style="height: 5px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">50 lần</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>