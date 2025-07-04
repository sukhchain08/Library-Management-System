<?php 
    include '../include/admin-privacy.php';
    include '../include/connection.php';

    $admin_number = $_SESSION['number'];
    $data_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE number = '$admin_number'");
    $result_admin = mysqli_fetch_assoc($data_admin);

    if(isset($_POST['issue-book']))
    {
        
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $usernumber = mysqli_real_escape_string($conn, $_POST['usernumber']);
        $book_id = mysqli_real_escape_string($conn, $_POST['book_id']); // Get the book_id (sr_no) from the hidden input
        
        $check_user_status = mysqli_query($conn, "SELECT status FROM user_data WHERE number = '$usernumber'");
        $user_status = mysqli_fetch_assoc($check_user_status);

        if($user_status['status'] === 'Blocked')
        {
            echo "<script>alert('User Account is Blocked')</script>";
            echo "<script>window.open('issue-book.php', '_self')</script>";
            exit(); // Always exit after a redirect/alert
        }

        else
        {
            if (empty($book_id)) {
                echo "<script>alert('Error: Book ID is missing!')</script>";
                echo "<script>window.open('issue-book.php', '_self')</script>";
                exit();
            }

            date_default_timezone_set('Asia/Kolkata');
            $issue_date = date('d-m-y H:i:s'); // Standard DATETIME format for MySQL

            // ✅ Check if user exists
            $check_user = mysqli_query($conn, "SELECT * FROM user_data WHERE number = '$usernumber'");
            if(!$check_user) {
                die("Error fetching user data: " . mysqli_error($conn));
            }

            if(mysqli_num_rows($check_user) != 0)
            {
                $user = mysqli_fetch_assoc($check_user);
                $new_username = $user['name'];

                // ✅ Get the book title and availability
                $get_book_details_query = mysqli_query($conn, "SELECT title, available_copies FROM `books` WHERE sr_no = '$book_id'");
                if(!$get_book_details_query) {
                    die("Error fetching book details: " . mysqli_error($conn));
                }

                $book_data = mysqli_fetch_assoc($get_book_details_query);
                $book_title = $book_data['title'];
                $current_available_copies = $book_data['available_copies'];

                if ($current_available_copies > 0) 
                {
                    // ✅ Check if this book is already issued to the same user
                    $already_issued_query = mysqli_query($conn, "SELECT * FROM issued_books WHERE user_number='$usernumber' AND book_id='$book_id' AND return_date IS NULL");
                    if(!$already_issued_query) {
                        die("Error checking already issued books: " . mysqli_error($conn));
                    }

                    if(mysqli_num_rows($already_issued_query) > 0) {
                        echo "<script>alert('This book is already issued to this user and not yet returned.')</script>";
                        echo "<script>window.open('issue-book.php', '_self')</script>";
                        exit();
                    }

                    // ✅ Use Prepared Statement
                    $stmt = mysqli_prepare($conn, "INSERT INTO issued_books (book_id, book_title, user_name, user_number, issue_date) VALUES (?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "issss", $book_id, $book_title, $new_username, $usernumber, $issue_date);

                    // --- Start Transaction ---
                    mysqli_autocommit($conn, FALSE);
                    $transaction_success = true;

                    if(mysqli_stmt_execute($stmt)) {
                        // ✅ Decrement available_copies
                        $decrement_copies_query = mysqli_query($conn, "UPDATE `books` SET available_copies = available_copies - 1 WHERE sr_no = '$book_id'");
                        if(!$decrement_copies_query) {
                            $transaction_success = false;
                            error_log("Failed to decrement available copies: " . mysqli_error($conn));
                        }
                    } else {
                        $transaction_success = false;
                        error_log("Failed to insert into issued_books (prepared): " . mysqli_stmt_error($stmt));
                    }

                    // ✅ Commit or Rollback
                    if($transaction_success) {
                        mysqli_commit($conn);
                        mysqli_autocommit($conn, TRUE);
                        echo "<script>alert('Book successfully issued to ".$new_username." and available copies updated!')</script>";
                        echo "<script>window.open('issue-book.php', '_self')</script>";
                    } else {
                        mysqli_rollback($conn);
                        mysqli_autocommit($conn, TRUE);
                        echo "<script>alert('Failed to issue book due to a database error.')</script>";
                        echo "<script>window.open('issue-book.php', '_self')</script>";
                    }

                    mysqli_stmt_close($stmt);
                } 
                else 
                {
                    echo "<script>alert('Book not available (0 copies left) for issuance!')</script>";
                    echo "<script>window.open('issue-book.php', '_self')</script>";
                }
            }
            else
            {
                echo "<script>alert('User not found. Please check user number.')</script>";
                echo "<script>window.open('issue-book.php', '_self')</script>";
            }
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
    <title>Issue Book</title>
    <style>
        /* Optional: Add some basic styling for the availability message */
        .availability-message {
            margin-top: 10px;
            font-weight: bold;
        }
        .availability-message.available {
            color: green;
        }
        .availability-message.not-available {
            color: red;
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
                    <p>Admin</p> / <a href="dashboard.php">Dashboard</a> / <a href="issue-book.php">Issue New Book</a>
                </pre>
            </div>
            <div class="book-table">
                <h3 class="text-center mt-3 mb-4 fw-semibold">Issue New Book</h3>
                <div class="row">
                    <form method="POST">
                        <div class="col-12 col-lg-7 text-center container-fluid">
                            <div class="issue-new-book-div border p-2 text-start">
                                <span>
                                    <p>User Name:</p>
                                    <input type="text" name="username" required>
                                </span>
                                <span>
                                    <p>User Number:</p>
                                    <input type="text" name="usernumber" onkeypress='return event.charCode >= 48 && event.charCode <= 57' inputmode="numeric" maxlength="10" required>
                                </span>
                                <span>
                                    <p>Book Category:</p>
                                    <select name="book_category" id="bookCategorySelect" onchange="fetchBookTitles()">
                                        <option value="">Select Book Category</option>
                                        <?php
                                            // Assuming $conn is your database connection
                                            $category_query = mysqli_query($conn, "SELECT type FROM category");
                                            if(mysqli_num_rows($category_query) > 0) 
                                            {
                                                while($category = mysqli_fetch_assoc($category_query)) 
                                                {
                                                    echo "<option value='".$category['type']."'>".$category['type']."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </span>
                                <span>
                                    <p>Book Title:</p>
                                    <select name="book_title" id="bookTitleSelect" onchange="displayBookImage()" required>
                                        <option value="">Select Book Title</option>
                                    </select>
                                </span>
                                <input type="hidden" name="book_id" id="hiddenBookId">

                                <span>
                                    <p>Book Preview:</p>
                                    <img id="bookImagePreview" src="" alt="Book Cover Preview" style="display: none; height: 250px;">
                                </span>
                                <p id="availableCopiesDisplay" class="availability-message" style="display: none;"></p>
                                
                                <input type="submit" value="Issue Book" name="issue-book" id="issueBookBtn" class="btn btn-primary my-2">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        function fetchBookTitles() {
            var categoryId = document.getElementById("bookCategorySelect").value;
            var bookTitleSelect = document.getElementById("bookTitleSelect");
            var bookImagePreview = document.getElementById("bookImagePreview");
            var hiddenBookId = document.getElementById("hiddenBookId");
            var availableCopiesDisplay = document.getElementById("availableCopiesDisplay");
            var issueBookBtn = document.getElementById("issueBookBtn");

            bookTitleSelect.innerHTML = '<option value="">Select Book Title</option>';
            bookImagePreview.style.display = 'none';
            bookImagePreview.src = '';
            hiddenBookId.value = '';
            availableCopiesDisplay.style.display = 'none';
            availableCopiesDisplay.textContent = '';
            availableCopiesDisplay.classList.remove('available', 'not-available');
            issueBookBtn.disabled = true;

            if (categoryId === "") {
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_book_titles.php?category_id=" + categoryId, true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var books = JSON.parse(xhr.responseText);

                    books.forEach(function(book) {
                        var option = document.createElement("option");
                        option.value = book.sr_no;
                        option.textContent = book.title + (book.author ? ' (' + book.author + ')' : '');
                        
                        if (book.image) {
                            option.setAttribute('data-image', book.image);
                        } else {
                            option.setAttribute('data-image', '');
                        }
                        option.setAttribute('data-available-copies', book.available_copies);
                        bookTitleSelect.appendChild(option);
                    });
                } else if (xhr.readyState === 4 && xhr.status !== 200) {
                    console.error("Error fetching book titles: " + xhr.status);
                }
            };

            xhr.send();
        }

        function displayBookImage() {
            var bookTitleSelect = document.getElementById("bookTitleSelect");
            var bookImagePreview = document.getElementById("bookImagePreview");
            var hiddenBookId = document.getElementById("hiddenBookId");
            var availableCopiesDisplay = document.getElementById("availableCopiesDisplay");
            var issueBookBtn = document.getElementById("issueBookBtn");

            var selectedOption = bookTitleSelect.options[bookTitleSelect.selectedIndex];

            if (selectedOption.value === "") {
                bookImagePreview.src = '';
                bookImagePreview.style.display = 'none';
                hiddenBookId.value = '';
                availableCopiesDisplay.style.display = 'none';
                availableCopiesDisplay.textContent = '';
                availableCopiesDisplay.classList.remove('available', 'not-available');
                issueBookBtn.disabled = true;
                return;
            }

            var imagePath = selectedOption.getAttribute('data-image');
            var availableCopies = selectedOption.getAttribute('data-available-copies');

            hiddenBookId.value = selectedOption.value;

            if (imagePath && imagePath !== '') {
                bookImagePreview.src = imagePath;
                bookImagePreview.style.display = 'block';
            } else {
                bookImagePreview.src = '';
                bookImagePreview.style.display = 'none';
            }

            availableCopiesDisplay.style.display = 'block';
            if (parseInt(availableCopies) > 0) {
                availableCopiesDisplay.textContent = 'Available Copies: ' + availableCopies;
                availableCopiesDisplay.classList.remove('not-available');
                availableCopiesDisplay.classList.add('available');
                issueBookBtn.disabled = false;
            } else {
                availableCopiesDisplay.textContent = 'Book not available';
                availableCopiesDisplay.classList.remove('available');
                availableCopiesDisplay.classList.add('not-available');
                issueBookBtn.disabled = true;
            }
        }
    </script>
</body>
</html>