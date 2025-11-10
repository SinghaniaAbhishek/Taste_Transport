<?php
include("connection/connect.php");  
error_reporting(0);  
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle booking cancellation
if (isset($_POST['cancel_booking'])) {
    $booking_id = mysqli_real_escape_string($db, $_POST['booking_id']);
    $update_query = "UPDATE bookings SET status = 'cancelled' WHERE booking_id = '$booking_id' AND user_id = '$user_id'";
    mysqli_query($db, $update_query);
}

// Get user's bookings
$bookings_query = "SELECT b.*, r.title as restaurant_name 
                   FROM bookings b 
                   JOIN restaurant r ON b.restaurant_id = r.rs_id 
                   WHERE b.user_id = '$user_id' 
                   ORDER BY b.booking_date DESC, b.booking_time DESC";
$bookings_result = mysqli_query($db, $bookings_query);
?>

<!DOCTYPE html>
<html lang="en">
<?php
include("include/header.php");
?>

<body>
    <div class="page-wrapper">
        <div class="container" style="margin-top: 150px; margin-bottom: 50px;">
            <div class="widget">
                <div class="widget-body">
                    <h2 class="text-center mb-4" style="color: #333;">My Bookings</h2>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (mysqli_num_rows($bookings_result) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover" style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        <thead style="background: #f8f9fa;">
                                            <tr>
                                                <th style="padding: 15px;">Restaurant</th>
                                                <th style="padding: 15px;">Date</th>
                                                <th style="padding: 15px;">Time</th>
                                                <th style="padding: 15px;">Seats</th>
                                                <th style="padding: 15px;">Status</th>
                                                <th style="padding: 15px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($booking = mysqli_fetch_assoc($bookings_result)): ?>
                                                <tr>
                                                    <td style="padding: 15px; vertical-align: middle;"><?php echo $booking['restaurant_name']; ?></td>
                                                    <td style="padding: 15px; vertical-align: middle;"><?php echo date('F j, Y', strtotime($booking['booking_date'])); ?></td>
                                                    <td style="padding: 15px; vertical-align: middle;"><?php echo date('g:i A', strtotime($booking['booking_time'])); ?></td>
                                                    <td style="padding: 15px; vertical-align: middle;"><?php echo $booking['number_of_seats']; ?></td>
                                                    <td style="padding: 15px; vertical-align: middle;">
                                                        <span class="badge bg-<?php 
                                                            echo $booking['status'] == 'confirmed' ? 'success' : 
                                                                ($booking['status'] == 'pending' ? 'warning' : 'danger'); 
                                                        ?>" style="font-size: 12px; padding: 8px 12px;">
                                                            <?php echo ucfirst($booking['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td style="padding: 15px; vertical-align: middle;">
                                                        <?php if ($booking['status'] == 'pending'): ?>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                                                                <button type="submit" name="cancel_booking" class="btn btn-danger btn-sm" 
                                                                        style="padding: 5px 10px;"
                                                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                                    Cancel
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info" style="border-radius: 8px;">
                                    You haven't made any bookings yet. 
                                    <a href="book_table.php" class="alert-link">Book a table now!</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center">&copy; <?php echo date('Y'); ?> Taste Transport. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.js"></script>
    <script src="js/jquery.isotope.js"></script>
    <script src="js/theme.js"></script>
</body>
</html> 