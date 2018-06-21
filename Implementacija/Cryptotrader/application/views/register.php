<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/cryptotrader/css/style.css">
    </head>
    <body class="register-body">
        <div class="container-fluid">
            
            <?php include('header-guest.php'); ?>
            
            <div class="main-content boxed-content">
                
                <?php include('flashdata.php'); ?>
                
                <div class="col-md-12 white-background register-form-container">
                    <form class="register-form" method="post" action="<?php echo base_url(); ?>guest/registering">
                        <div class="container">
                            <h1>Sign Up</h1>
                            <p>Please fill in this form to create an account.</p>
                            <hr>
                            <label for="email"><b>Email</b></label>
                            <input class="register-input" type="text" placeholder="Enter Email" name="email" required>
                            
                            <label for="name"><b>First Name</b></label>
                            <input class="register-input" type="text" placeholder="Enter Name" name="name" required>
                            
                            <label for="lastname"><b>Last Name</b></label>
                            <input class="register-input" type="text" placeholder="Enter Lastname" name="surname" required>

                            <label for="psw"><b>Password</b></label>
                            <input class="register-input" type="password" placeholder="Enter Password" name="password" required>

                            <label for="psw-repeat"><b>Repeat Password</b></label>
                            <input class="register-input" type="password" placeholder="Repeat Password" name="password-repeat" required>

                            <div class="clearfix">
                                <button class="signup-btn" type="submit" class="signupbtn">Sign Up</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <?php include('footer.php'); ?>
            
        </div>
        <script src="/cryptotrader/js/bootstrap.min.js"></script>
        <script src="/cryptotrader/js/Chart.min.js"></script>
        <script src="/cryptotrader/js/custom.js"></script>
        <script>
            closeLogin();    
        </script>
    </body>
</html>