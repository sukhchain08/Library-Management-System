<?php
    include '../include/admin-privacy.php';
    include '../include/connection.php';

    $admin_number = $_SESSION['number'];
    $data_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE number = '$admin_number'");
    $result_admin = mysqli_fetch_assoc($data_admin);

    $book_id = $_GET['id'];
    $book_data_query = mysqli_query($conn, "SELECT * FROM books WHERE sr_no = '$book_id'");
    $book_data = mysqli_fetch_assoc($book_data_query);

    if(isset($_POST['save-changes']))
    {
        $book_name = $_POST['book_name'];
        $author = $_POST['author'];
        $publisher = $_POST['publisher'];
        $total_copies = $_POST['total_copies'];

        date_default_timezone_set('Asia/Kolkata');
        $updation_date = date('d-m-y H:i');

        $update_query = mysqli_query($conn, "UPDATE `books` SET `title`='$book_name', `author`='$author', `publisher`='$publisher', `total_copies`='$total_copies', `updation_date`='$updation_date' WHERE sr_no = '$book_id'");
        if($update_query)
        {
            echo "<script>alert('Changes Successfull')</script>";
            echo "<script>window.open('total-books.php', '_self')</script>";
        }
        else
        {
            echo "<script>alert('Changes Failed')</script>";
            echo "<script>window.open('edit-book.php?id=$book_id', '_self')</script>";
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
    <title>Edit Book</title>
</head>
<body class="dashboard">
    <section class="container-fluid border-bottom pb-3">
        <?php include '../include/header.php'; ?>
    </section>

    <div class="container-fluid py-3">
        <?php include '../include/admin_sidebar.php'; ?>
        <div class="col-lg-10">
            <div class="pagination">
                <pre class="d-flex">
                    <p>Admin</p> / <a href="dashboard.php">Dashboard</a> / <a href="total-books.php">Total Books</a> / <a href="edit-book.php?id=<?php echo $book_id; ?>">Edit Book Detail</a>
                </pre>
            </div>
            <div class="book-table">
                <h3 class="text-center mt-3 mb-4 fw-semibold">Books Detail</h3>
                <form method="POST">
                    
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <th width="30%" class="text-nowrap">Book Name</th>
                            <td><input type="text" name="book_name" class="add-input" value="<?php echo $book_data['title'] ?>"></td>
                        </tr>
                        <tr>
                            <th class="text-nowrap">Author</th>
                            <td><input type="text" name="author" class="add-input" value="<?php echo $book_data['author'] ?>"></td>
                        </tr>
                        <tr>
                        <tr>
                            <th class="text-nowrap">Publisher</th>
                            <td><input type="text" name="publisher" class="add-input" value="<?php echo $book_data['publisher'] ?>"></td>
                        </tr>
                        <tr>
                            <th class="text-nowrap">Category</th>
                            <td><input type="text" class="add-input" value="<?php echo $book_data['type'] ?>" disabled></td>
                        </tr>
                        <tr>
                            <th class="text-nowrap">Total Copies</th>
                            <td><input type="text" name="total_copies" inputmode="numeric" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="add-input" value="<?php echo $book_data['total_copies'] ?>"></td>
                        </tr>
                        <tr>
                            <th class="text-nowrap">Added Date</th>
                            <td><input type="text" class="add-input" value="<?php echo $book_data['added_date'] ?>" disabled></td>
                        </tr>
                    </table>
                    <input type="submit" value="Save Changes" name="save-changes" class="btn btn-success">
                </form>
            </div>
        </div>
    </div>
</body>
</html>