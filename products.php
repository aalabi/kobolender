<?php
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





<section class="products">
    <div class="container">
    <div class="row" >
        <div class="about-management-text text-center pt-5">
            <h1>
              Our Target Markets
            </h1>
            <div class="small-text">            
            </div>
        </div>
    </div>
    </div>
</section>
    <!---End of About us section-->



<!--Services section-->
<section class="servicestwo" id="services">
    <div class="container services-container">
        <div class="row mb-4 ">
            <div class="col-md-12" data-aos="fade-up">
                <h4 class="text-center mt-4">Our Target Markets</h4>
                <div class="divider"></div>
            </div>
        </div>
        <div class="row service-row pb-4">
            <div class="col-md-4">
                <a href="individual_form.php?productId=<?php echo $firstProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3 " data-aos="zoom-in-down" >
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
                    <div class="card text-center my-3" data-aos="zoom-in-down" >
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
                    <div class="card text-center my-3" data-aos="zoom-in-down">
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
                    <div class="card text-center my-3" data-aos="zoom-in-down" >
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
                    <div class="card text-center my-3" data-aos="zoom-in-down" >
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
                    <div class="card text-center my-3" data-aos="zoom-in-down">
                        <div class="card-body" style="line-height: 70px; padding-bottom: 20px;">
                            <i class="fa fa-user"></i>
                            <h6 style="font-size: 18px; padding-bottom: 5px;">Self –Employed –Professionals</h6>
                            <h6>INDIVIDUAL</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="services-btn pt-5">
            <a href="individual.php"><button class="btn serbtn">Learn more</button></a>
             </div>
        </div>
        <!--MSMEs-->

        <div class="row service-row">
            <div class="col-md-3">
                <a href="msme_form.php?productId=<?php echo $seventhProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3 " data-aos="zoom-in-down" >
                        <div class="card-body" style="line-height: 70px;">
                            <i class="fa fa-users"></i>
                            <h6>Micro Borrower - Petty Traders</h6>

                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="msme_form.php?productId=<?php echo $eighthProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3 " data-aos="zoom-in-down">
                        <div class="card-body" style="line-height: 70px;">
                            <i class="fa fa-users"></i>
                            <h6>Small Scale Businesses</h6>

                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="msme_form.php?productId=<?php echo $ninthProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3 " data-aos="zoom-in-down" >
                        <div class="card-body" style="line-height: 70px;">
                            <i class="fa fa-users"></i>
                            <h6>Medium Scale Business</h6>

                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="msme_form.php?productId=<?php echo $tenthProduct; ?>" target="_blank" class="card-link">
                    <div class="card text-center my-3 " data-aos="zoom-in-down" >
                        <div class="card-body" style="line-height: 70px;">
                            <i class="fa fa-users"></i>
                            <h6>On-Lending (Coorporative Societies)</h6>

                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="services-btn pt-5">
            <a href="msmes.php"><button class="btn serbtn">Learn more</button></a>
        </div>
    </div>
</section>
<!--End of Services section-->



<!---Contact us Google map section
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.404868491439!2d3.3524142148225127!3d6.5964995241403805!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b923a18e7e5a1%3A0x42a5229388e06f3f!2s5%20Allen%20Ave%2C%20Allen%20101233%2C%20Ikeja!5e0!3m2!1sen!2sng!4v1642328228875!5m2!1sen!2sng" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>
    <!--End of Contact us Google map section-->



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
<script src="js/jquery.counterup.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="js/main.js"></script>

</body>

</html>