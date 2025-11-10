<?php
include("connection/connect.php");  
error_reporting(0);  
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user details
$user_query = "SELECT * FROM users WHERE u_id = '$user_id'";
$user_result = mysqli_query($db, $user_query);
$user_data = mysqli_fetch_assoc($user_result);

// Get order count
$orders_count_query = "SELECT COUNT(*) as total_orders FROM users_orders WHERE u_id = '$user_id'";
$orders_result = mysqli_query($db, $orders_count_query);
$orders_count = mysqli_fetch_assoc($orders_result)['total_orders'];

// Get bookings count
$bookings_count_query = "SELECT COUNT(*) as total_bookings FROM bookings WHERE user_id = '$user_id'";
$bookings_result = mysqli_query($db, $bookings_count_query);
$bookings_count = mysqli_fetch_assoc($bookings_result)['total_bookings'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    
    // Update basic info
    $update_query = "UPDATE users SET username='$username', address='$address', phone='$phone' WHERE u_id='$user_id'";
    
    if (mysqli_query($db, $update_query)) {
        // If password change is requested
        if (!empty($_POST['new_password']) && !empty($_POST['current_password'])) {
            $current_password = mysqli_real_escape_string($db, $_POST['current_password']);
            $new_password = mysqli_real_escape_string($db, $_POST['new_password']);
            
            // Verify current password
            if (password_verify($current_password, $user_data['password'])) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $password_update = "UPDATE users SET password='$hashed_password' WHERE u_id='$user_id'";
                mysqli_query($db, $password_update);
                $success_message = "Profile and password updated successfully!";
            } else {
                $error_message = "Current password is incorrect!";
            }
        } else {
            $success_message = "Profile updated successfully!";
        }
        
        // Refresh user data
        $user_result = mysqli_query($db, $user_query);
        $user_data = mysqli_fetch_assoc($user_result);
    } else {
        $error_message = "Error updating profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include("include/header.php"); ?>

<body>
    <div class="page-wrapper">
        <div class="container" style="margin-top: 120px; margin-bottom: 50px;">
            <div class="row">
                <div class="col-md-4">
                    <div class="card" style="border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: none;">
                        <div class="card-body text-center">
                            <div class="mb-4" style="width: 120px; height: 120px; background-color: #fff; border-radius: 60px; margin: 0 auto; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <i class="fa fa-user" style="font-size: 60px; color: #000;"></i>
                            </div>
                            <h4 class="card-title" style="color: #333; font-weight: 600; margin-bottom: 5px;">
                                <?php echo htmlspecialchars($user_data['username']); ?>
                            </h4>
                            <p class="text-muted" style="font-size: 14px;">
                                <?php echo htmlspecialchars($user_data['email']); ?>
                            </p>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <div class="stat-box p-3" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                        <h3 style="color: #e74c3c; font-weight: 600; margin-bottom: 5px;">
                                            <?php echo $orders_count; ?>
                                        </h3>
                                        <p class="mb-0" style="color: #666; font-size: 14px;">Orders</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-box p-3" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                        <h3 style="color: #e74c3c; font-weight: 600; margin-bottom: 5px;">
                                            <?php echo $bookings_count; ?>
                                        </h3>
                                        <p class="mb-0" style="color: #666; font-size: 14px;">Bookings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card" style="border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: none;">
                        <div class="card-body" style="padding: 30px;">
                            <h4 class="card-title mb-4" style="color: #333; font-weight: 600;">Edit Profile</h4>
                            
                            <?php if (isset($success_message)): ?>
                                <div class="alert alert-success" style="border-radius: 8px; border: none;">
                                    <?php echo $success_message; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($error_message)): ?>
                                <div class="alert alert-danger" style="border-radius: 8px; border: none;">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="form-group mb-4">
                                    <label style="color: #666; font-size: 14px; margin-bottom: 8px;">Username</label>
                                    <input type="text" class="form-control" name="username" 
                                           value="<?php echo htmlspecialchars($user_data['username']); ?>" required
                                           style="border-radius: 8px; padding: 12px; border: 1px solid #ddd;">
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label style="color: #666; font-size: 14px; margin-bottom: 8px;">Email</label>
                                    <input type="email" class="form-control" 
                                           value="<?php echo htmlspecialchars($user_data['email']); ?>" 
                                           readonly style="background-color: #f8f9fa; border-radius: 8px; padding: 12px; border: 1px solid #ddd;">
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label style="color: #666; font-size: 14px; margin-bottom: 8px;">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" 
                                           value="<?php echo htmlspecialchars($user_data['phone']); ?>"
                                           style="border-radius: 8px; padding: 12px; border: 1px solid #ddd;">
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label style="color: #666; font-size: 14px; margin-bottom: 8px;">Delivery Address</label>
                                    <textarea class="form-control" name="address" rows="3"
                                              style="border-radius: 8px; padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($user_data['address']); ?></textarea>
                                </div>
                                
                                <hr class="my-4" style="border-color: #eee;">
                                
                                <h5 class="mb-4" style="color: #333; font-weight: 600;">Change Password</h5>
                                <div class="form-group mb-4">
                                    <label style="color: #666; font-size: 14px; margin-bottom: 8px;">Current Password</label>
                                    <input type="password" class="form-control" name="current_password"
                                           style="border-radius: 8px; padding: 12px; border: 1px solid #ddd;">
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label style="color: #666; font-size: 14px; margin-bottom: 8px;">New Password</label>
                                    <input type="password" class="form-control" name="new_password"
                                           style="border-radius: 8px; padding: 12px; border: 1px solid #ddd;">
                                </div>
                                
                                <button type="submit" class="btn btn-primary" 
                                        style="background-color: #e74c3c; border: none; border-radius: 8px; padding: 12px 30px; font-weight: 500; width: 100%;">
                                    Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "include/footer.php" ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>
</html> 