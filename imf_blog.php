<?php
$pageName = "IMF READY TO STABILIZE ITS LOAN ASSET TO NIGERIA ";
require_once('template/head.php');
require_once('template/header.php');
require_once('template/mast-head.php');
?>






<!--Ownership and Management section--->
<section class="mt-5">
    <div class="container">
        <div class="row">
            <div class="about-sec">
<!--FAQs section-->
<section class="mt-5 mb-5">
    <div class="container">
        <div class="about-sec">
            <h3 class="text-center">Blog</h3>
            <div class="divider"></div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="card img-fluid mb-5">
                    
                    <img src="img/fin3.jpg" class="card-img-top" alt="...">
                    <div class="card-title"><h3>IMF READY TO STABILIZE ITS LOAN ASSET TO NIGERIA</h3></div>
                    <div class="card-body" style="text-align: justify;">
                    <div class="conman">
                        <ul class='post-meta' style="display: flex; flex-direction:row; padding:0; list-style-type: none;">
                            <!--<li><i class='fa fa-user'></i>posted by <a href='#'> admin agba </a> </li>-->
                            <!-- <li><i class='fa fa-comments'></i><a href='#'> 0 comments</a> </li>
                            <li style="padding-left: 15px;"><i class='fa fa-clock-o'></i><span class='day'> Jan 26th, 2022 </span></li> -->
                            <li><small class="text-muted"><i class="fa fa-comments"></i> <a href="#">0 comment</small></a></li>
                            <li style="padding-left: 20px;"><small class="text-muted"><i class='fa fa-clock-o'></i> Feb 19th, 2022</small></li>
                        </ul>
                    </div>
                        <p class="card-text">
                            Angela holds a masters and bachelors degree in Mass communication from the University of Lagos and Abia State University, respectively . With over 25 years working experience ,She Kicked off her career journey as a personal Secretary /Executive Assistance at Chartered Loss(Insurance) Adjusters and later spent several years working as a Marketing Executive at Something New Nigeria Limited before setting out to establish her private business . Angela is a consummate Marketers and astute entrepreneur . She is a co-founder and chief Operating Officer of VEMA Group ,a family company with spread of activities that cover retails Marketing, Confectionaries and agricultural businesses.
                        </p>
                        <p class="card-text">
                            Hector is a financial & management expert of over 20 years in the financial sector covering Oerations,Financial Control , Internal Control, Risk Management, Project Management, Business Development ,Retail Banking ,Investment Banking and Consultancy He is a fellow of the Institute of Chartered Accountant of Nigeria, an alumnus of the Lagos Business School, and a graduate of Accounting from the Obafemi Awolowo. Hector is is also an Associate Member, Chartered Institute of Taxation of Nigeria , member ,Association of International Product Marketing and management and a senior Associate ,Risk Management Association of Nigeria. He was the Group Chief Risk Officer at Greenwich Trust Limited Group where he implemented & strengthened the Enterprise Risk Management framework across the group . He was also with Diamond Bank (now Access Bank) where he spent over 15 years supporting the bank’s strategic goals in a leadership role in risk management and control ,Business Development, Project Management, Performance management and Retail Banking.
                        </p>
                    </div>
                </div>

                <div class="col-md-12 shadow rounded p-5">
                <?php
                    if(isset($_POST['submit'])){
                        $firstname = $_POST['first_name'];
                        $lastname = $_POST['last_name'];
                        $email = $_POST['email'];
                        $subject = $_POST['subject'];
                        $message = $_POST['message'];

                        if(empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($subject) || empty($message)){
                            echo '<div class="alert alert-danger" role="alert">
                                    <h5>Fields cannot be empty!</h5>
                                </div>';
                        }
                    }
                ?>
                    <div class="mb-4">
                        <h3>Leave a Reply</h3>
                        <small class="text-muted">Your email address will not be published. Required fields are marked *</small>
                    </div>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col mb-3">
                                <input type="text" name="first_name" id="" class="form-control" placeholder="First Name*">
                            </div>
                            <div class="col mb-3">
                                <input type="text" name="last_name" id="" class="form-control" placeholder="Last Name*">
                            </div>
                            <div class=" mb-3">
                                <input type="email" name="email" id="" class="form-control" placeholder="Email*">
                            </div>
                            <div class=" mb-3">
                                <input type="text" name="subject" id="" class="form-control" placeholder="Subject*">
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" name="message" placeholder="Message*" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" type="submit" name="submit">Post Comment</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="col-md-3">
                <form class="d-flex mb-4">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <h3 class="mb-4">Categories</h3>
                <ul>
                    <li><a href="">General</a></li>
                    <li><a href="">Lifestyle</a></li>
                    <li><a href="">Travel</a></li>
                    <li><a href="">Design</a></li>
                    <li><a href="">Creative</a></li>
                    <li><a href="">Education</a></li>
                </ul>
                <h3 class="mb-4">Recent Posts</h3>
                <div class="card mb-4" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                        <img src="img/africa-america.jpg" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                        <div class="card-body">
                            <h6 class="card-title">KoboLender  is the best</h6>
                            <p class="card-text">We are the best in the lending sector.</p>
                            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                        <img src="img/fin1.jpg" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                        <div class="card-body">
                            <h6 class="card-title">IMF introduce loan promo</h6>
                            <p class="card-text">IMF have introduced loan promo.</p>
                            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                        <img src="img/business-woman-2756210_640.jpg" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                        <div class="card-body">
                            <h6 class="card-title">Big boom for Nollywood industry</h6>
                            <p class="card-text">Entertainment industry booms with financial endorsement.</p>
                            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                        <img src="img/ororo.jpg" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                        <div class="card-body">
                            <h6 class="card-title">Manchester United's season almost over</h>
                            <p class="card-text">United were shambolic and disunited against the Saints.</p>
                            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>

            </div>
        </div>
    </div>
</section>
<!---End of FAQs section-->



            </div>
        </div>
</section>
<!--End of Ownership and Management section--->

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