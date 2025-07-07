<?php 
    include '../include/admin-privacy.php';
    include '../include/connection.php';

    $admin_number = $_SESSION['number'];
    $data_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE number = '$admin_number'");
    $result_admin = mysqli_fetch_assoc($data_admin);

    $sr_no = $_GET['sr_no'];
    $usernumber = $_GET['usernumber'];
    $bookid = $_GET['bookid'];
    $issued_book_detail_query = mysqli_query($conn, "SELECT * FROM issued_books WHERE user_number = '$usernumber' AND book_id = '$bookid' AND sr_no = '$sr_no'");
    $issued_book_detail = mysqli_fetch_assoc($issued_book_detail_query);

    $book_table_query = mysqli_query($conn, "SELECT * FROM books WHERE sr_no = '$bookid'");
    $book_table_data = mysqli_fetch_assoc($book_table_query);

    if(isset($_POST['return-book']))
    {
        $return_status = $_POST['return_status'];
        $fine = $_POST['fine'];

        date_default_timezone_set('Asia/Kolkata');
        $return_date = date('d-m-y H:i:s');

        $return_query = mysqli_query($conn, "UPDATE `issued_books` SET `return_date`='$return_date',`return_status`='$return_status',`fine`='$fine' WHERE user_number = '$usernumber' AND book_id = '$bookid'");
        $increment_copies_query = mysqli_query($conn, "UPDATE `books` SET available_copies = available_copies + 1 WHERE sr_no = '$bookid'");
        if($return_query && $increment_copies_query)
        {
            echo "<script>alert('Book Retured')</script>";
            echo "<script>window.open('manage-issued-books.php', '_self')</script>";
        }
        else
        {
            echo "<script>alert('Book Retured Failed! Database error occor')</script>";
            echo "<script>window.open('manage-issued-books.php', '_self')</script>";
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
    <title>Update Issued Book Detail</title>
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
                    <p>Admin</p> / <a href="dashboard.php">Dashboard</a> / <a href="manage-issued-books.php">Manage Issued Books</a> / <a href="update-issued-book-detail.php?usernumber=<?php echo $usernumber?>&bookid=<?php echo $bookid ?>">Update Detail</a>
                </pre> 
            </div>
            <div class="user-table">
                <h3 class="text-center mt-3 mb-4 fw-semibold">Issued Book Detail</h3>
                <div class="row">
                    <form method="POST">
                        <div class="col-12 col-lg-8 text-center container-fluid">
                            <div class="issued-book-div border p-2 text-start">
                                <div class="row g-2"> 
                                    <div class="col-12 col-md-6">
                                        <span class="d-flex p-2 w-100">
                                            <p class="me-2">User Name:</p> 
                                            <p style="font-weight: 500;"><?php echo $issued_book_detail['user_name']; ?></p>
                                        </span>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <span class="d-flex p-2 w-100">
                                            <p class="me-2">User Number:</p> 
                                            <p style="font-weight: 500;"><?php echo $issued_book_detail['user_number']; ?></p>
                                        </span>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <span class="d-flex p-2 w-100">
                                            <p class="me-2">Book Name:</p> 
                                            <p style="font-weight: 500;"><?php echo $issued_book_detail['book_title']; ?></p>
                                        </span>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <span class="d-flex p-2 w-100">
                                            <p class="me-2">Publisher:</p> 
                                            <p style="font-weight: 500;"><?php echo $book_table_data['publisher']; ?></p>
                                        </span>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <span class="d-flex p-2 w-100">
                                            <p class="me-2">Issue Date:</p> 
                                            <p style="font-weight: 500;"><?php echo $issued_book_detail['issue_date']; ?></p>
                                        </span>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <span class="d-flex p-2 w-100">
                                            <p class="me-2">Return Date:</p> 
                                            <p style="font-weight: 500;"><?php echo ($issued_book_detail['return_date'] == '' ? "Not Return Yet" : $issued_book_detail['return_date']) ?></p>
                                        </span>
                                    </div>
                                    <div class="col-12">
                                        <span class="d-flex p-2 w-100">
                                            <p class="me-2">Book Image:</p> 
                                            <p style="font-weight: 500;"><?php echo ".<img src='".$book_table_data['image']."' alt='Book Image' width='120px' height='150px'>."?></p>
                                        </span>
                                    </div>
                                    <div class="col-12">
                                        <span class="d-flex p-2 w-100">
                                            <p>Fine:</p> <br> 
                                            <input type="text" name="fine" onkeypress='return event.charCode >= 48 && event.charCode <= 57' inputmode="numeric" autocomplete="off" class="edit-input">
                                        </span>
                                    </div>
                                </div>
                                <input type="hidden" name="return_status" value="return">
                                <input type="submit" value="Return Book" name="return-book" class="btn btn-success">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
