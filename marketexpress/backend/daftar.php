<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ebiz";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $custName = $_POST['custName'];
    $custEmail = $_POST['custEmail'];
    $custAlamat = $_POST['custAlamat'];
    $custPhone = $_POST['custPhone'];
    $custPass = password_hash($_POST['custPass'], PASSWORD_DEFAULT);

    $checkEmailQuery = "SELECT * FROM customer WHERE custEmail = ?";
    $checkStmt = $conn->prepare($checkEmailQuery);
    $checkStmt->bind_param("s", $custEmail);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location.href = 'daftar.php';</script>";
        exit();
    }

    $sql = "INSERT INTO customer (custName, custEmail, custAlamat, custPhone, custPass) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $custName, $custEmail, $custAlamat, $custPhone, $custPass);

    if ($stmt->execute()) {
        // Get the last inserted ID
        $last_id = $conn->insert_id;

        // Insert into cart table, letting the database generate the cartID
        $cartSql = "INSERT INTO mscart (custID) VALUES (?)";
        $cartStmt = $conn->prepare($cartSql);
        $cartStmt->bind_param("i", $last_id);

        if ($cartStmt->execute()) {
            echo "<script>alert('Berhasil Mendaftar dan CartID berhasil dibuat!'); window.location.href = 'login.php';</script>";
        } else {
            echo "Error: " . $cartSql . "<br>" . $conn->error;
        }

        $cartStmt->close();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $checkStmt->close();
}



$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Market Express - Register</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/x-icon" href="img/logo.png">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <div class="container-fluid fixed-top">
        <div class="container topbar bg-primary d-none d-lg-block">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2">
                    <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">Jl. Sudirman, Jakarta</a></small>
                    <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">Email@Example.com</a></small>
                </div>
                <div class="top-link pe-2">
                    <small class="me-3"><i class="fab fa-instagram me-2 text-secondary"></i> <a href="#" class="text-white">marketexpress</a></small>
                    <small class="me-3"><i class="fab fa-whatsapp me-2 text-secondary"></i><a href="#" class="text-white">0821-xxxx-xxxx</a></small>
                </div>
            </div>
        </div>
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="index.php" class="navbar-brand"><h3 class="text-black display-6"><img src="img/logo.png" alt="" class="m-2" style="width: 70px;">Market Express</h3></a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="index.php" class="nav-item nav-link">Home</a>
                        <a href="login.php" class="nav-item nav-link active">Produk</a>
                        <a href="login.php" class="nav-item nav-link">Galeri</a>
                        <a href="login.php" class="nav-item nav-link">Kontak Kami</a>
                    </div>
                    <div class="d-flex m-3 me-0">
                        <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#cariModal"><i class="fas fa-search text-primary"></i></button>
                        <a href="login.php" class="position-relative me-4 my-auto">
                            <i class="fa fa-shopping-bag fa-2x"></i>
                        </a>
                        <div class="nav-item position-relative me-4 my-auto">
                            <a href="login.php">
                                <i class="fas fa-user fa-2x"></i>
                            </a>
                            <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                <a href="login.php" class="dropdown-item">Profil</a>
                                <a href="login.php" class="dropdown-item">Riwayat</a>
                                <a href="index.php" class="dropdown-item">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Modal Search Start -->
    <div class="modal fade" id="cariModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cari Menggunakan Kata Kunci</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <div class="input-group w-75 mx-auto d-flex">
                        <input type="search" class="form-control p-3" placeholder="Kata kunci" aria-describedby="search-icon-1">
                        <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Search End -->

    <!-- Header Start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Daftar</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active text-white">Daftar</li>
        </ol>
    </div>
    <!-- Header End -->

    <!-- Daftar Start -->
    <div class="container-fluid contact py-5">
        <div class="container py-5">
            <div class="p-5 bg-light rounded">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="text-center mx-auto" style="max-width: 700px;">
                            <h1 class="text-primary">Daftar</h1>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <form action="" method="post">
                            <input type="text" name="custName" class="w-100 form-control border-0 py-3 mb-4" placeholder="Nama" required>
                            <input type="email" name="custEmail" class="w-100 form-control border-0 py-3 mb-4" placeholder="Email" required>
                            <input type="text" name="custAlamat" class="w-100 form-control border-0 py-3 mb-4" placeholder="Alamat" required>
                            <input type="text" name="custPhone" class="w-100 form-control border-0 py-3 mb-4" placeholder="No. HP" required>
                            <input type="password" name="custPass" class="w-100 form-control border-0 py-3 mb-4" placeholder="Password" required>
                            <button class="w-100 btn form-control border-secondary py-3 bg-white text-primary" type="submit">Daftar</button>
                        </form>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex p-4 rounded mb-4 bg-white">
                            <div class="text-center">
                                <h4>Sudah punya akun?</h4>
                                <p class="mb-2">Silakan nikmati berbagai pilihan produk dan layanan menarik yang kami dengan masuk ke akun Anda.</p>
                                <a href="login.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-user me-2 text-primary"></i> Masuk</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Daftar End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
        <div class="container py-5">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                <div class="row g-4">
                    <div class="col-lg-9">
                        <a href="#">
                            <h1 class="text-primary mb-0">Market Express</h1>
                            <p class="text-secondary mb-0">Fresh products</p>
                        </a>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex justify-content-end pt-3">
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-light mb-3">Mengapa Orang Menyukai Kami!</h4>
                        <p class="mb-4">Toko online untuk sayuran segar dan sembako menawarkan kemudahan dan harga terjangkau untuk membeli bahan makanan berkualitas tinggi langsung dari rumah Anda.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md=6">
                    <div class="d-flex flex-column text-start footer-item">
                        <h4 class="text-light mb-3">Info Toko</h4>
                        <a class="btn-link" href="login.php">Galeri</a>
                        <a class="btn-link" href="login.php">Kontak</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex flex-column text-start footer-item">
                        <h4 class="text-light mb-3">Akun</h4>
                        <a class="btn-link" href="login.php">Akun Saya</a>
                        <a class="btn-link" href="login.php">Keranjang Belanja</a>
                        <a class="btn-link" href="login.php">Riwayat Pesanan</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-light mb-3">Kontak</h4>
                        <p>Address: Jl. Sudirman, Jakarta</p>
                        <p>Email: Example@gmail.com</p>
                        <p>Phone: 0821-xxxx-xxxx</p>
                        <p>Payment Accepted</p>
                        <img src="img/payment.png" class="img-fluid" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright bg-dark py-4">
        <div class="container">
            <div class="row">
                <span class="text-light text-center"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Market Express</a>, All right reserved.</span>
            </div>
        </div>
    </div>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
