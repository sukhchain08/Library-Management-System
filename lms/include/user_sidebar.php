<div class="row pb-3">
    <div class="col-12 col-md-6">
        <p class="user-name fw-semibold">Welcome, <?php echo $result['name']; ?></p>
    </div>
    <div class="col-12 d-none d-md-inline col-md-6 text-md-end lg-logout-div">
        <a class="btn btn-danger text-white" onclick="return confirm('Are you sure to Log out?')" href="logout.php">Logout</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 d-none d-lg-inline side-bar min-vh-100 border-end">
        <h4 class="fs-4 fw-semibold text-primary">User Dashboard</h4>
        <div class="dashboard-links mt-3 border-top pt-3">
            <div class="link">
                <i class="fa-solid fa-house"></i> <a href="dashboard.php">Dashboard</a>
            </div>
            <div class="link">  
                <i class="fa-solid fa-address-card"></i> <a href="profile.php">Profile</a>
            </div>
            <div class="link">  
                <i class="fa-solid fa-book"></i> <a href="all-books.php">All Books</a>
            </div>
            <div class="link">  
                <i class="fa-sharp fa-solid fa-book-open-reader"></i><a href="total-issued-books.php">Total Issued Book</a>
            </div>
            <div class="link">  
                <i class="fa-solid fa-lock"></i><a href="change-password.php">Reset Password</a>
            </div>
    </div>
</div>