<?php
    include '../include/user-privacy.php';
    include '../include/connection.php';

    $user_number = $_SESSION['number'];
    $data = mysqli_query($conn, "SELECT * FROM `user_data` WHERE number = '$user_number'");
    $result = mysqli_fetch_assoc($data);

    $selected_book_type = $_GET['book_type'] ?? '';
    $search_value = $_GET['search-value'] ?? '';

    // Pagination variables
    $limit = 5; // Maximum 5 books per page
    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $limit;

    // Build the base SQL query for counting total records
    $count_sql = "SELECT COUNT(*) AS total_books FROM books WHERE 1=1";
    $where_clause = "";

    if (!empty($selected_book_type)) {
        $where_clause .= " AND type = '" . mysqli_real_escape_string($conn, $selected_book_type) . "'";
    }

    if (!empty($search_value)) {
        $where_clause .= " AND (title LIKE '%" . mysqli_real_escape_string($conn, $search_value) . "%' OR author LIKE '%" . mysqli_real_escape_string($conn, $search_value) . "%')";
    }

    $count_sql .= $where_clause;
    $count_result = mysqli_query($conn, $count_sql);
    $total_books_row = mysqli_fetch_assoc($count_result);
    $total_books = $total_books_row['total_books'];
    $total_pages = ceil($total_books / $limit);

    // Build the SQL query for fetching books with pagination
    $sql = "SELECT * FROM books WHERE 1=1";
    $sql .= $where_clause;
    $sql .= " LIMIT $limit OFFSET $offset";

    $books_data = mysqli_query($conn, $sql);

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
    <title>All Books</title>
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
                    <p>User</p> / <a href="dashboard.php">Dashboard</a> / <a href="all-books.php">All Books</a>
                </pre>
            </div>
            <div class="book-table">
                <div class="selection mb-4">
                    <div class="select">
                        <p style="position: relative; top: 10px;">Select Type</p>
                        <form action="" method="GET">
                            <select name="book_type" class="edit-input ms-0" onchange="this.form.submit()">
                                <option value="">Select Type</option>
                                <?php
                                    $type_query = mysqli_query($conn, "SELECT * FROM category");
                                    if(mysqli_num_rows($type_query) > 0) {
                                        while($type_result = mysqli_fetch_assoc($type_query)) {
                                            $selected = ($type_result['type'] == $selected_book_type) ? 'selected' : '';
                                            echo "<option value='".htmlspecialchars($type_result['type'])."' $selected>".htmlspecialchars($type_result['type'])."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </form>
                    </div>
                </div>

                <?php
                // Show search div ONLY if a book type has been selected
                if (!empty($selected_book_type))
                {
                ?>
                <div class="search-div my-4">
                    <form action="" method="GET">
                        <input type="hidden" name="book_type" value="<?php echo htmlspecialchars($selected_book_type); ?>">
                        <input type="text" name="search-value" autocomplete="off" class="search-input" placeholder="Search Book" value="<?php echo htmlspecialchars($search_value); ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <?php
                }
                ?>

                <table class="table table-bordered table-responsive text-center">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Language</th>
                    </tr>
                    <?php
                        if(mysqli_num_rows($books_data) > 0) {
                            $counter = $offset + 1; // Adjust counter for current page
                            while($book_result = mysqli_fetch_assoc($books_data)) {
                                $imagePath = '../admin/'.$book_result['image'];
                                echo "
                                    <tr>
                                        <td>".$counter++."</td>
                                        <td>".htmlspecialchars($book_result['title'])."</td>
                                        <td><img src='".$imagePath."' width='100px' height='150px'></td>
                                        <td>".htmlspecialchars($book_result['author'])."</td>
                                        <td>".htmlspecialchars($book_result['type'])."</td>
                                        <td class='text-nowrap'>".htmlspecialchars($book_result['language'])."</td>
                                    </tr>
                                ";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No Record Found</td></tr>";
                        }
                    ?>
                </table>

                <div class="pagination-controls">
                    <?php
                    $pagination_base_url = "all-books.php?";
                    if (!empty($selected_book_type)) {
                        $pagination_base_url .= "book_type=" . urlencode($selected_book_type) . "&";
                    }
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
