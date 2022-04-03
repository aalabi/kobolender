<?php
$pageName = "Contact Us";
require_once('template/head.php');
require_once('template/header.php');
require_once('template/mast-head.php');


if(isset($_POST['submit'])){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    if(empty($fullname) || empty($email) || empty($phone) || empty($subject) || empty($message)){
        echo '<div class="alert alert-danger" role="alert">
                <h5>Fields cannot be empty!</h5>
              </div>';
    }
    
    else if(!preg_match('/^[0-9]*$/', $phone)){
        echo '<div class="alert alert-danger" role="alert">
                <h5>Invalid Phone number!</h5>
              </div>';
    }
    else if(strlen($phone) > 11 || strlen($phone) < 11 ){
        echo '<div class="alert alert-danger" role="alert">
                <h5>Incomplete Phone number!</h5>
              </div>';
    }


}







?>

<!--Form section--->
<section class="mt-5 mb-5 form-section">
    <div class="container">
        <div class="row about pb-4" data-aos="zoom-in-down">
            <div class="about-sec text-center">
                <h3 style="font-family:  'Source Sans Pro', sans-serif;" class="display-6">Keep in Touch with us</h3>
                <div class="divider"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <form action="" method="post" class="row g-4">
                    <div class="form-group col-md-6">
                        <input type="text" name="fullname" id="" class="form-control" placeholder="FullName" require>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="email" name="email" id="" class="form-control" placeholder="Email" require>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="text" name="phone" id="" class="form-control" placeholder="Enter the phone number in this format 09079060202" require>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="text" name="subject" id="" class="form-control" placeholder="Subject" require>
                    </div>
                    <div class="form-group col-md-12">
                        <textarea name="message" id="message" cols="30" rows="8" class="form-control" placeholder="Message" require></textarea>
                    </div>
                    <div class="d-grid gap-2 " style="height:45px">
                        <button class="btn btn-success" type="submit" name="submit">SEND MESSAGE</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</section>
<!--End of Form section--->
<!---Contact Address, phone, email --->
<section style="  font-family: 'Lato', sans-serif;">
    <div class="container">
        <div class="row about">
            <div class="about-sec">
                <h3 class="mcmicro" style="font-size: 2.5rem; text-align:center;">Contact Us</h3>
                <div class="divider"></div>
            </div>
            <div class="col-md-12 good">
                <div class="col-md-6 feature ">
                    <div class="card text-center my-3 my-4 mx-4 " data-aos="flip-right" data-aos-duration="2000">
                        <div class="card-body">
                            <div class="icon pb-2">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <h3>Email Us</h3>
                            <p style="font-size: 1.1rem;">
                                 info@kobolender.com
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 feature ">
                <div class="card text-center my-3 my-4 mx-4 " data-aos="flip-right" data-aos-duration="2000">
                        <div class="card-body" style="font-style:  'Lato', sans-serif;">
                            <div class="icon pb-2">
                                <i class="fa fa-phone"></i>
                            </div>
                            <h3>Call Us</h3>
                            <p style="font-size: 1.1rem;">
                                09079060202
          
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!---End of contact phone, addr-->


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