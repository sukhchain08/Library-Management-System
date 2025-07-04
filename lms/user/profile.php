<?php 
    include '../include/user-privacy.php';
    
    include '../include/connection.php';
    $user_number = $_SESSION['number'];
    $data = mysqli_query($conn, "SELECT * FROM `user_data` WHERE number = '$user_number'");
    $result = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/user-dashboard-style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
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
                    <p>User</p> / <a href="dashboard.php">Dashboard</a> / <a href="profile.php">Profile</a>
                </pre>
            </div>

            <div class="d-flex fw-semibold">
                <i class="fa-regular fa-pen-to-square pe-2" style="color: #0d6efd;"></i>
                <a href="edit-profile.php" class="text-primary edit-detail-link" style="font-size: 17px; margin-top: -3px;">Edit Details</a>
            </div>

            <div class="user-profile">
                <table class="table table-bordered table-responsive">
                    <tr>
                        <th class="user-heading">Name</th>
                        <td class="user-value"><?php echo $result['name']; ?></td>
                    </tr>
                    <tr>
                        <th class="user-heading">Number</th>
                        <td class="user-value"><?php echo $result['number']; ?></td>
                    </tr>
                    <tr>
                        <th class="user-heading">Email</th>
                        <td class="user-value"><?php echo $result['email']; ?></td>
                    </tr>
                    <tr>
                        <th class="user-heading">DOB</th>
                        <td class="user-value"><?php echo $result['dob']; ?></td>
                    </tr>
                    <tr>
                        <th class="user-heading">Address</th>
                        <td class="user-value"><?php echo $result['address']; ?></td>
                    </tr>
                    <tr>
                        <th class="user-heading">Joining Date</th>
                        <td class="user-value"><?php echo $result['reg-date']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
</body>
</html>