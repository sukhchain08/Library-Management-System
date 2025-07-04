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

    // Process account activation/block requests
    if(isset($_POST['user_number_to_update']) && (isset($_POST['active-account']) || isset($_POST['block-account'])))
    {
        $user_number_to_update = mysqli_real_escape_string($conn, $_POST['user_number_to_update']);
        $new_status = "";

        if (isset($_POST['active-account']))
        {
            $new_status = "Active";
        }
        elseif (isset($_POST['block-account']))
        {
            $new_status = "Blocked";
        }

        $update_query = mysqli_query($conn, "UPDATE `user_data` SET `status`='$new_status' WHERE `number` = '$user_number_to_update'");

        if($update_query)
        {
            // Redirect to the same page to show updated status and maintain pagination/search
            echo "<script>window.location.href = 'total-user.php?page=" . ($_GET['page'] ?? 1) . "&search-value=" . urlencode($search_value) . "';</script>";
            exit(); // Ensure no further code is executed after redirect
        }
        else
        {
            echo "<script>alert('Status change failed');</script>";
            echo "<script>window.location.href = 'total-user.php?page=" . ($_GET['page'] ?? 1) . "&search-value=" . urlencode($search_value) . "';</script>";
            exit();
        }
    }

    // Pagination variables
    $limit = 10; 
    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $limit;

    // Build the base SQL query for counting total records
    $count_sql = "SELECT COUNT(*) AS total_users FROM user_data WHERE 1=1";
    $where_clause = "";

    if (!empty($search_value)) {
        $where_clause .= " AND (name LIKE '%" . $search_value . "%' OR number LIKE '%" . $search_value . "%' OR email LIKE '%" . $search_value . "%')";
    }

    $count_sql .= $where_clause;
    $count_result = mysqli_query($conn, $count_sql);
    $total_users_row = mysqli_fetch_assoc($count_result);
    $total_users = $total_users_row['total_users'];
    $total_pages = ceil($total_users / $limit);

    // Build the SQL query for fetching users with pagination
    $sql = "SELECT * FROM user_data WHERE 1=1";
    $sql .= $where_clause;
    $sql .= " LIMIT $limit OFFSET $offset";

    $users_data = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/admin-dashboard-style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Users</title>
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
                    <p>Admin</p> / <a href="dashboard.php">Dashboard</a> / <a href="total-user.php">Total Users</a>
                </pre>
            </div>
            <div class="user-table">
                <div class="search-div my-4">
                    <form action="" method="GET">
                        <input type="text" name="search-value" autocomplete="off" class="search-input" placeholder="Search User" value="<?php echo htmlspecialchars($search_value); ?>">
                        <button class="btn btn-primary">Search</button>
                    </form>
                </div>
                <table class="table table-bordered table-hover table-responsive text-center">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Number</th>
                        <th>Email</th>
                        <th>Joined Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php
                        if(mysqli_num_rows($users_data) > 0) { // Changed $data to $users_data
                            $counter = $offset + 1; // Adjust counter for current page
                            while($result_user = mysqli_fetch_assoc($users_data)) // Changed $data to $users_data
                            {
                                echo "
                                    <tr>
                                        <td>".$counter++."</td>
                                        <td>".htmlspecialchars($result_user['name'])."</td>
                                        <td>".htmlspecialchars($result_user['number'])."</td>
                                        <td>".htmlspecialchars($result_user['email'])."</td>
                                        <td class='text-nowrap'>".htmlspecialchars($result_user['reg-date'])."</td>
                                        <td>".htmlspecialchars($result_user['status'])."</td>
                                        <td class='text-nowrap'>";
                                            if ($result_user['status'] == 'Active')
                                            {
                                                echo
                                                "
                                                    <form method='POST' style='display:inline-block; margin-right: 5px;'>
                                                        <input type='hidden' name='user_number_to_update' value='".htmlspecialchars($result_user['number'])."'>
                                                        <input type='submit' name='block-account' class='btn btn-danger' value='Blocked'>
                                                    </form>
                                                ";
                                            }
                                            elseif ($result_user['status'] == 'Blocked')
                                            {
                                                echo
                                                "
                                                    <form method='POST' style='display:inline-block; margin-right: 5px;'>
                                                        <input type='hidden' name='user_number_to_update' value='".htmlspecialchars($result_user['number'])."'>
                                                        <input type='submit' name='active-account' class='btn btn-success' value='Active'>
                                                    </form>
                                                ";
                                            }

                                            echo "
                                            <a href='user-history.php?user_number=".htmlspecialchars($result_user['number'])."' class='btn btn-primary'>Detail</a>
                                        </td>
                                    </tr>
                                ";
                            }
                        }
                        else
                        {
                            echo "<tr><td colspan='7'>No Record Found</td></tr>";
                        }
                    ?>
                </table>

                <div class="pagination-controls">
                    <?php
                    $pagination_base_url = "total-user.php?";
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