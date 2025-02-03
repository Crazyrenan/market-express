<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ebiz";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$custID = $_SESSION['custID'];

function getCartID($conn, $custID) {
    $query = "SELECT cartID FROM msCart WHERE custID = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $custID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $cartID);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $cartID;
    } else {
        die("Query failed: " . mysqli_error($conn));
    }
}

$cartID = getCartID($conn, $custID);

// Retrieve grouped cart items for the current user
$query = "SELECT c.barangID, b.barangName, b.barangPrice, b.weight, SUM(c.quantity) AS total_quantity, SUM(c.quantity * b.barangPrice) AS total_price
          FROM cartdetail c 
          INNER JOIN msbarang b ON c.barangID = b.barangID 
          WHERE c.cartID = ?
          GROUP BY c.barangID";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $cartID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    die("Query failed: " . mysqli_error($conn));
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
                            <a href="produk.php" class="nav-item nav-link active">Produk</a>
                            <a href="kontak.php" class="nav-item nav-link">Kontak Kami</a>
                        </div>
                        <div class="d-flex m-3 me-0">
                            <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#cariModal"><i class="fas fa-search text-primary"></i></button>
                            <a href="keranjang.php" class="position-relative me-4 my-auto">
                                <i class="fa fa-shopping-bag fa-2x"></i>
                            </a>
                            <div class="nav-item position-relative me-4 my-auto">
                                <a href="profil.php">
                                    <i class="fas fa-user fa-2x"></i>
                                </a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                    <a href="profil.php" class="dropdown-item">Profil</a>
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
            <h1 class="text-center text-white display-6">Checkout</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active text-white">Checkout</li>
            </ol>
        </div>
        <!-- Header End -->


        <!-- Halaman checkout Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4">Detail Pembayaran</h1>
                <form action="#">
                    <div class="row g-5">
                        <div class="col-md-12 col-lg-6 col-xl-7">
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-item w-100">
                                        <label class="form-label my-3">Nama Depan<sup>*</sup></label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-item w-100">
                                        <label class="form-label my-3">Nama Belakang<sup>*</sup></label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Alamat <sup>*</sup></label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Kota<sup>*</sup></label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Provinsi<sup>*</sup></label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Kode Pos<sup>*</sup></label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">No. HP<sup>*</sup></label>
                                <input type="tel" class="form-control">
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Email<sup>*</sup></label>
                                <input type="email" class="form-control">
                            </div>
                            <div class="form-item my-3">
                                <label class="form-label my-3">Catatan</label>
                                <textarea name="text" class="form-control" spellcheck="false" cols="30" rows="11"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 col-xl-5">
                            <div class="table-responsive">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-0 me-4">Total</h6>
                                    <div class="">
                                        <p class="mb-0 me-4 totalHarga">Rp. </p>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Retrieve the total from sessionStorage
                                        const total = sessionStorage.getItem('total');

                                        // Display the total in the HTML
                                        document.querySelector('.totalHarga').innerText = 'Rp. ' + total.toLocaleString();
                                    });
                                </script>
                            </div>
                            <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                            <a href="pembayaran.php">
                                <button type="button" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Bayar</button>
                            </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Halaman checkout End -->


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
                            <a class="btn-link" href="kontak.php">Kontak</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">Akun</h4>
                            <a class="btn-link" href="profil.php">Akun Saya</a>
                            <a class="btn-link" href="keranjang.php">Keranjang Belanja</a>
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