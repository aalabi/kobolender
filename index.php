<?php
//require_once('config.php');
require_once('template/head.php');
require_once('template/header.php');
$products = Functions::getLoanProducts();
foreach ($products as $aProductId => $aProductInfo) {
    $productId = $aProductInfo['no'];
    if($productId == '1'){
        $firstProduct = $productId;
    }
    if($productId == '2'){
        $secondProduct = $productId;
    }
    if($productId == '3'){
        $thirdProduct = $productId;
    }
    if($productId == '4'){
        $fourthProduct = $productId;
    }
    if($productId == '5'){
        $fifthProduct = $productId;
    }
    if($productId == '6'){
        $sixthProduct = $productId;
    }
    if($productId == '7'){
        $seventhProduct = $productId;
    }
    if($productId == '8'){
        $eighthProduct = $productId;
    }
    if($productId == '9'){
        $ninthProduct = $productId;
    }
    if($productId == '10'){
        $tenthProduct = $productId;
    }
   // var_dump($love);
}
?>



<!--Carousel-->
<div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="10000">
            <img src="img/image-2.jpg" class="d-block w-100 " alt="...">
            <div class="carousel-caption  d-md-block p-4" data-aos="fade-right" data-aos-duration="2000">
                <h5>
                    We offer consumer and micro loans to individuals and medium/small scale
                    commercial enterprises primarily
                </h5>
                <div class="services-btn carobtn">
                    <a href="#services"><button class="btn serbtn caro-btn">Our Services</button></a>
                </div>
            </div>
        </div>
        <div class="carousel-item" data-bs-interval="2000">
            <img src="img/image-3.jpg" class="d-block w-100" alt="...">
            <div class="carousel-caption  d-md-block" data-aos="fade-right" data-aos-duration="2000">
                <h5 style="line-height: 45px;">
                    It is ok to need
                    urgent cash, get a quick loan from  <!-- MC & C micro support limited --> KoboLender. No
                    stories, no delay, no hidden charges!
                </h5>
                <div class="services-btn carobtn">
                    <a href="#services"><button class="btn serbtn caro-btn">Apply For A Loan</button></a>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <img src="img/moneycoins.jpg" class="d-block w-100" alt="...">
            <div class="carousel-caption  d-md-block" data-aos="fade-right" data-aos-duration="2000">
                <h5>We deliver credit products to our customers through digital channels such as mobile apps , the web and other non-digital channels . </h5>
                <div class="services-btn carobtn">
                    <a href="#services"><button class="btn serbtn caro-btn">Our Services</button></a>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!--End of Carousel-->


<!---About Us section-->
<section  class="aboutsectionone">
    <div class="container">
        <div class="row about">
            <div class="about-sec">
                <small style="font-size: 1.3rem;">About us</small>
                <h3 class="mcmicro" style="font-size: 2.5rem; text-align:center">Welcome to KoboLender</h3>
                <div class="divider"></div>
                <p style="font-size: 1.2rem;">KoboLender is a product of MC & C MICRO SUPPORT LIMITED. It is licensed by the Lagos State Government (Ministry of Home Affairs) as a money lender to individuals and MSME businesses. We deliver credit products to our customers through digital channels such as mobile apps , the web and other non-digital channels . We also combine the traditional and the non-traditional digital media to meet our customers’ expectation.
                </p>
            </div>
            <div class="col-md-12 good">
                <div class="col-md-4 feature" data-aos="zoom-in-down">
                    <div class="card text-center my-4 mx-4 about-card" data-aos="flip-right" data-aos-duration="2000">
                        <div class="card-body p-4">
                            <div class="icon pb-2">
                                <i class="fa fa-binoculars"></i>
                            </div>
                            <h3>Vision</h3>
                            <p style="font-size: 1.1rem;">To be a world-class brand in Microlending and financial services. </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 feature ">
                    <div class="card text-center my-3 my-4 mx-4 about-card" data-aos="flip-right" data-aos-duration="2000">
                        <div class="card-body pt-2">
                            <div class="icon pb-2">
                                <i class="fa fa-anchor"></i>
                            </div>
                            <h3>Mission</h3>
                            <p style="font-size: 1.1rem;">
                                To build a strong lending institution through innovative and efficient
                                service delivery that meets our customers financial needs
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 feature ">
                    <div class="card text-center my-3 my-4 mx-4 about-card" data-aos="flip-right" data-aos-duration="2000">
                        <div class="card-body" style="padding-bottom: 29px;">
                            <div class="icon pb-2">
                                <i class="fa fa-user"></i>
                            </div>
                            <h3 style="padding-bottom: 10px;">Core Values </h3>
                            <p style="padding-bottom: 19px; font-size:1.1rem">
                                Integrity ,
                                Innovation ,
                                Relationship,
                                Teamwork.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="about-btn pt-5">
            <a href="about.php"><button class="btn mybtn">About us</button></a>
        </div>
    </div>
</section>
<!--End of About Us section-->


