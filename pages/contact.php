<?php
include("../config/config.php");
include("../components/header.php");
?>

<section class="container py-5">
    <div class="row justify-content-center text-center">
        <div class="col-lg-8">
            <h2 class="display-4 mb-4 text-uppercase text-dark">Liên Hệ Với Chúng Tôi</h2>
            <p class="lead text-dark-50">Chúng tôi luôn sẵn sàng lắng nghe ý kiến và câu hỏi của bạn. Hãy liên hệ với chúng tôi qua thông tin dưới đây hoặc điền vào biểu mẫu để chúng tôi có thể hỗ trợ bạn tốt nhất.</p>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6 mb-4">
            <h3 class="text-dark">Thông Tin Liên Hệ</h3>
            <ul class="list-unstyled">
                <li><i class="fas fa-map-marker-alt"></i> Địa chỉ: 123 Đường ABC, Thành phố XYZ</li>
                <li><i class="fas fa-phone"></i> Điện thoại: (012) 345-6789</li>
                <li><i class="fas fa-envelope"></i> Email: contact@yourdomain.com</li>
                <li><i class="fab fa-facebook-f"></i> Facebook: <a href="https://www.facebook.com/yourpage" target="_blank">Theo dõi chúng tôi</a></li>
            </ul>
        </div>

        <div class="col-md-6 mb-4">
            <h3 class="text-dark">Gửi Tin Nhắn Cho Chúng Tôi</h3>
            <form>
                <div class="form-group">
                    <label for="name">Họ và Tên</label>
                    <input type="text" class="form-control" id="name" placeholder="Nhập họ và tên của bạn" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Nhập email của bạn" required>
                </div>
                <div class="form-group">
                    <label for="message">Tin Nhắn</label>
                    <textarea class="form-control" id="message" rows="4" placeholder="Nhập tin nhắn của bạn" required></textarea>
                </div>
                <button type="submit" class="btn btn-dark">Gửi</button>
            </form>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h3 class="text-dark text-center">Tìm Đường Đến Chúng Tôi</h3>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.954342044642!2d106.67525717564423!3d10.738002459900027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f62a90e5dbd%3A0x674d5126513db295!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBuZ2jhu4cgU8OgaSBHw7Ju!5e0!3m2!1svi!2s!4v1734281319244!5m2!1svi!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

<style>
    body {
        background-color: #f8f9fa;
        color: #212529;
        font-family: 'Arial', sans-serif;
        position: relative;
        overflow-x: hidden;
    }

    .container {
        max-width: 1200px;
        position: relative;
        z-index: 10;
    }

    h2, h3 {
        color: #212529;
    }

    .list-unstyled {
        font-size: 1.1rem;
        color: #6c757d;
    }

    .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
    }

    .btn-dark {
        background-color: #343a40;
        border: none;
        transition: background-color 0.3s ease;
    }

    .btn-dark:hover {
        background-color: #212529;
    }

    #map {
        border: 1px solid #ddd;
        border-radius: 5px;
    }
</style>


<?php
include("../components/footer.php");
?>