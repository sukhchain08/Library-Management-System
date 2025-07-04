<?php 
    include '../include/admin-privacy.php';
    include '../include/connection.php';

    $admin_number = $_SESSION['number'];
    $data_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE number = '$admin_number'");
    $result_admin = mysqli_fetch_assoc($data_admin);

    // Total Users
    $user_query = mysqli_query($conn, "SELECT * FROM user_data");
    $total_user = mysqli_num_rows($user_query);
    
    // Total Books
    $books_query = mysqli_query($conn, "SELECT * FROM books");
    $total_books = mysqli_num_rows($books_query);
    
    // Total Category
    $category_query = mysqli_query($conn, "SELECT * FROM category");
    $total_category = mysqli_num_rows($category_query);

    
    // Total Issued Books
    $issued_books_query = mysqli_query($conn, "SELECT * FROM issued_books WHERE return_status = ''");
    $issued_books = mysqli_num_rows($issued_books_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/admin-dashboard-style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body class="dashboard" style="overflow: hidden;">
    <section class="container-fluid border-bottom pb-3">
        <?php include '../include/header.php'; ?>
    </section>

    <section class="container-fluid py-3">
        <?php include '../include/admin_sidebar.php'; ?>
            <div class="col-lg-10">
                <div class="pagination">
                    <pre class="d-flex">
                        <p>Admin</p> / <a href="dashboard.php">Dashboard</a>
                    </pre>
                </div>
                
                <div class="dashboard-all-links" >
                    <a href="total-user.php">
                        <div class="link">
                            <p>Total Users</p>
                            <p><?php echo $total_user; ?></p>
                        </div>
                    </a>
                    <a href="total-books.php">
                        <div class="link" style="background-color:rgb(119, 167, 80);">
                            <p>Total Books</p>
                            <p><?php echo $total_books; ?></p>
                        </div>
                    </a>
                    <a href="add-book.php">
                        <div class="link" style="background-color:rgba(81, 0, 115, 0.84);">
                            <p>Add New Book</p>
                            <p style="font-size: 15px; text-decoration: underline;">Click Here</p>
                        </div>
                    </a>
                    <a href="category.php">
                        <div class="link" style="background-color:rgba(0, 10, 88, 0.85);">
                            <p>Total Categories</p>
                            <p><?php echo $total_category; ?></p>
                        </div>
                    </a>
                    <a href="add-category.php">
                        <div class="link" style="background-color: #A0153E;">
                            <p>Add Category</p>
                            <p style="font-size: 15px; text-decoration: underline;">Click Here</p>
                        </div>
                    </a>
                    <a href="issue-book.php">
                        <div class="link" style="background-color: #2c9092;">
                            <p>Issue New Book</p>
                            <p style="font-size: 15px; text-decoration: underline;">Click Here</p>
                        </div>
                    </a>
                    <a href="manage-issued-books.php">
                        <div class="link" style="background-color: #1d8b40;">
                            <p>Manage Issued Books</p>
                            <p><?php echo $issued_books ?></p>
                        </div>
                    </a>
                    <a href="change-password.php" class="d-block d-lg-none">
                        <div class="link" style="background-color: #b771e5;">
                            <p>Change Password</p>
                            <p style="font-size: 15px; text-decoration: underline;">Click Here</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>