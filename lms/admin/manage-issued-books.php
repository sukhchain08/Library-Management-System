<?php
    include '../include/admin-privacy.php';
    include '../include/connection.php';

    $admin_number = $_SESSION['number'];
    $data_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE number = '$admin_number'");
    $result_admin = mysqli_fetch_assoc($data_admin);

    $search_value = "";
    if (isset($_GET['search-value'])) {
        $search_value = mysqli_real_escape_string($conn, $_GET['search-value']);
    }

    // Pagination variables
    $limit = 10; // Display 10 issued books per page
    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $limit;

    // Build the base SQL query for counting total records
    $count_sql = "SELECT COUNT(*) AS total_issued_books FROM issued_books WHERE 1=1";
    $where_clause = "";

    if (!empty($search_value)) {
        $where_clause .= " AND (user_name LIKE '%" . $search_value . "%' OR book_title LIKE '%" . $search_value . "%')";
    }

    $count_sql .= $where_clause;
    $count_result = mysqli_query($conn, $count_sql);
    $total_issued_books_row = mysqli_fetch_assoc($count_result);
    $total_issued_books = $total_issued_books_row['total_issued_books'];
    $total_pages = ceil($total_issued_books / $limit);

    // Build the SQL query for fetching issued books with pagination
    $sql = "SELECT * FROM issued_books WHERE 1=1";
    $sql .= $where_clause;
    $sql .= " ORDER BY issue_date DESC LIMIT $limit OFFSET $offset"; // Order by issue_date for consistency

    $books_data_query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/admin-dashboard-style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Issued Books</title>
    <style>
        .pagination-controls {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .pagination-controls a {
            padding: 8px 15px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #007bff;
            border-radius: 5px;
        }
        .pagination-controls a.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .pagination-controls a:hover:not(.active) {
            background-color: #f1f1f1;
        }
    </style>
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
                    <p>Admin</p> / <a href="dashboard.php">Dashboard</a> / <a href="manage-issued-books.php">Manage Issued Books</a>
                </pre>
            </div>
            <div class="user-table">
                <div class="search-div my-4">
                    <form action="" method="GET">
                        <input type="text" name="search-value" autocomplete="off" class="search-input" placeholder="Search User or Book" value="<?php echo htmlspecialchars($search_value); ?>">
                        <button class="btn btn-primary">Search</button>
                    </form>
                </div>
                <table class="table table-bordered table-hover table-responsive text-center mt-4">
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Book Name</th>
                        <th>Issued Date</th>
                        <th>Return Date</th>
                        <th>Action</th>
                    </tr>
                    <?php
                        if(mysqli_num_rows($books_data_query) > 0)
                        {
                            $counter = $offset + 1;
                            while($books_data = mysqli_fetch_assoc($books_data_query))
                            {
                                echo
                                "
                                    <tr>
                                        <td>".$counter++."</td>
                                        <td>".htmlspecialchars($books_data['user_name'])."</td>
                                        <td>".htmlspecialchars($books_data['book_title'])."</td>
                                        <td class='text-nowrap'>".htmlspecialchars($books_data['issue_date'])."</td>
                                        <td class='text-nowrap'>". (empty($books_data['return_date']) || $books_data['return_date'] == '0000-00-00 00:00:00' ? "Not Returned Yet" : htmlspecialchars($books_data['return_date'])) ."</td>
                                        <td>";
                                            if ($books_data['return_status'] == 'return')
                                            {
                                                echo "Book Returned";
                                            }
                                            else
                                            {
                                                echo
                                                "
                                                    <a href='update-issued-book-detail.php?usernumber=".htmlspecialchars($books_data['user_number'])."&bookid=".htmlspecialchars($books_data['book_id'])."' class='btn btn-primary'>Edit</a>
                                                ";
                                            }
                                            echo "
                                        </td>
                                    </tr>
                                ";
                            }
                        }
                        else
                        {
                            echo "<tr><td colspan='6'>No Record Found</td></tr>";
                        }
                    ?>
                </table>

                <div class="pagination-controls">
                    <?php
                    $pagination_base_url = "manage-issued-books.php?";
                    if (!empty($search_value)) {
                        $pagination_base_url .= "search-value=" . urlencode($search_value) . "&";
                    }

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