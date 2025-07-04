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
    <title>User Dashboard</title>
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
                        <p>User</p> / <a href="dashboard.php">Dashboard</a>
                    </pre>
                </div>
                
                <div class="d-flex " style="flex-wrap: wrap;">
                    <span class="profile-link col-12 col-md-6 p-2 me-1 mb-2 bg-primary">
                        <a href="profile.php">
                            <div class="top-div border-bottom">
                                <p>Hello, <?php echo $result['name']; ?></p>
                            </div>
                            <div class="bottom-div mt-3">
                                <p>View Profile</p>
                            </div>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </section>
</body>
</html>