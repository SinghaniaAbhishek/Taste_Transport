<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();
include("include/header.php");
?>

<body>
    <div class="page-wrapper">
        <div class="top-links">
            <div class="container">
                <ul class="row links">
                    <li class="col-xs-12 col-sm-4 link-item active"><span>1</span><a href="#">Choose Restaurant</a></li>
                    <li class="col-xs-12 col-sm-4 link-item"><span>2</span><a href="#">Pick Your favorite food</a></li>
                    <li class="col-xs-12 col-sm-4 link-item"><span>3</span><a href="#">Order and Pay</a></li>
                </ul>
            </div>
        </div>
        <div class="inner-page-hero bg-image" data-image-src="images/img/pimg.jpg">
            <div class="container"> </div>
        </div>
        <div class="result-show">
            <div class="container">
                <div class="row">
                </div>
            </div>
        </div>
        
        <section class="restaurants-page">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-3">
                    </div>
                    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-9">
                        <div class="bg-gray restaurant-entry">
                            <div class="row">
                                <?php 
                                // First get restaurants with active discounts
                                $discounted_res = mysqli_query($db,
                                    "SELECT * FROM restaurant 
                                     WHERE discount > 0 
                                     AND discount_valid_until >= CURDATE()
                                     ORDER BY discount DESC");
                                
                                // Then get restaurants without discounts
                                $regular_res = mysqli_query($db,
                                    "SELECT * FROM restaurant 
                                     WHERE discount = 0 
                                     OR discount_valid_until < CURDATE()
                                     ORDER BY title");

                                // Function to display restaurant card
                                function display_restaurant($row, $has_discount = false) {
                                    echo '<div class="col-sm-12 col-md-12 col-lg-8 text-xs-center text-sm-left">
                                            <div class="entry-logo">
                                                <a class="img-fluid" href="dishes.php?res_id='.$row['rs_id'].'" > 
                                                    <img src="admin/Res_img/'.$row['image'].'" alt="Food logo">
                                                </a>
                                            </div>
                                            <div class="entry-dscr">
                                                <h5>
                                                    <a href="dishes.php?res_id='.$row['rs_id'].'" >'.$row['title'].'</a>';
                                                    if($has_discount) {
                                                        echo ' <span class="badge badge-danger" style="font-size: 14px;">-'.$row['discount'].'% OFF</span>';
                                                    }
                                    echo        '</h5>
                                                <span>'.$row['address'].'</span>';
                                                if($has_discount) {
                                                    echo '<p class="text-danger">Discount valid until: '.date('M d, Y', strtotime($row['discount_valid_until'])).'</p>';
                                                }
                                    echo    '</div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-4 text-xs-center">
                                            <div class="right-content bg-white">
                                                <div class="right-review">
                                                    <a href="dishes.php?res_id='.$row['rs_id'].'" class="btn btn-purple">View Menu</a>
                                                </div>
                                            </div>
                                        </div>';
                                }

                                // Display discounted restaurants first
                                while($row = mysqli_fetch_array($discounted_res)) {
                                    echo '<div class="col-xs-12" style="margin-bottom: 20px; padding: 15px; background-color: #fff3f3; border-radius: 5px;">';
                                    display_restaurant($row, true);
                                    echo '</div>';
                                }

                                // Display regular restaurants
                                while($row = mysqli_fetch_array($regular_res)) {
                                    echo '<div class="col-xs-12" style="margin-bottom: 20px; padding: 15px;">';
                                    display_restaurant($row, false);
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include "include/footer.php" ?>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>
</html>