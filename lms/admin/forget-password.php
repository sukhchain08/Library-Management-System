<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/links.php'; ?>
    <link rel="stylesheet" href="../styles/admin-dashboard-style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
</head>
<body>
    <div class="user-table">
        <h3 class="text-center mt-5 mb-4 fw-semibold">Forget Password</h3>
        <div class="row form">
            <form method="POST">
                <div class="col-12 col-lg-4 text-center container-fluid">
                    <div class="issue-new-book-div border p-2 text-start">
                        <span>
                            <p>Enter Number</p>
                            <input type="text" name="number" autocomplete="off" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="10" placeholder="Enter Number" required>
                        </span>
                        <span>
                            <p>Enter Email Address</p>
                            <input type="email" name="email" autocomplete="off" placeholder="Enter Email Address" required>
                        </span>
                        <span>
                            <p>Enter DOB</p>
                            <input type="email" name="dob" placeholder="Date of Birth" onfocus="(this.type='date')" onblur="if(!this.value) this.type='text'" autocomplete="off" required>
                        </span>
                        <input type="submit" value="Verify Details" name="get-password" class="btn btn-primary mt-2">   
                        <div class="mt-3 mb-2">
                            <a href="login.php" style="color: #1264a3; font-weight: 600; letter-spacing: .6px;" class="mb-3">Click here for login</a>
                        </div>
                        <?php   
                            include '../include/connection.php';
                            error_reporting(0);
                            if(isset($_POST['get-password']))
                            {
                                $number = $_POST['number'];
                                $email = $_POST['email'];
                                $dob = $_POST['dob'];

                                $dob_formatted_for_db = date('d-m-Y', strtotime($dob));

                                $query = mysqli_query($conn, "SELECT * FROM `admin` WHERE number = '$number'");
                                $result = mysqli_fetch_assoc($query);

                                // it execute when number is correct 
                                if($result['number'] == $number)
                                {
                                    // it execute when email address is correct 
                                    if($result['email'] == $email)
                                    {
                                        // it execute when dob is correct 
                                        if($result['dob'] == $dob_formatted_for_db)
                                        {
                                            echo "Password is: ". "<b>" .$result['password']. "</b>";
                                            exit();
                                        }
                                        else
                                        {
                                            echo "Invalid DOB";
                                        }
                                    }
                                    else
                                    {
                                        echo "Invalid Email Adrress";
                                        exit();
                                    }
                                }
                                else
                                {
                                    echo "Invalid Phone Number";
                                    exit();
                                }
                            }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>