<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if(empty($_SESSION["adm_id"]))
{
    header('location:index.php');
}
else
{
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Customer Reviews || Taste Transport</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="main-wrapper">
        <?php include('include/header.php'); ?>
        <?php include('include/sidebar.php'); ?>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Customer Reviews</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Review ID</th>
                                                <th>User</th>
                                                <th>Order</th>
                                                <th>Rating</th>
                                                <th>Comment</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $sql = "SELECT r.*, u.username, uo.title as order_title 
                                                    FROM reviews r 
                                                    JOIN users u ON r.user_id = u.u_id 
                                                    JOIN users_orders uo ON r.order_id = uo.o_id 
                                                    ORDER BY r.date DESC";
                                            $result = mysqli_query($db, $sql);
                                            while($row = mysqli_fetch_assoc($result)) { 
                                            ?>
                                                <tr>
                                                    <td><?php echo $row['review_id']; ?></td>
                                                    <td><?php echo $row['username']; ?></td>
                                                    <td><?php echo $row['order_title']; ?></td>
                                                    <td>
                                                        <?php 
                                                        for($i = 1; $i <= 5; $i++) {
                                                            if($i <= $row['rating']) {
                                                                echo '<i class="fa fa-star text-warning"></i>';
                                                            } else {
                                                                echo '<i class="fa fa-star-o text-warning"></i>';
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['comment']; ?></td>
                                                    <td><?php echo date('Y-m-d H:i:s', strtotime($row['date'])); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>
<?php
}
?> 