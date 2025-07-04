<?php 
    include '../include/connection.php';

    $book_id = $_GET['id'];

    // Step 1: Get image path before deleting
    $get_image_query = mysqli_query($conn, "SELECT image FROM `books` WHERE sr_no = '$book_id'");
    $image_data = mysqli_fetch_assoc($get_image_query);

    if ($image_data) 
    {
        $image_path = $image_data['image'];
        // Step 2: Delete the image from the folder if it exists
        if (file_exists($image_path)) 
        {
            unlink($image_path);
        }

        // Step 3: Now delete the book record
        $delete_query = mysqli_query($conn, "DELETE FROM `books` WHERE sr_no = '$book_id'");
        $delete_from_issued_book_query = mysqli_query($conn, "DELETE FROM `issued_books` WHERE book_id = '$book_id'");
        
        if($delete_query && $delete_from_issued_book_query) 
        {
            echo "<script>alert('Book Deleted')</script>";
            echo "<script>window.open('total-books.php', '_self')</script>";
        } 
        else 
        {
            echo "<script>alert('Failed to delete book from database')</script>";
            echo "<script>window.open('total-books.php', '_self')</script>";
        }
    } 
    else 
    {
        echo "<script>alert('Book not found')</script>";
        echo "<script>window.open('total-books.php', '_self')</script>";
    }
?>
