<?php
include("connection/connect.php");  
error_reporting(0);  
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all restaurants
$restaurants_query = "SELECT * FROM restaurant ORDER BY title";
$restaurants_result = mysqli_query($db, $restaurants_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $restaurant_id = mysqli_real_escape_string($db, $_POST['restaurant_id']);
    $booking_date = mysqli_real_escape_string($db, $_POST['booking_date']);
    $booking_time = mysqli_real_escape_string($db, $_POST['booking_time']);
    $number_of_seats = mysqli_real_escape_string($db, $_POST['number_of_seats']);

    $insert_query = "INSERT INTO bookings (user_id, restaurant_id, booking_date, booking_time, number_of_seats) 
                     VALUES ('$user_id', '$restaurant_id', '$booking_date', '$booking_time', '$number_of_seats')";
    
    if (mysqli_query($db, $insert_query)) {
        $success_message = "Booking submitted successfully!";
    } else {
        $error_message = "Error submitting booking. Please try again.";
    }
}
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
                    <h2 class="text-center mb-4" style="color: #333;">Book a Table</h2>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <form method="POST" class="booking-form form">
                                <div class="form-group mb-4">
                                    <label for="restaurant_id" style="font-weight: 600; color: #555;">Select Restaurant</label>
                                    <select class="form-control" id="restaurant_id" name="restaurant_id" required style="height: 45px; border-radius: 4px;">
                                        <option value="">Choose a restaurant</option>
                                        <?php while ($restaurant = mysqli_fetch_assoc($restaurants_result)): ?>
                                            <option value="<?php echo $restaurant['rs_id']; ?>">
                                                <?php echo $restaurant['title']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="booking_date" style="font-weight: 600; color: #555;">Date</label>
                                    <input type="date" class="form-control" id="booking_date" name="booking_date" 
                                           min="<?php echo date('Y-m-d'); ?>" required style="height: 45px; border-radius: 4px;">
                                </div>

                                <div class="form-group mb-4">
                                    <label for="booking_time" style="font-weight: 600; color: #555;">Time</label>
                                    <input type="time" class="form-control" id="booking_time" name="booking_time" required 
                                           style="height: 45px; border-radius: 4px;">
                                </div>

                                <div class="form-group mb-4">
                                    <label for="number_of_seats" style="font-weight: 600; color: #555;">Number of Seats</label>
                                    <input type="number" class="form-control" id="number_of_seats" name="number_of_seats" 
                                           min="1" max="20" required style="height: 45px; border-radius: 4px;">
                                </div>

                                <button type="submit" class="btn btn-primary w-100" style="height: 45px; font-size: 16px; font-weight: 600;">
                                    Book Table
                                </button>
                            </form>
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