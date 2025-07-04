<?php 
    include 'include/connection.php';
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $number = $_POST['number'];
        $password = $_POST['password'];

        // Step 1: First check if user with the given number exists
        $sql = "SELECT * FROM `user_data` WHERE number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $number);
        $stmt->execute();
        $result = $stmt->get_result();

        // Step 2: If user exists, check status
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ($user['status'] === 'Blocked') {
                echo "<script>alert('Your account has been blocked. Please contact admin.');</script>";
                echo "<script>window.open('index.php', '_self');</script>";
                exit();
            } elseif ($user['status'] === 'Active') {
                // Step 3: Check password
                if ($user['password'] === $password) {
                    $_SESSION['user_login_success'] = true;
                    $_SESSION['number'] = $number;

                    echo "<script>window.open('user/dashboard.php', '_self');</script>";
                    exit();
                } else {
                    echo "<script>alert('Incorrect password. Try again.');</script>";
                    echo "<script>window.open('index.php', '_self');</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Unknown account status. Please contact support.');</script>";
                echo "<script>window.open('index.php', '_self');</script>";
                exit();
            }
        } else {
            echo "<script>alert('No account found with this phone number.');</script>";
            echo "<script>window.open('index.php', '_self');</script>";
            exit();
        }

        $stmt->close();
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include/links.php'; ?>
    <link rel="stylesheet" href="styles/index-style.css">
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
                <img src="images/library.jpg" class="img-fluid mx-auto d-block" alt="">
            </div>
            <div class="col-lg-6 user-div mt-3">
                <div class="user-form px-2 py-3">
                    <h3 class="text-center">User Login</h3>
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
                        <a href="user/signup.php">Create Account</a>
                        <a href="user/forget-password.php">Forget Password ?</a>
                        <a href="admin/login.php">Admin Login</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
</body>
</html>