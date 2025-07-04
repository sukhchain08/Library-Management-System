<?php 
    include '../include/user-privacy.php';
    include '../include/connection.php';

    $user_number = $_SESSION['number'];
    $data = mysqli_query($conn, "SELECT * FROM `user_data` WHERE number = '$user_number'");
    $result = mysqli_fetch_assoc($data);

    if (isset($_POST['update-detail'])) {
        $name = $_POST['name'];
        $number = $_POST['number'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];

        // Check if the phone number has changed
        if ($number != $result['number']) // Important comparison!
        { 
            // Only if the number changed, perform the duplicate check
            $check_number = "SELECT number FROM `user_data` WHERE number = '$number'";
            $number_result = $conn->query($check_number);

            if ($number_result->num_rows > 0) 
            {
                echo "<script>alert('Phone Number is already exist! Try with different phone number')</script>";
                echo "<script>window.open('edit-profile.php', '_self')</script>";
                exit(); // Stop processing to prevent the update
            }
        }

        // If the number is the same OR the number is new and doesn't exist, proceed with the update
        $update_query = mysqli_query($conn, "UPDATE `user_data` SET `name`='$name', `number`='$number', `email`='$email', `dob`='$dob' WHERE number='$user_number'");
        $update_into_issuebook = mysqli_query($conn, "UPDATE `issued_books` SET `user_number`='$number' WHERE user_number='$user_number'");
        if ($update_query && $update_into_issuebook) 
        {
            $_SESSION['number'] = $number; // Update session only after successful database update
            echo "<script>alert('Update Success')</script>";
            echo "<script>window.open('profile.php', '_self')</script>";
        } 
        else 
        {
            echo "<script>alert('Update Failed')</script>";
            echo "<script>window.open('profile.php', '_self')</script>";
            exit();
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
    <title>Edit Profile</title>
</head>
<body>
    <section class="container-fluid border-bottom pb-3">
        <?php include '../include/header.php'; ?>
        <?php include '../include/user_responsive_sidebar.php'; ?>
    </section>

    <section class="container-fluid py-3">
        <?php include '../include/user_sidebar.php'; ?>

        <div class="col-lg-10">
            <div class="pagination">
                <pre class="d-flex">
                    <p>User</p> / <a href="dashboard.php">Dashboard</a> / <a href="profile.php">Profile</a> / <a href="edit-profile.php">Edit Profile</a>
                </pre>
            </div>

            <div class="user-profile">
                <form method="POST">
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <th class="edit-heading">Name</th>
                            <td><input type="text" class="edit-input" autocomplete="off" name="name" value="<?php echo $result['name']; ?>"></td>
                        </tr>
                        <tr>
                            <th class="edit-heading">Number</th>
                            <td><input type="text" class="edit-input" autocomplete="off" name="number" maxlength="10" value="<?php echo $result['number']; ?>"></td>
                        </tr>
                        <tr>
                            <th class="edit-heading">Email</th>
                            <td><input type="text" class="edit-input" autocomplete="off" name="email" value="<?php echo $result['email']; ?>"></td>
                        </tr>
                        <tr>
                            <th class="edit-heading">DOB</th>
                            <td><input type="text" class="edit-input" autocomplete="off" name="dob" value="<?php echo $result['dob']; ?>" onfocus="(this.type='date')" onblur="if(!this.value) this.type='text'"></td>
                        </tr>
                        <tr>
                            <th class="edit-heading">Pin Code</th>
                            <td><input type="text" class="edit-input" name="" value="<?php echo $result['pincode']; ?>" disabled></td>
                        </tr>
                        <tr>
                            <th class="edit-heading">Address</th>
                            <td><input type="text" class="edit-input" name="" value="<?php echo $result['address']; ?>" disabled></td>
                        </tr>
                    </table>
                    <input type="submit" value="Save Changes" name="update-detail" class="btn btn-success">
                </form>
            </div>
        </div>
    </section>
</body>
</html>