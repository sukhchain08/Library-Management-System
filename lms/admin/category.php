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
    $limit = 10; 
    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $limit;

    // Build the base SQL query for counting total records
    $count_sql = "SELECT COUNT(*) AS total_categories FROM category WHERE 1=1";
    $where_clause = "";

    if (!empty($search_value)) {
        $where_clause .= " AND type LIKE '%" . $search_value . "%'";
    }

    $count_sql .= $where_clause;
    $count_result = mysqli_query($conn, $count_sql);
    $total_categories_row = mysqli_fetch_assoc($count_result);
    $total_categories = $total_categories_row['total_categories'];
    $total_pages = ceil($total_categories / $limit);

    // Build the SQL query for fetching categories with pagination
    $sql = "SELECT * FROM category WHERE 1=1";
    $sql .= $where_clause;
    $sql .= " LIMIT $limit OFFSET $offset";

    $categories_query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/admin-dashboard-style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
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
                    <p>Admin</p> / <a href="dashboard.php">Dashboard</a> / <a href="category.php">Total Categories</a>
                </pre>
            </div>
            <div class="user-table">
                <div class="search-div my-4">
                    <form action="" method="GET">
                        <input type="text" name="search-value" autocomplete="off" class="search-input" placeholder="Search Category" value="<?php echo htmlspecialchars($search_value); ?>">
                        <button class="btn btn-primary">Search</button>
                    </form>
                </div>
                <table class="table table-bordered table-hover table-responsive text-center">
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Creation Date</th>
                        <th>Updation Date</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                        if(mysqli_num_rows($categories_query) > 0)
                        {
                            $counter = $offset + 1;
                            while($result = mysqli_fetch_assoc($categories_query))
                            {
                                echo
                                "
                                    <tr>
                                        <td>".$counter++."</td>
                                        <td>".htmlspecialchars($result['type'])."</td>
                                        <td class='text-nowrap'>".htmlspecialchars($result['creation_date'])."</td>
                                        <td class='text-nowrap'>";
                                            // Corrected logic for updation_date display
                                            if(empty($result['updation_date']) || $result['updation_date'] == '0000-00-00 00:00:00')
                                            {
                                                echo "-";
                                            }
                                            else
                                            {
                                                echo htmlspecialchars($result['updation_date']);
                                            }
                                            echo "
                                        </td>
                                        <td class='text-nowrap'>
                                            <a href='edit-category.php?sr_no=".htmlspecialchars($result['sr_no'])."' class='btn btn-primary me-2'>Edit</a>
                                            <a href='delete-category.php?sr_no=".htmlspecialchars($result['sr_no'])."' onclick=\"return confirm('Are you sure to delete this category?');\" class='btn btn-danger'>Delete</a>
                                        </td>
                                    </tr>
                                ";
                            }
                        }
                        else
                        {
                            echo "<tr><td colspan='5'>No Record Found</td></tr>"; // Changed colspan to 5
                        }
                    ?>
                </table>

                <div class="pagination-controls">
                    <?php
                    $pagination_base_url = "category.php?";
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