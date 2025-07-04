<?php
    // get_book_titles.php

    include '../include/connection.php';

    header('Content-Type: application/json');

    $category_id = $_GET['category_id'] ?? '';

    $books = [];

    if (!empty($category_id)) {
        $category_id = mysqli_real_escape_string($conn, $category_id);

        // ADDED 'image_path' to the SELECT statement
        $book_query = mysqli_query($conn, "SELECT * FROM books WHERE type = '$category_id'");

        if ($book_query) {
            while ($book = mysqli_fetch_assoc($book_query)) {
                $books[] = [
                    'sr_no' => $book['sr_no'],
                    'type' => $book['type'],
                    'title' => $book['title'],
                    'author' => $book['author'],
                    'image' => $book['image'],
                    'available_copies' => $book['available_copies']
                ];
            }
        } else {
            error_log("Error fetching books: " . mysqli_error($conn));
        }
    }

    echo json_encode($books);

    mysqli_close($conn);
?>