<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Browser tab icon -->
        <link rel="apple-touch-icon" sizes="180x180" href="../images/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon/favicon-16x16.png">
        <link rel="manifest" href="../images/favicon/site.webmanifest">
        <link rel="mask-icon" href="../images/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <!-- CSS files -->
        <link href="styles/main.css" rel="stylesheet" type="text/css">

        <!-- External fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    </head>
    <body>
        <ul class="navigation-menu">
            <li class="login-logout-option selected-navigation-option">
                <a href="login.php">
                    <p>Login</p>
                </a>
            </li>
            <li>
                <a href="home.html">
                    <p>Home</p>
                </a>
            </li>
        </ul>
        <div class="wrapper" name="login-form" style="margin-top: -257px">
            <div class="inner-wrapper"> 
                <img style="text-align: center" src="../images/my-cal-logo.png" alt="MyCal">
                <form action="controller/login-controller.php" method="post">
                    <input class="general-form-input" type="text" name="username" placeholder="Username" maxlength="10" 
                           autocomplete="off" title="Username" required>
                    <input class="general-form-input" type="password" name="password" placeholder="Password" maxlength="12" 
                           autocomplete="off" title="Password" required>
                    <div class="message-wrapper">
                        <span class="warning-message">
                            <?php
                            session_start();

                            if (isset($_SESSION['loginError'])) {
                                
                                echo(htmlspecialchars($_SESSION['loginError']));;
                            }
                            
                            if(isset($_SESSION['signUpMessage']) && $_SESSION['signUpMessage'] == "") {
                                
                                echo("<script type='text/javascript'>alert('Sign Up Successful!');</script>");
                            }
                            
                            session_destroy();
                            ?>
                        </span>
                    </div>                        
                    <input class="general-form-input" type="submit" value="Login">
                    <div class="message-wrapper">
                        <a style="float: left" href="sign-up.php">Don't have an account?</a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
