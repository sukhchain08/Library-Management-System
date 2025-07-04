<?php 
    include '../include/admin-privacy.php';
    include '../include/connection.php';

    $admin_number = $_SESSION['number'];
    $data_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE number = '$admin_number'");
    $result_admin = mysqli_fetch_assoc($data_admin);

    $serial_no = $_GET['sr_no'];
    $data = mysqli_query($conn, "SELECT * FROM category where sr_no = '$serial_no'");
    $result = mysqli_fetch_assoc($data);

    if(isset($_POST['update']))
    {
        $category = $_POST['category'];
        date_default_timezone_set('Asia/Kolkata');
        $updation_date = date('d-m-y H:i');

        $update_query = mysqli_query($conn, "UPDATE `category` SET `type`='$category', `updation_date`='$updation_date' WHERE sr_no = $serial_no");
        if($update_query)
        {
            echo "<script>alert('Category Updated')</script>";
            echo "<script>window.open('category.php', '_self')</script>";
        }
        else
        {
            echo "<script>alert('Category Updated Failed')</script>";
            echo "<script>window.open('edit-category.php?sr_no=".$serial_no."', '_self')</script>";
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
    <title>Edit Category</title>
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
                    <p>Admin</p> / <a href="dashboard.php">Dashboard</a> / <a href="category.php">Total Categories</a> / <a href="edit-category.php?sr_no=<?php echo $serial_no; ?>">Edit Category</a>
                </pre>
            </div>
            <div class="category-div text-center book-table">
                <h3 class="my-3">Category Info</h3>
                <form method="POST" class="border mt-3 p-4 category-responsive" style="width: 400px; margin: 0 auto;">
                    <p class="text-start" style="font-weight: 500;">Category Name</p>
                    <input type="text" name="category" class="add-input" autocomplete="off" value="<?php echo  $result['type'] ?>" style="position: relative; top: -5px;">
                    <input type="submit" value="Update" name="update" class="btn btn-primary mt-3 text-start">
                </form>
            </div>
        </div>
    </section>
</body>
</html>