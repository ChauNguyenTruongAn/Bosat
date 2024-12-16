<?php
include("../config/config.php");
include("../components/header.php");
?>

<section class="container py-5">
    <div class="row justify-content-center text-center">
        <div class="col-lg-8">
            <h2 class="display-4 mb-4 text-uppercase text-dark">Giới Thiệu Về Chúng Tôi</h2>
            <p class="lead text-light">Chúng tôi, An và Vương, là những người đam mê bò sát và đã quyết định mở cửa
                hàng chuyên cung cấp các loài bò sát tuyệt vời cùng các sản phẩm phụ kiện cho những ai yêu thích loài
                vật này.</p>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6 mb-4">
            <div class="card shadow-lg border-0 rounded hover-zoom">
                <img src="https://via.placeholder.com/600x400?text=An" class="card-img-top" alt="An">
                <div class="card-body">
                    <h5 class="card-title text-dark">An</h5>
                    <p class="card-text text-muted">Với đam mê nghiên cứu và chăm sóc bò sát từ khi còn nhỏ, An mang đến
                        cho cửa hàng một kiến thức vững vàng về các loài bò sát và cách chăm sóc chúng. An cũng chính là
                        người đầu tiên khám phá ra nhiều giống loài bò sát hiếm gặp tại Việt Nam, từ đó đưa chúng về cửa
                        hàng để cung cấp cho những người yêu thích.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/vuong3503" class="btn btn-outline-dark" target="_blank">
                            <i class="fab fa-facebook-f"></i> Theo dõi An
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-lg border-0 rounded hover-zoom">
                <img src="https://via.placeholder.com/600x400?text=V%C6%B0%C6%A1ng" class="card-img-top" alt="Vương">
                <div class="card-body">
                    <h5 class="card-title text-dark">Vương</h5>
                    <p class="card-text text-muted">Vương là một người luôn chú trọng đến chất lượng dịch vụ và sự hài
                        lòng của khách hàng. Với sự am hiểu về các giống bò sát từ khắp nơi trên thế giới, Vương cam kết
                        mang lại những sản phẩm tốt nhất, phục vụ nhu cầu đa dạng của khách hàng.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/chaunguyentruongan/" class="btn btn-outline-dark"
                            target="_blank">
                            <i class="fab fa-facebook-f"></i> Theo dõi Vương
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center text-center mt-5">
        <div class="col-lg-8">
            <h3 class="mb-4 text-light">Chúng Tôi Đặt Uy Tín Lên Hàng Đầu</h3>
            <p class="lead text-light">Cửa hàng của chúng tôi cam kết cung cấp những loài bò sát khỏe mạnh, những sản
                phẩm phụ kiện chất lượng, và dịch vụ tư vấn chăm sóc tận tình. Với mục tiêu mang lại sự hài lòng tối đa
                cho khách hàng, chúng tôi luôn không ngừng nghiên cứu và cải tiến để mang đến những gì tốt nhất.</p>
        </div>
    </div>
</section>

<!-- Video background -->
<video autoplay muted loop id="bg-video">
    <source src="https://videos.pexels.com/video-files/29415894/12666761_640_360_60fps.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

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
        z -index: 10;
    }

    .card {
        background-color: #ffffff;
        border: 1px solid #ddd;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        font-weight: bold;
        font-size: 1.6rem;
        color: #212529;
    }

    .card-text {
        font-size: 1.1rem;
        color: #6c757d;
    }

    .lead {
        font-size: 1.3rem;
        color: #212529;
    }

    h2,
    h3 {
        color: #212529;
    }

    .card-img-top {
        max-height: 400px;
        object-fit: cover;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .shadow-lg {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .rounded {
        border-radius: 15px;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    .text-center {
        text-align: center;
    }

    .social-links a {
        margin-top: 10px;
        font-size: 1rem;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
    }

    .fab {
        margin-right: 8px;
    }

    .social-links a:hover {
        background-color: #212529;
        color: white;
    }

    .btn-outline-dark {
        color: #212529;
        border: 2px solid #212529;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-outline-dark:hover {
        background-color: #212529;
        color: white;
    }

    /* Hover zoom effect */
    .hover-zoom:hover .card-img-top {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }

    /* Video background */
    #bg-video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
        filter: brightness(0.5);
    }
</style>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<?php
include("../components/footer.php");
?>