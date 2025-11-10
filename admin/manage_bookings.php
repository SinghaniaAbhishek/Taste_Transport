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
    // Handle booking status updates
    if(isset($_POST['update_status'])) {
        $booking_id = mysqli_real_escape_string($db, $_POST['booking_id']);
        $status = mysqli_real_escape_string($db, $_POST['status']);
        
        $update_query = "UPDATE bookings SET status = '$status' WHERE booking_id = '$booking_id'";
        mysqli_query($db, $update_query);
    }

    // Delete booking
    if(isset($_GET['delete_booking'])) {
        $booking_id = mysqli_real_escape_string($db, $_GET['delete_booking']);
        $delete_query = "DELETE FROM bookings WHERE booking_id = '$booking_id'";
        mysqli_query($db, $delete_query);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Manage Bookings | Admin Panel</title>
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
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Manage Table Bookings</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive m-t-40">
                                        <table id="bookings_table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Booking ID</th>
                                                    <th>Restaurant</th>
                                                    <th>Customer</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Seats</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT b.*, r.title as restaurant_name, 
                                                              CONCAT(u.f_name, ' ', u.l_name) as customer_name,
                                                              u.phone as customer_phone
                                                       FROM bookings b 
                                                       JOIN restaurant r ON b.restaurant_id = r.rs_id
                                                       JOIN users u ON b.user_id = u.u_id
                                                       ORDER BY b.booking_date DESC, b.booking_time DESC";
                                                $query = mysqli_query($db, $sql);
                                                if(!mysqli_num_rows($query) > 0) {
                                                    echo '<td colspan="8"><center>No Bookings Found</center></td>';
                                                } else {
                                                    while($rows = mysqli_fetch_array($query)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $rows['booking_id']; ?></td>
                                                    <td><?php echo $rows['restaurant_name']; ?></td>
                                                    <td>
                                                        <?php echo $rows['customer_name']; ?><br>
                                                        <small><?php echo $rows['customer_phone']; ?></small>
                                                    </td>
                                                    <td><?php echo date('F j, Y', strtotime($rows['booking_date'])); ?></td>
                                                    <td><?php echo date('g:i A', strtotime($rows['booking_time'])); ?></td>
                                                    <td><?php echo $rows['number_of_seats']; ?></td>
                                                    <td>
                                                        <form method="POST">
                                                            <input type="hidden" name="booking_id" value="<?php echo $rows['booking_id']; ?>">
                                                            <select name="status" class="form-control" onchange="this.form.submit()">
                                                                <option value="pending" <?php if($rows['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                                                <option value="confirmed" <?php if($rows['status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                                                                <option value="cancelled" <?php if($rows['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                                                            </select>
                                                            <input type="hidden" name="update_status" value="1">
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <a href="?delete_booking=<?php echo $rows['booking_id']; ?>" 
                                                           class="btn btn-danger btn-flat btn-addon btn-xs m-b-10" 
                                                           onclick="return confirm('Are you sure you want to delete this booking?');">
                                                            <i class="fa fa-trash-o"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
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
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#bookings_table').DataTable();
        });
    </script>
</body>
</html>
<?php } ?> 