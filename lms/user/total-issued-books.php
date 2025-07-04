<?php
    include '../include/user-privacy.php';
    include '../include/connection.php';

    $user_number = $_SESSION['number'];
    $data = mysqli_query($conn, "SELECT * FROM `user_data` WHERE number = '$user_number'");
    $result = mysqli_fetch_assoc($data);

    // Pagination variables
    $limit = 10; // Maximum 10 entries per page
    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $limit;

    // Build the base SQL query for counting total records for the current user
    $count_sql = "SELECT COUNT(*) AS total_issued_books FROM issued_books WHERE user_number = '$user_number'";
    $count_result = mysqli_query($conn, $count_sql);
    $total_issued_books_row = mysqli_fetch_assoc($count_result);
    $total_issued_books = $total_issued_books_row['total_issued_books'];
    $total_pages = ceil($total_issued_books / $limit);

    // Build the SQL query for fetching issued books with pagination
    $issued_book_query = mysqli_query($conn, "SELECT * FROM issued_books WHERE user_number = '$user_number' ORDER BY issue_date DESC LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/user-dashboard-style.css">
    <link rel="stylesheet" href="../styles/admin-dashboard-style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Issued Books</title>
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
                    <p>User</p> / <a href="dashboard.php">Dashboard</a> / <a href="total-issued-books.php">Issued Books</a>
                </pre>
            </div>
            <div class="book-table">
                <table class="table table-bordered table-responsive text-center">
                    <tr>
                        <th>#</th>
                        <th>Book Name</th>
                        <th>Publisher Name</th>
                        <th>Issued Date</th>
                        <th>Return Date</th>
                        <th>Fine</th>
                    </tr>
                    <?php
                        // Check if there are any issued books for the current page
                        if(mysqli_num_rows($issued_book_query) > 0)
                        {
                            $counter = $offset + 1; // Start counter from the correct number for the current page
                            while($issued_book = mysqli_fetch_assoc($issued_book_query))
                            {
                                // Sanitize book_id before using in query to prevent SQL injection
                                $book_id_from_issued = mysqli_real_escape_string($conn, $issued_book['book_id']);

                                // Fetch publisher name based on book_id from the 'books' table
                                // Ensure 'book_id' in 'issued_books' corresponds to 'sr_no' in 'books'
                                $book_publisher_query = mysqli_query($conn, "SELECT publisher FROM books WHERE sr_no = '{$book_id_from_issued}'");
                                $book_publisher = mysqli_fetch_assoc($book_publisher_query);

                                // Safely get publisher name, default to 'N/A' if not found
                                $publisher_name = $book_publisher ? htmlspecialchars($book_publisher['publisher']) : 'N/A';

                                echo
                                "
                                    <tr>
                                        <td>".$counter++."</td>
                                        <td>".htmlspecialchars($issued_book['book_title'])."</td>
                                        <td>".$publisher_name."</td>
                                        <td class='text-nowrap'>".htmlspecialchars($issued_book['issue_date'])."</td>
                                        <td class='text-nowrap'>";
                                            // More robust check for return_date
                                            if(empty($issued_book['return_date']) || $issued_book['return_date'] == '0000-00-00 00:00:00')
                                            {
                                                echo "<p style='color: red; font-weight: 600;'>Not Returned Yet</p>";
                                            }
                                            else
                                            {
                                                echo htmlspecialchars($issued_book['return_date']);
                                            }
                                            echo "
                                        </td>
                                        <td class='text-nowrap'>";
                                            // More robust check for fine and return status
                                            if (empty($issued_book['return_status']) || $issued_book['return_status'] !== 'return')
                                            {
                                                echo "<p style='color: red; font-weight: 600;'>Not Returned Yet</p>";
                                            }
                                            elseif ($issued_book['return_status'] == 'return' && (empty($issued_book['fine']) || $issued_book['fine'] == 0)) {
                                                echo "₹0";
                                            }
                                            else
                                            {
                                                echo "₹". htmlspecialchars($issued_book['fine']);
                                            }
                                            echo "
                                        </td>
                                    </tr>
                                ";
                            }
                        }
                        else
                        {
                            echo "<tr><td colspan='6'>No Issued Books Found</td></tr>"; // Changed colspan to 6
                        }
                    ?>
                </table>

                <div class="pagination-controls">
                    <?php
                    $pagination_base_url = "total-issued-books.php?";

                    // Previous page link
                    if ($current_page > 1) {
                        echo '<a href="' . $pagination_base_url . 'page=' . ($current_page - 1) . '">Previous</a>';
                    }

                    // Page numbers
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active_class = ($i == $current_page) ? 'active' : '';
                        echo '<a class="' . $active_class . '" href="' . $pagination_base_url . 'page=' . $i . '">' . $i . '</a>';
                    }

                    // Next page link
                    if ($current_page < $total_pages) {
                        echo '<a href="' . $pagination_base_url . 'page=' . ($current_page + 1) . '">Next</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>