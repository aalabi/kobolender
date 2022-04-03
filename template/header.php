  <!-- ======= Top Bar ======= -->
  <section id="topbar" class="d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
          <div class="contact-info d-flex align-items-center">
              <i class="fa fa-envelope" style="color:#fff;"></i><a href="mailto:info@kobolender.com" style="text-decoration: none;">info@kobolender.com</a>
              <i class="fa fa-phone phone-icon" style="color:#fff;"></i> +(234) 9079060202
          </div>
          <div class="social-links d-none d-md-block">
              <a href="https://twitter.com" class="twitter"><i class="fa fa-twitter"></i></a>
              <a href="https://facebook.com" class="facebook"><i class="fa fa-facebook"></i></a>
              <a href="https://linkedin.com" class="linkedin"><i class="fa fa-linkedin"></i></i></a>
              <a href="https://www.youtube.com" class="linkedin"><i class="fa fa-youtube"></i></i></a>
          </div>
      </div>
  </section>



  <!---Navbar section-->
  <nav class="navbar  navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
          <a class="navbar-brand" href="https://kobolender.com/"><img src="./img/kobonewlogo.png" alt="" class="img-fluid my-image" id="my-image"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                      <a class="nav-link <?= isset($pageName) && $pageName == 'Home' ? 'active' : '' ?>" aria-current="page" href="index.php">Home</a>
                  </li>
                  <li class="nav-item">

                      <a class="nav-link <?= isset($pageName) && $pageName == 'About Us' ? 'active' : '' ?>" href="about.php">About Us</a>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Our Products & Services
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <li><a class="dropdown-item" href="products.php">Our Target Markets</a></li>
                          <li><a class="dropdown-item" href="services.php">Our Services</a></li>
                      </ul>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link <?= isset($pageName) && $pageName == 'Frequently Asked Questions' ? 'active' : '' ?>" href="faq.php">FAQs</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link <?= isset($pageName) && $pageName == 'Blog' ? 'active' : '' ?>" href="blog.php">Blog</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link <?= isset($pageName) && $pageName == 'Contact Us' ? 'active' : '' ?>" href="contact.php">Contact Us</a>
                  </li>
                  <li class="nav-item" data-aos="fade-right" data-aos-duration="2000">
                      <a class="nav-link" href="apply-loan.php"><button class="btn btn-success appbtn">Apply For Loan</button></a>
                  </li>
              </ul>
          </div>
      </div>
  </nav>
  <!--End of Navbar-->