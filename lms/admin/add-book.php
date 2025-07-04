<?php
    include '../include/admin-privacy.php';
    include '../include/connection.php';

    $admin_number = $_SESSION['number'];
    $data_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE number = '$admin_number'");
    $result_admin = mysqli_fetch_assoc($data_admin);

    if(isset($_POST['add-book']))
    {
        $book_name = mysqli_real_escape_string($conn, $_POST['book_name']);
        $book_author = mysqli_real_escape_string($conn, $_POST['book_author']);
        $publisher_name = mysqli_real_escape_string($conn, $_POST['publisher_name']);
        $book_category = mysqli_real_escape_string($conn, $_POST['book_category']);
        $book_language = mysqli_real_escape_string($conn, $_POST['book_language']);
        $total_copies = mysqli_real_escape_string($conn, $_POST['total_copies']);

        date_default_timezone_set('Asia/Kolkata');
        $date = date('d-m-y H:i');

        // Image Upload Validation
        $filename = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $file_type = $_FILES['image']['type']; // Get the MIME type
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Get the file extension

        $allowed_extensions = array('jpg', 'jpeg', 'png');
        $allowed_mime_types = array('image/jpeg', 'image/png');

        if (in_array($file_ext, $allowed_extensions) && in_array($file_type, $allowed_mime_types)) {
            $folder = "images/".$filename;
            if (move_uploaded_file($tempname, $folder)) {
                $query = mysqli_query($conn, "INSERT INTO `books`(`title`, `author`, `publisher`, `image`, `type`, `language`, `total_copies`, `available_copies`, `added_date`) VALUES ('$book_name','$book_author','$publisher_name','$folder','$book_category','$book_language','$total_copies','$total_copies','$date')");
                if($query)
                {
                    echo "<script>alert('Book Added')</script>";
                    echo "<script>window.open('add-book.php', '_self')</script>";
                }
                else
                {
                    echo "<script>alert('Failed to Add new Book: Database error " . addslashes(mysqli_error($conn)) . "')</script>";
                    echo "<script>window.open('add-book.php', '_self')</script>";
                }
            } else {
                echo "<script>alert('Failed to upload image.')</script>";
                echo "<script>window.open('add-book.php', '_self')</script>";
            }
        } else {
            echo "<script>alert('Invalid image type. Only JPG, JPEG, and PNG images are allowed.')</script>";
            echo "<script>window.open('add-book.php', '_self')</script>";
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
    <title>Add New Book</title>
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
                    <p>Admin</p> / <a href="dashboard.php">Dashboard</a> / <a href="add-book.php">Add New Book</a>
                </pre>
            </div>
            <div class="book-table">
                <h3 class="text-center mt-3 mb-4 fw-semibold">Add New Book</h3>
                <form method="POST" enctype="multipart/form-data">
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <th width="30%" class="text-nowrap">Book Name</th>
                            <td><input type="text" name="book_name" class="add-input" placeholder="Book Name" required></td>
                        </tr>
                        <tr>
                            <th class="text-nowrap">Author Name</th>
                            <td><input type="text" name="book_author" class="add-input" placeholder="Author Name" required></td>
                        </tr>
                        <tr>
                            <th class="text-nowrap">Category</th>
                            <td>
                                <select name="book_category" class="add-input" required>
                                    <option value="">Select Category</option>
                                    <?php
                                        $category_query = mysqli_query($conn, "SELECT * FROM category");
                                        $category_row = mysqli_num_rows($category_query);
                                        if($category_row != 0)
                                        {
                                            while($category = mysqli_fetch_assoc($category_query))
                                            {
                                                echo
                                                "
                                                    <option>".$category['type']."</option>
                                                ";
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-nowrap">Book Image</th>
                            <td><input type="file" class="add-input" name="image" accept=".jpg, .jpeg, .png" required></td>
                        </tr>
                        <tr>
                            <th class="text-nowrap">Publisher Name</th>
                            <td><input type="text" name="publisher_name" class="add-input" placeholder="Publisher Name" required></td>
                        </tr>
                        <tr>
                            <th class="text-nowrap">Book Language</th>
                            <td><input type="text" name="book_language" class="add-input" placeholder="Book Language" required></td>
                        </tr>
                        <tr>
                            <th class="text-nowrap">Total Copies</th>
                            <td><input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' inputmode="numeric" name="total_copies" class="add-input" placeholder="Total Copies" required></td>
                        </tr>
                    </table>
                    <input type="submit" value="Add Book" name="add-book" class="btn btn-primary">
                </form>
            </div>
        </div>
    </section>
</body>
</html>