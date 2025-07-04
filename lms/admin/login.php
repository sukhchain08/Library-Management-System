<?php 
    include '../include/connection.php';
    session_start();

    // Check when form is submitted 
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $number = $_POST['number'];
        $password = $_POST['password'];

        $_SESSION['admin_login_success'] = true;

        $_SESSION['number'] = $number;

        // Query to find the uid and password from database
        $sql = "SELECT * FROM `admin` WHERE number = ? AND password = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $number, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        // Checking number and password exist in database??
        if($result->num_rows > 0) {
            echo "<script>window.open('dashboard.php', '_self')</script>";
            exit();
        }
        else {
            echo "<script>alert('Phone number or Password is wrong. Try again')</script>";
            echo "<script>window.open('login.php', '_self')</script>";
            exit();
        }

        $stmt->close();
        $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/index-style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        @media (max-width: 992px) {
            .user-form{
                width: 100% !important;
            }
        }
    </style>
</head>
<body>
    <section class="container-fluid border-bottom pb-3">
        <div class="row mt-3">
            <div class="col-lg-6 col-sm-12 heading">
                <i class="fa-solid fa-book-open-cover fs-3"></i>
                <h4 class="ms-2 text-success">Library Management System</h4>
            </div>
            <div class="col-lg-6 col-sm-12">
                <span class="heading float-lg-end me-3">
                    <a href=""><i class="fa-brands fa-youtube"></i></a>
                    <a href=""><i class="fa-brands fa-facebook"></i></a>
                    <a href=""><i class="fa-brands fa-instagram"></i></a>
                </span>
            </div>
        </div>
    </section>

    <header class="container-fluid">
        <div class="row mt-5">
            <div class="d-none d-lg-block col-lg-6 mt-3">
                <img src="../images/admin-login.jpg" class="img-fluid mx-auto d-block" alt="">
            </div>
            <div class="col-lg-6 user-div mt-3">
                <div class="user-form px-2 py-3">
                    <h3 class="text-center">Admin Login</h3>
                    <form method="POST">
                         <div class="alert-div float-start"></div>
                        <div class="all-input mt-4">
                            <div class="single-input px-1">
                                <i class="fa-solid fa-phone pe-2"></i>
                                <input type="text" name="number" autocomplete="off" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="10" placeholder="Enter Phone Number">
                            </div>

                            <div class="single-input px-1">
                                <i class="fa-solid fa-lock pe-2"></i>
                                <input type="password" name="password" placeholder="Enter Password">
                            </div>
                            
                            <div class="single-input px-1 mt-4 border-0">
                                <input type="submit" value="Login" name="submit" class="btn btn-success fw-semibold">
                            </div>
                        </div>
                    </form>
                    <div class="other-links">
                        <a href="../index.php">User Login</a>
                        <a href="forget-password.php">Forget Password ?</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
</body>
</html>