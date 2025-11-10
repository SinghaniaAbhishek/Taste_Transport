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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Manage Discounts || Taste Transport</title>
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
                <!-- Restaurant Discounts -->
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Manage Restaurant Discounts</h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if(isset($_POST['submit_restaurant']))
                                    {
                                        $rs_id = $_POST['rs_id'];
                                        $discount = $_POST['discount'];
                                        $valid_until = $_POST['valid_until'];
                                        
                                        $sql = "UPDATE restaurant SET discount = '$discount', discount_valid_until = '$valid_until' WHERE rs_id = '$rs_id'";
                                        mysqli_query($db, $sql);
                                        echo "<script>alert('Restaurant discount updated successfully!');</script>";
                                    }

                                    // Handle cancel restaurant discount
                                    if(isset($_GET['cancel_restaurant_discount']))
                                    {
                                        $rs_id = $_GET['cancel_restaurant_discount'];
                                        $sql = "UPDATE restaurant SET discount = '0.00', discount_valid_until = NULL WHERE rs_id = '$rs_id'";
                                        mysqli_query($db, $sql);
                                        echo "<script>alert('Restaurant discount cancelled successfully!');</script>";
                                        echo "<script>window.location.href='manage_discounts.php';</script>";
                                    }
                                    ?>
                                    
                                    <form method="post" action="">
                                        <div class="form-body">
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Select Restaurant</label>
                                                        <select name="rs_id" class="form-control" required>
                                                            <option value="">Select Restaurant</option>
                                                            <?php
                                                            $sql = "SELECT * FROM restaurant";
                                                            $result = mysqli_query($db, $sql);
                                                            while($row = mysqli_fetch_assoc($result)) {
                                                                echo "<option value='".$row['rs_id']."'>".$row['title']."</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Discount Percentage</label>
                                                        <input type="number" name="discount" class="form-control" min="0" max="100" step="0.01" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Valid Until</label>
                                                        <input type="date" name="valid_until" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="submit_restaurant" class="btn btn-primary"> <i class="fa fa-check"></i> Set Restaurant Discount</button>
                                        </div>
                                    </form>

                                    <div class="table-responsive m-t-40">
                                        <table id="restaurantTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Restaurant</th>
                                                    <th>Discount</th>
                                                    <th>Valid Until</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM restaurant";
                                                $result = mysqli_query($db, $sql);
                                                while($row = mysqli_fetch_assoc($result)) {
                                                    $status = '';
                                                    if($row['discount'] > 0) {
                                                        if(strtotime($row['discount_valid_until']) > time()) {
                                                            $status = '<span class="badge badge-success">Active</span>';
                                                        } else {
                                                            $status = '<span class="badge badge-danger">Expired</span>';
                                                        }
                                                    } else {
                                                        $status = '<span class="badge badge-secondary">No Discount</span>';
                                                    }
                                                    echo "<tr>
                                                        <td>".$row['title']."</td>
                                                        <td>".$row['discount']."%</td>
                                                        <td>".($row['discount_valid_until'] ? date('Y-m-d', strtotime($row['discount_valid_until'])) : 'N/A')."</td>
                                                        <td>".$status;
                                                        if($row['discount'] > 0 && strtotime($row['discount_valid_until']) > time()) {
                                                            echo " <a href='manage_discounts.php?cancel_restaurant_discount=".$row['rs_id']."' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to cancel this discount?\")'>Cancel Discount</a>";
                                                        }
                                                        echo "</td>
                                                    </tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dish Discounts -->
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Manage Dish Discounts</h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if(isset($_POST['submit_dish'])) {
                                        $dish_id = $_POST['dish_id'];
                                        $discount_percentage = $_POST['discount_percentage'];
                                        $start_date = $_POST['start_date'];
                                        $end_date = $_POST['end_date'];
                                        
                                        // Validate input
                                        if($discount_percentage <= 0 || $discount_percentage > 100) {
                                            echo "<div class='alert alert-danger'>Discount percentage must be between 0 and 100</div>";
                                        } else if($start_date > $end_date) {
                                            echo "<div class='alert alert-danger'>End date must be after start date</div>";
                                        } else {
                                            // Check if discount already exists for this dish
                                            $check_sql = "SELECT * FROM dish_discounts WHERE dish_id = ? AND status = 'active'";
                                            $stmt = $db->prepare($check_sql);
                                            $stmt->bind_param("i", $dish_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            
                                            if($result->num_rows > 0) {
                                                // Update existing discount
                                                $sql = "UPDATE dish_discounts SET 
                                                        discount_percentage = ?,
                                                        start_date = ?,
                                                        end_date = ?
                                                        WHERE dish_id = ? AND status = 'active'";
                                                $stmt = $db->prepare($sql);
                                                $stmt->bind_param("dssi", $discount_percentage, $start_date, $end_date, $dish_id);
                                            } else {
                                                // Insert new discount
                                                $sql = "INSERT INTO dish_discounts(dish_id, discount_percentage, start_date, end_date) 
                                                        VALUES(?, ?, ?, ?)";
                                                $stmt = $db->prepare($sql);
                                                $stmt->bind_param("idss", $dish_id, $discount_percentage, $start_date, $end_date);
                                            }
                                            
                                            if($stmt->execute()) {
                                                echo "<div class='alert alert-success'>Dish discount updated successfully</div>";
                                            } else {
                                                echo "<div class='alert alert-danger'>Error updating discount: " . $db->error . "</div>";
                                            }
                                        }
                                    }

                                    // Handle dish discount deletion
                                    if(isset($_GET['cancel_dish_discount'])) {
                                        $discount_id = $_GET['cancel_dish_discount'];
                                        $sql = "UPDATE dish_discounts SET status = 'inactive' WHERE discount_id = ?";
                                        $stmt = $db->prepare($sql);
                                        $stmt->bind_param("i", $discount_id);
                                        if($stmt->execute()) {
                                            echo "<script>alert('Dish discount removed successfully');</script>";
                                            echo "<script>window.location.href='manage_discounts.php';</script>";
                                        } else {
                                            echo "<div class='alert alert-danger'>Error removing discount</div>";
                                        }
                                    }
                                    ?>
                                    
                                    <form method="post" action="">
                                        <div class="form-body">
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Select Dish</label>
                                                        <select name="dish_id" class="form-control" required>
                                                            <option value="">Select Dish</option>
                                                            <?php
                                                            $sql = "SELECT d.d_id, d.title, r.title as restaurant 
                                                                    FROM dishes d 
                                                                    JOIN restaurant r ON d.rs_id = r.rs_id 
                                                                    ORDER BY r.title, d.title";
                                                            $result = $db->query($sql);
                                                            while($row = $result->fetch_assoc()) {
                                                                echo "<option value='".$row['d_id']."'>".$row['restaurant']." - ".$row['title']."</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Discount Percentage</label>
                                                        <input type="number" name="discount_percentage" class="form-control" min="0" max="100" step="0.01" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Start Date</label>
                                                        <input type="date" name="start_date" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">End Date</label>
                                                        <input type="date" name="end_date" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="submit_dish" class="btn btn-primary"> <i class="fa fa-check"></i> Set Dish Discount</button>
                                        </div>
                                    </form>

                                    <div class="table-responsive m-t-40">
                                        <table id="dishTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Restaurant</th>
                                                    <th>Dish</th>
                                                    <th>Discount</th>
                                                    <th>Valid From</th>
                                                    <th>Valid Until</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT dd.*, d.title as dish_name, r.title as restaurant_name,
                                                              CASE 
                                                                  WHEN dd.status = 'active' AND CURRENT_DATE BETWEEN dd.start_date AND dd.end_date THEN 'Active'
                                                                  WHEN dd.status = 'active' AND CURRENT_DATE < dd.start_date THEN 'Upcoming'
                                                                  WHEN dd.status = 'active' AND CURRENT_DATE > dd.end_date THEN 'Expired'
                                                                  ELSE 'Inactive'
                                                              END as discount_status
                                                        FROM dish_discounts dd
                                                        JOIN dishes d ON dd.dish_id = d.d_id
                                                        JOIN restaurant r ON d.rs_id = r.rs_id
                                                        WHERE dd.status = 'active'
                                                        ORDER BY r.title, d.title";
                                                $result = $db->query($sql);
                                                while($row = $result->fetch_assoc()) {
                                                    $status_class = '';
                                                    switch($row['discount_status']) {
                                                        case 'Active':
                                                            $status_class = 'badge-success';
                                                            break;
                                                        case 'Upcoming':
                                                            $status_class = 'badge-info';
                                                            break;
                                                        case 'Expired':
                                                            $status_class = 'badge-danger';
                                                            break;
                                                        default:
                                                            $status_class = 'badge-secondary';
                                                    }
                                                    
                                                    echo "<tr>
                                                        <td>".$row['restaurant_name']."</td>
                                                        <td>".$row['dish_name']."</td>
                                                        <td>".$row['discount_percentage']."%</td>
                                                        <td>".date('Y-m-d', strtotime($row['start_date']))."</td>
                                                        <td>".date('Y-m-d', strtotime($row['end_date']))."</td>
                                                        <td>
                                                            <span class='badge ".$status_class."'>".$row['discount_status']."</span>";
                                                    if($row['discount_status'] == 'Active' || $row['discount_status'] == 'Upcoming') {
                                                        echo " <a href='manage_discounts.php?cancel_dish_discount=".$row['discount_id']."' 
                                                                class='btn btn-danger btn-sm' 
                                                                onclick='return confirm(\"Are you sure you want to cancel this discount?\")'>
                                                                Cancel
                                                             </a>";
                                                    }
                                                    echo "</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('include/footer.php'); ?>
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
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>
    <script>
        $(document).ready(function() {
            $('#restaurantTable').DataTable();
            $('#dishTable').DataTable();
        });
    </script>
</body>
</html>
<?php
}
?> 