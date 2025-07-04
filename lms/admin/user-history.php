<?php
    include '../include/admin-privacy.php';
    include '../include/connection.php';

    $admin_number = $_SESSION['number'];
    $data_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE number = '$admin_number'");
    $result_admin = mysqli_fetch_assoc($data_admin);

    // Get and sanitize user_number from GET request
    $user_number = isset($_GET['user_number']) ? mysqli_real_escape_string($conn, $_GET['user_number']) : '';

    // Fetch user_name for the heading
    $user_data_query = mysqli_query($conn, "SELECT user_name FROM issued_books WHERE user_number = '$user_number' LIMIT 1");
    $user_data = mysqli_fetch_assoc($user_data_query);

    $search_value = "";
    if (isset($_GET['search-value'])) {
        $search_value = mysqli_real_escape_string($conn, $_GET['search-value']);
    }

    // Pagination variables
    $limit = 10; // Maximum 10 entries per page
    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $limit;

    // Build the base SQL query for counting total records for the specific user, with search
    $count_sql = "SELECT COUNT(*) AS total_history_records FROM issued_books WHERE user_number = '$user_number'";
    $where_clause = "";

    if (!empty($search_value)) {
        $where_clause .= " AND book_title LIKE '%" . $search_value . "%'";
    }
    $count_sql .= $where_clause;

    $count_result = mysqli_query($conn, $count_sql);
    $total_history_row = mysqli_fetch_assoc($count_result);
    $total_history_records = $total_history_row['total_history_records'];
    $total_pages = ceil($total_history_records / $limit);

    // Build the SQL query for fetching user history with pagination and search
    $sql = "SELECT * FROM issued_books WHERE user_number = '$user_number'";
    $sql .= $where_clause;
    $sql .= " ORDER BY issue_date DESC LIMIT $limit OFFSET $offset"; // Order by issue_date for consistency

    $user_history_query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/admin-dashboard-style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User History</title>
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
                    <p>Admin</p> / <a href="dashboard.php">Dashboard</a> / <a href="total-user.php">Total Users</a> / <a href="user-history.php?user_number=<?php echo htmlspecialchars($user_number); ?>">User History</a>
                </pre>
            </div>
            <div class="search-div mb-4">
                <form action="" method="GET">
                    <input type="hidden" name="user_number" value="<?php echo htmlspecialchars($user_number); ?>">
                    <input type="text" name="search-value" autocomplete="off" class="search-input" placeholder="Search Book Title" value="<?php echo htmlspecialchars($search_value); ?>">
                    <button class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="user-table">
                <h4 class="mt-5 fw-semibold">
                    <?php
                        if($user_data && !empty($user_data['user_name']))
                        {
                            echo "Book issued history of " . htmlspecialchars($user_data['user_name']);
                        }
                        else
                        {
                            echo "No Book Issue History"; // Or "User not found" if user_number is invalid
                        }
                    ?>
                </h4> <hr>
                <table class="table table-bordered table-hover table-responsive text-center mt-4">
                    <tr>
                        <th>#</th>
                        <th>Issued Books</th>
                        <th>Issued Date</th>
                        <th>Return Date</th>
                        <th>Fine (if any)</th>
                    </tr>
                    <?php
                        if(mysqli_num_rows($user_history_query) > 0)
                        {
                            $counter = $offset + 1; // Start counter from the correct number for the current page
                            while($user_history = mysqli_fetch_assoc($user_history_query))
                            {
                                echo
                                "
                                    <tr>
                                        <td>".$counter++."</td>
                                        <td>".htmlspecialchars($user_history['book_title'])."</td>
                                        <td class='text-nowrap'>".htmlspecialchars($user_history['issue_date'])."</td>
                                        <td class='text-nowrap'>";
                                            // More robust check for return_date
                                            if(empty($user_history['return_date']) || $user_history['return_date'] == '0000-00-00 00:00:00')
                                            {
                                                echo "Not Returned Yet";
                                            }
                                            else
                                            {
                                                echo htmlspecialchars($user_history['return_date']);
                                            }
                                            echo "
                                        </td>
                                        <td class='text-nowrap'>";
                                            // More robust check for fine and return status
                                            if (empty($user_history['return_status']) || $user_history['return_status'] !== 'return')
                                            {
                                                echo "Not Returned Yet";
                                            }
                                            elseif ($user_history['return_status'] == 'return' && (empty($user_history['fine']) || $user_history['fine'] == 0)) {
                                                echo "₹0";
                                            }
                                            else
                                            {
                                                echo "₹". htmlspecialchars($user_history['fine']);
                                            }
                                            echo "
                                        </td>
                                    </tr>
                                ";
                            }
                        }
                        else
                        {
                            echo "<tr><td colspan='5'>No Record Found</td></tr>"; // Corrected colspan to 5
                        }
                    ?>
                </table>

                <div class="pagination-controls">
                    <?php
                    $pagination_base_url = "user-history.php?user_number=" . urlencode($user_number);
                    if (!empty($search_value)) {
                        $pagination_base_url .= "&search-value=" . urlencode($search_value);
                    }
                    $pagination_base_url .= "&"; // Add a trailing & for page parameter

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