<?php 
    session_start();
    session_destroy();
    
    echo "<script>alert('Logout Success')</script>";
    echo "<script>window.open('../index.php', '_self')</script>";
?>