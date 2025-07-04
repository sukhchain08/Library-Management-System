<?php 
    include '../include/admin-privacy.php';
    include '../include/connection.php';

    $admin_number = $_SESSION['number'];
    $data_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE number = '$admin_number'");
    $result_admin = mysqli_fetch_assoc($data_admin);

    // if click on change password button 
    if(isset($_POST['change-password']))
    {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // select old password from the database 
        $old_password_result = mysqli_query($conn, "SELECT password FROM `admin` WHERE number = $admin_number ");
        $old_password_row = mysqli_fetch_assoc($old_password_result);

        // if old password match in the database then this block execute 
        if($old_password_row['password'] == $old_password)
        {
            // if new password and confirm password match then this block execute 
            if($new_password == $confirm_password)
            {   
                // Query for update password 
                $is_updated = mysqli_query($conn, "UPDATE `admin` SET `password` = '$new_password' WHERE number='$admin_number'");
                
                if($is_updated)
                {
                    echo "<script>alert('Password successfully changed')</script>";
                    echo "<script>window.open('dashboard.php', '_self')</script>";
                    exit();
                }
                else
                {
                    echo "<script>alert('Query Failed')</script>";
                    echo "<script>window.open('dashboard.php', '_self')</script>";
                    exit();
                }
            }
            else
            {
                echo "<script>alert('Confirm Password not match with new password')</script>";
                echo "<script>window.open('change-password.php', '_self')</script>";
                exit();
            }
        }
        else
        {
            echo "<script>alert('Current password not match with your default password')</script>";
            echo "<script>window.open('change-password.php', '_self')</script>";
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/admin-dashboard-style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body class="dashboard">
    <section class="container-fluid border-bottom pb-3">
        <?php include '../include/header.php'; ?>
    </section>

    <section class="container-fluid py-3">
        <?php include '../include/admin_sidebar.php'; ?>
        <div class="col-lg-10">
            <div class="pagination">
                <pre class="d-flex">
                    <p>Admin</p> / <a href="dashboard.php">Dashboard</a> / <a href="change-password.php">Change Password</a>
                </pre>
            </div>
            <div class="user-table">
                <h3 class="text-center mt-3 mb-4 fw-semibold">Change Password</h3>
                <div class="row">
                    <form method="POST">
                        <div class="col-12 col-lg-5 text-center container-fluid">
                            <div class="issue-new-book-div border p-2 text-start">
                                <span>
                                    <p>Current Password</p>
                                    <input type="password" name="old_password" required>
                                </span>
                                <span>
                                    <p>New Password Password</p>
                                    <input type="password" name="new_password" required>
                                </span>
                                <span>
                                    <p>Confirm Password</p>
                                    <input type="password" name="confirm_password" required>
                                </span>
                                <input type="submit" value="Change Password" onclick="return confirm('Are you sure to change password?')" name="change-password" class="btn btn-success mt-3">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>