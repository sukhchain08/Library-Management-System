<?php 
    include '../include/connection.php';

    $sr_no = $_GET['sr_no'];

    $delete_query = mysqli_query($conn, "DELETE FROM category WHERE sr_no = '$sr_no'");
    if($delete_query) 
    {
        echo "<script>alert('Category Deleted')</script>";
        echo "<script>window.open('category.php', '_self')</script>";
    } 
    else 
    {
        echo "<script>alert('Failed to delete category from database')</script>";
        echo "<script>window.open('category.php', '_self')</script>";
    }
?>