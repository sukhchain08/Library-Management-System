<?php 
    include '../include/admin-privacy.php';
    include '../include/connection.php';

    $admin_number = $_SESSION['number'];
    $data_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE number = '$admin_number'");
    $result_admin = mysqli_fetch_assoc($data_admin);

    if(isset($_POST['add-category']))
    {
        $category = $_POST['category'];
        date_default_timezone_set('Asia/Kolkata');
        $updation_date = date('d-m-y H:i');

        $insert_query = mysqli_query($conn, "INSERT INTO `category`(`type`, `creation_date`) VALUES ('$category', '$updation_date')");
        if($insert_query)
        {
            echo "<script>alert('Category Added')</script>";
            echo "<script>window.open('add-category.php', '_self')</script>";
        }
        else
        {
            echo "<script>alert('Failed')</script>";
            echo "<script>window.open('add-category.php', '_self')</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @media (max-width: 992px)
        {
            .category-responsive{
                width: 270px !important;
            }
        }
    </style>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/admin-dashboard-style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
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
                    <p>Admin</p> / <a href="dashboard.php">Dashboard</a> / <a href="add-category.php">Add Categories</a>
                </pre>
            </div>
            <div class="category-div text-center book-table">
                <h3 class="my-3">Add Category</h3>
                <form method="POST" class="border mt-3 p-4 category-responsive" style="width: 400px; margin: 0 auto;">
                    <p class="text-start" style="font-weight: 500;">New Category Name</p>
                    <input type="text" name="category" class="add-input" placeholder="Enter Category Name" autocomplete="off" style="letter-spacing: .4px; position: relative; top: -5px;">
                    <input type="submit" value="Add Category" name="add-category" class="btn btn-primary mt-3 text-start">
                </form>
            </div>
        </div>
        </div>
    </section>
</body>
</html>