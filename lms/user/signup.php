<?php 
    include '../include/connection.php';

    if(isset($_POST['submit']))
    {
        $name = $_POST['name'];
        $number = $_POST['number'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $password = $_POST['password'];
        $pincode = $_POST['pincode'];
        $dob = $_POST['dob'];
        $address = $_POST['address'];
        $default_status = $_POST['default_status'];

        date_default_timezone_set('Asia/Kolkata');
        $reg_date = date('d-m-y H:i:s');
        // $reg_time = date('H:i:s');

        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $number_exists = false;
    
            // Check number
            $check_number = "SELECT number FROM `user_data` WHERE number = '$number'";
            $number_result = $conn->query($check_number);
            if($number_result->num_rows > 0) 
            {
                echo "<script>alert('Phone number already exist! Try with different Nnunber')</script>";
                echo "<script>window.open('signup.php', '_self')</script>";
                $number_exists = true;
            }

            if (!$number_exists) 
            {
                $result = mysqli_query($conn, "INSERT INTO `user_data`(`name`, `number`, `email`, `gender`, `password`, `dob`, `status`, `pincode`, `address`, `reg-date`) VALUES ('$name','$number','$email','$gender','$password','$dob', '$default_status','$pincode', '$address','$reg_date')");
                if($result) 
                {
                    echo "<script>alert('Signup Success,  Click Ok to Login')</script>";
                    echo "<script>window.open('../index.php', '_self')</script>";
                    exit(); 
                } 
                else 
                {
                    echo "Query Failed: " . mysqli_error($conn); 
                }
            } 
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/user-dashboard-style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup</title>
</head>
<body>
    <section class="container-fluid">
        <div class="row mt-3 border-bottom">
            <div class="col-lg-6 col-sm-12 heading">
                <i class="fa-solid fa-book-open-cover fs-3"></i>
                <h4 class="ms-2 text-success">Library Management System</h4>
            </div>
        </div>
    </section>

    <header class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="form-div px-2 py-3">
                <h4 class="text-success text-center">Sign Up Form</h4>
                <div class="row">
                    <div class="col-lg-12 all-inputs mt-3 pb-3">
                        <form method="POST">
                            <input type="text" autocomplete="off" name="name" placeholder="Name" required>
                            <input type="text" autocomplete="off" name="number" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="10" placeholder="Mobile Number" required>
                            <input type="email" autocomplete="off" name="email" placeholder="Email" required>
                            <select name="gender" required>
                                <option value="">Gender</option>
                                <option value="Male">Male</option> 
                                <option value="Female">Female</option>
                            </select>
                            <input type="password" autocomplete="off" name="password" placeholder="Password" required>
                            <input type="text" autocomplete="off" name="dob" class="same" placeholder="Date of Birth" onfocus="(this.type='date')" onblur="if(!this.value) this.type='text'" required>
                            <input type="text" name="pincode" placeholder="Pincode" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="6">
                            <input type="text" autocomplete="off" name="address" placeholder="Address" required>
                            <input type="hidden" name="default_status" value="Active">
                            <input type="submit" value="Sign Up" name="submit" class="btn btn-success submit-btn">
                        </form>
                    </div>
                    <div class="other-links">
                        <a href="../index.php">User Login</a>
                        <a href="../admin/login.php">Admin Login</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
</body>
</html>