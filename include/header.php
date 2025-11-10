<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include("connection/connect.php");
error_reporting(0);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Taste Transport</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }
        .profile-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1);
            z-index: 1;
            border-radius: 8px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }
        .profile-dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        .profile-dropdown-content a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .profile-dropdown-content a:hover {
            background-color: #f8f9fa;
            color: #e74c3c;
        }
        .profile-dropdown:hover .profile-dropdown-content {
            display: block;
        }
        /* Add a small invisible area to prevent dropdown from closing */
        .profile-dropdown::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            height: 20px;
            background: transparent;
        }
        .profile-btn {
            background: none;
            border: none;
            color: white;
            padding: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 15px;
        }
        .profile-btn i.fa-user {
            margin-right: 8px;
            font-size: 16px;
            background: #fff;
            color: #000;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .profile-btn i.fa-caret-down {
            margin-left: 5px;
            font-size: 14px;
        }
        
        /* Search bar styles */
        .search-form {
            margin: 0;
            padding: 0;
        }
        .search-container {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 25px;
            overflow: hidden;
            width: 300px;
        }
        .search-input {
            border: none;
            padding: 10px 15px;
            width: 100%;
            outline: none;
            font-size: 14px;
            background: transparent;
        }
        .search-button {
            background: #e74c3c;
            border: none;
            padding: 10px 20px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-button:hover {
            background: #c0392b;
        }
        .search-button i {
            font-size: 16px;
        }
    </style>
</head>

<body>
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark" style="background-color: #000000; padding: 0; width: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 0.5rem 2rem;">
                <!-- Logo Section -->
                <div class="navbar-header" style="padding: 0;">
                    <a class="navbar-brand" href="index.php" style="padding: 0; margin: 0;">
                        <img src="admin/images/icn.png" alt="Taste Transport Logo" style="max-height: 40px; width: auto;">
                    </a>
                </div>

                <!-- Navigation Menu -->
                <div style="display: flex; align-items: center; gap: 30px;">
                    <ul class="nav" style="display: flex; align-items: center; margin: 0; padding: 0; gap: 30px;">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="restaurants.php">Restaurants</a></li>
                        <?php if(!empty($_SESSION["user_id"])) { ?>
                            <li class="nav-item"><a class="nav-link" href="book_table.php">Book Table</a></li>
                        <?php } ?>
                    </ul>

                    <!-- Search Form -->
                    <form class="search-form" action="index.php" method="GET">
                        <div class="search-container">
                            <input type="text" name="search" class="search-input" placeholder="Search restaurants or dishes..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button type="submit" class="search-button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- User Profile/Auth Section -->
                    <?php
                    if(empty($_SESSION["user_id"])) {
                        echo '<ul class="nav" style="display: flex; align-items: center; margin: 0; padding: 0; gap: 20px;">
                                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                                <li class="nav-item"><a class="nav-link" href="registration.php">Register</a></li>
                              </ul>';
                    } else {
                        // Get user details
                        $user_id = $_SESSION["user_id"];
                        $user_query = mysqli_query($db, "SELECT * FROM users WHERE u_id='$user_id'");
                        $user_data = mysqli_fetch_assoc($user_query);
                        ?>
                        <div class="profile-dropdown">
                            <button class="profile-btn">
                                <i class="fa fa-user"></i>
                                <?php echo htmlspecialchars($user_data['username']); ?>
                                <i class="fa fa-caret-down"></i>
                            </button>
                            <div class="profile-dropdown-content">
                                <a href="my_profile.php"><i class="fa fa-user"></i> My Profile</a>
                                <a href="your_orders.php"><i class="fa fa-list"></i> My Orders</a>
                                <a href="my_bookings.php"><i class="fa fa-calendar"></i> My Bookings</a>
                                <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </nav>
    </header>
</body>
</html> 