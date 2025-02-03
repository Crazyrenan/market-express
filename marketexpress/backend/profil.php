<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: indexLoggedIn.php');
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=ebiz", "root", "");

$id = $_SESSION['custID'];
$stmt = $pdo->prepare("SELECT custPhone, custPass FROM customer WHERE custID = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$phone = $row['custPhone'] ?? '';
$password = $row['custPass'];

if (isset($_POST['ubah'])) {
    $name = $_POST['nama'];
    $email = $_POST['email'];
    $address = $_POST['alamat'];
    $phone = $_POST['telepon'];
    $newPassword = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $password;

    $updateQuery = "UPDATE customer SET
                    custName = :name,
                    custEmail = :email,
                    custAlamat = :address,
                    custPhone = :phone,
                    custPass = :password
                    WHERE custID = :id";

    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':address' => $address,
        ':phone' => $phone,
        ':password' => $newPassword,
        ':id' => $id
    ]);

    $_SESSION['custName'] = $name;
    $_SESSION['custEmail'] = $email;
    $_SESSION['custAlamat'] = $address;
    $_SESSION['custPhone'] = $phone;
    $_SESSION['custPass'] = $newPassword;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Market Express</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar start -->
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
                <a href="indexLoggedIn.php" class="navbar-brand"><h3 class="text-black display-6"><img src="img/logo.png" alt="" class="m-2" style="width: 70px;">Market Express</h3></a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="indexLoggedIn.php" class="nav-item nav-link">Home</a>
                        <a href="produk.php" class="nav-item nav-link">Produk</a>
                        <a href="galeri.php" class="nav-item nav-link">Galeri</a>
                        <a href="kontak.php" class="nav-item nav-link">Kontak Kami</a>
                    </div>
                    <div class="d-flex m-3 me-0">
                        <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#cariModal"><i class="fas fa-search text-primary"></i></button>
                        <a href="keranjang.php" class="position-relative me-4 my-auto">
                            <i class="fa fa-shopping-bag fa-2x"></i>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">3</span>
                        </a>
                        <div class="nav-item position-relative me-4 my-auto">
                            <a href="profil.php">
                                <i class="fas fa-user fa-2x"></i>
                            </a>
                            <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                <a href="profil.php" class="dropdown-item">Profil</a>
                                <a href="riwayat.php" class="dropdown-item">Riwayat</a>
                                <a href="indexLoggedIn.php" class="dropdown-item">Logout</a>
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

    <!-- Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Profil</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active text-white">Profil</li>
        </ol>
    </div>
    <!-- Header End -->

    <!-- Cart Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="contact-from-section mt-150 mb-150">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group mb-4">
                                    <label>Nama</label>
                                    <input value="<?php echo $_SESSION['custName']?>" type="text" class="form-control" name="nama">
                                </div>
                                <div class="form-group mb-4">
                                    <label>Email</label>
                                    <input value="<?php echo $_SESSION['custEmail']?>" type="email" class="form-control" name="email">
                                </div>
                                <div class="form-group mb-4">
                                        <label>Telepon</label>
                                        <input value="<?php echo $phone; ?>" type="text" class="form-control" name="telepon">
                                    </div>
                                    <div class="form-group mb-4">
                                        <label>Alamat</label>
                                        <textarea class="form-control" name="alamat" rows="5"><?php echo $_SESSION['custAlamat']?></textarea>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="Leave blank to keep the current password">
                                        <input type="hidden" class="form-control" name="passwordlama" value="<?php echo $password; ?>">
                                        <span class="text-danger">Leave blank if you don't want to change the password</span>
                                    </div>
                                    <button type="submit" class="btn btn-primary float-right pull-right mb-4" name="ubah"><i class="glyphicon glyphicon-saved"></i>Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cart Page End -->


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
                                <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
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
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">Info Toko</h4>
                            <a class="btn-link" href="galeri.php">Galeri</a>
                            <a class="btn-link" href="kontak.php">Kontak</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">Akun</h4>
                            <a class="btn-link" href="profil.php">Akun Saya</a>
                            <a class="btn-link" href="keranjang.php">Keranjang Belanja</a>
                            <a class="btn-link" href="riwayat.php">Riwayat Pesanan</a>
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
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Your Site Name</a>, All right reserved.</span>
                    </div>
                    <div class="col-md-6 my-auto text-center text-md-end text-white">
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
                    </div>
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