<!--Services section-->
<section class="services" id="services">
    <div class="container services-container">
        <div class="row mb-4 ">
            <div class="col-md-12" data-aos="fade-up">
                <h4 class="text-center text-white">Our Target Markets</h4>
                <div class="divider"></div>
            </div>
        </div>
        <div class="row service-row pb-4">
            <div class="col-md-4">
                <a href="individual_form.php?productId=<?php echo $firstProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3 " data-aos="zoom-in-down" data-aos-duration="2000">
                        <div class="card-body" style="line-height: 70px; padding-bottom: 20px;">
                            <i class="fa fa-user"></i>
                            <h6 style="font-size: 18px; padding-bottom: 5px;">Salary Earners –Private</h6>
                            <h6>INDIVIDUAL</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="individual_form.php?productId=<?php echo $secondProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3" data-aos="zoom-in-down" data-aos-duration="2000">
                        <div class="card-body" style="line-height: 70px; padding-bottom: 20px;">
                            <i class="fa fa-user"></i>
                            <h6 style="font-size: 18px; padding-bottom: 5px;">Salary Earners –Public(Civil Servants)</h6>
                            <h6>INDIVIDUAL</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="individual_form.php?productId=<?php echo $thirdProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3" data-aos="zoom-in-down" data-aos-duration="2000">
                        <div class="card-body" style="line-height: 70px; padding-bottom: 20px;">
                            <i class="fa fa-user"></i>
                            <h6 style="font-size: 18px; padding-bottom: 5px;">Skilled Labour</h6>
                            <h6>INDIVIDUAL</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="individual_form.php?productId=<?php echo $fourthProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3" data-aos="zoom-in-down" data-aos-duration="2000">
                        <div class="card-body" style="line-height: 70px; padding-bottom: 20px;">
                            <i class="fa fa-user"></i>
                            <h6 style="font-size: 18px; padding-bottom: 5px;">Unskilled Labour</h6>
                            <h6>INDIVIDUAL</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="individual_form.php?productId=<?php echo $fifthProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3" data-aos="zoom-in-down" data-aos-duration="2000">
                        <div class="card-body" style="line-height: 70px; padding-bottom: 20px;">
                            <i class="fa fa-user"></i>
                            <h6 style="font-size: 18px; padding-bottom: 5px;">Self Employed- Artisan</h6>
                            <h6>INDIVIDUAL</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="individual_form.php?productId=<?php echo $sixthProduct; ?>"  target=" _blank" class="card-link">
                    <div class="card text-center my-3" data-aos="zoom-in-down" data-aos-duration="2000">
                        <div class="card-body" style="line-height: 70px; padding-bottom: 20px;">
                            <i class="fa fa-user"></i>
                            <h6 style="font-size: 18px; padding-bottom: 5px;">Self –Employed –Professionals</h6>
                            <h6>INDIVIDUAL</h6>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!--MSMEs-->

        <div class="row service-row">
            <div class="col-md-3">
                <a href="msme_form.php?productId=<?php echo $seventhProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3 " data-aos="zoom-in-down" data-aos-duration="2000">
                        <div class="card-body" style="line-height: 70px;">
                            <i class="fa fa-users"></i>
                            <h6>Micro Borrower - Petty Traders</h6>

                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="msme_form.php?productId=<?php echo $eighthProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3 " data-aos="zoom-in-down" data-aos-duration="2000">
                        <div class="card-body" style="line-height: 70px;">
                            <i class="fa fa-users"></i>
                            <h6>Small Scale Businesses</h6>

                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="msme_form.php?productId=<?php echo $ninthProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3 " data-aos="zoom-in-down" data-aos-duration="2000">
                        <div class="card-body" style="line-height: 70px;">
                            <i class="fa fa-users"></i>
                            <h6>Medium Scale Business</h6>

                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="msme_form.php?productId=<?php echo $tenthProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3 " data-aos="zoom-in-down" data-aos-duration="2000">
                        <div class="card-body" style="line-height: 70px;">
                            <i class="fa fa-users"></i>
                            <h6>On-Lending (Coorporative Societies)</h6>

                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="services-btn pt-5">
            <a href="apply-loan.php"><button class="btn serbtn">Learn More</button></a>
        </div>
    </div>
</section>
<!--End of Services section-->



<!---Apply For Loan section-->
<section class="apply-loan">
    <div class="container">
        <div class="row" style="padding-top: 60px;">
            <div class="col-md-7" data-aos="fade-right" data-aos-duration="2000">
                <h3>We are Kobo Lenders, your preferred Partner in Financial solutions</h3>
            </div>
            <div class="col-md-5">
                <div class="services-btn">
                    <a href="apply-loan.php"><button class="btn serbtn">Apply for Loan</button></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!---End of Apply For Loan section-->



<!--Consultation section-->
<!----
    <section class="consultaion">
      <div class="container">
        <div class="row">
          <div class="col-md-8 consult-text"  data-aos="fade-right" data-aos-duration="2000">
            <div class="mail-icon">
              <i class="fa fa-envelope"></i>
            </div>
            <h3>Have any Question related to Financial Consultation?</h3>
          </div>
          <div class="col-md-4">
            <div class="consultation-btn">
              <a href="contact.html"><button class="btn consultbtn">CONTACT US</button></a>
            </div>
          </div>
        </div>
      </div>
    </section>
       -->
<!---End of Consultation section-->

<!---Footer section-->
<?php
require_once('template/footer.php');
?>

<!---End of Footer-->

<!-- Option 1: Bootstrap Bundle with Popper -->

<script src="js/counter.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="js/main.js"></script>

</body>

</html>