<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta content="" name="description">
        <meta content="" name="keywords">

        <title>Laravel</title>

        <!-- Favicons -->
        <link href="assets/img/favicon.png" rel="icon">
        <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
        <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
        <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Template Main CSS File -->
        <link href="assets/css/style.css" rel="stylesheet">
        <style>
            #start_event i{
                color: #4154f1;
                font-size: 2em;
                margin-bottom: 15px;
            }
        </style>

        @stack('head')
    </head>
    <body>

        <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top">
          <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      
            <a href="index.html" class="logo d-flex align-items-center">
              <img src="assets/img/logo.png" alt="">
              <span>FlexStart</span>
            </a>
      
            <nav id="navbar" class="navbar">
              <ul>
                  <li class="dropdown"><a href="#"><span>Serviços</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                      <li><a href="#">Drop Down 1</a></li>
                      <li><a href="#">Drop Down 2</a></li>
                      <li><a href="#">Drop Down 3</a></li>
                      <li><a href="#">Drop Down 4</a></li>
                    </ul>
                  </li>
                <li><a class="nav-link scrollto active" href="#planos">Planos e Preços</a></li>
                <li><a class="nav-link" href="/eventos">Eventos</a></li>
                <li><a class="nav-link" href="/contato">Contato</a></li>
                {{-- <li><a href="blog.html">Blog</a></li> --}}
                {{-- <li><a class="nav-link" href="/cadastro"></a></li> --}}
                <li><a class="getstarted" href="/login">Criar evento</a></li>
              </ul>
              <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->
      
          </div>
        </header><!-- End Header -->
      
        <!-- ======= Hero Section ======= -->
        {{$slot}}
      
        <!-- ======= Footer ======= -->
        <footer id="footer" class="footer">
      
          {{-- <div class="footer-newsletter">
            <div class="container">
              <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                  <h4>Our Newsletter</h4>
                  <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
                </div>
                <div class="col-lg-6">
                  <form action="" method="post">
                    <input type="email" name="email"><input type="submit" value="Subscribe">
                  </form>
                </div>
              </div>
            </div>
          </div> --}}
      
          <div class="footer-top">
            <div class="container">
              <div class="row gy-4">
                <div class="col-lg-5 col-md-12 footer-info">
                  <a href="index.html" class="logo d-flex align-items-center">
                    <img src="assets/img/logo.png" alt="">
                    <span>FlexStart</span>
                  </a>
                  <p>Cras fermentum odio eu feugiat lide par naso tierra. Justo eget nada terra videa magna derita valies darta donna mare fermentum iaculis eu non diam phasellus.</p>
                  <div class="social-links mt-3">
                    <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                  </div>
                </div>
      
                <div class="col-lg-2 col-6 footer-links">
                  <h4>Useful Links</h4>
                  <ul>
                    <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#">Privacy policy</a></li>
                  </ul>
                </div>
      
                <div class="col-lg-2 col-6 footer-links">
                  <h4>Our Services</h4>
                  <ul>
                    <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#">Graphic Design</a></li>
                  </ul>
                </div>
      
                <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                  <h4>Contact Us</h4>
                  <p>
                    A108 Adam Street <br>
                    New York, NY 535022<br>
                    United States <br><br>
                    <strong>Phone:</strong> +1 5589 55488 55<br>
                    <strong>Email:</strong> info@example.com<br>
                  </p>
      
                </div>
      
              </div>
            </div>
          </div>
      
          <div class="container">
            <div class="copyright">
              &copy; Copyright <strong><span>FlexStart</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
              <!-- All the links in the footer should remain intact. -->
              <!-- You can delete the links only if you purchased the pro version. -->
              <!-- Licensing information: https://bootstrapmade.com/license/ -->
              <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/flexstart-bootstrap-startup-template/ -->
              Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
          </div>
        </footer><!-- End Footer -->
      
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    
        
    </body>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
  
    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
      
</html>