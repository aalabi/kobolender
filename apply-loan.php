<?php
  $pageName = "Apply For Loan";
  require_once('template/head.php');
  require_once('template/header.php');
  require_once('template/mast-head.php');
?>





    <!---Apply Loan Category--->
      <section>
          <div class="container" style="margin-top: 60px;">
            <div class="about-sec">
                <h3 class=" text-center">Loan Application</h3>
                <div class="divider"></div>
            </div>
          </div>
      </section>
    <!---End of Apply Loan Category--->


    <!--MSME section-->
    <section class="services" id="services">
      <div class="container services-container">
        <div class="row">
            <div class="col-sm-6">
              <div class="card" style=" font-family: 'Lato', sans-serif;">
                <div class="card-body">
                  <h5 class="card-title">Individuals Loan Product</h5>
                  <p class="card-text">Click the link below to apply for Individual Loan market.</p>
                  <a href="individual_form.php" class="btn btn-success">Apply</a>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="card" style=" font-family: 'Lato', sans-serif;">
                <div class="card-body">
                  <h5 class="card-title">MSMEs Loan Product</h5>
                  <p class="card-text">Click the link below to apply for Micro Borrower and Small Scale Businesses.</p>
                  <a href="msme_form.php" class="btn btn-success">Apply</a>
                </div>
              </div>
            </div>
          </div>
      </div>
    </section>
    <!--End of MSME section-->

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