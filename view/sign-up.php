<!DOCTYPE html>
<html>
    <head>
        <title>Sign Up</title>
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
        
        <!--  JavaScript files -->
        <script type="text/javascript" src="scripts/main.js"></script>
    </head>
    <body>
        <ul class="navigation-menu">
            <li class="login-logout-option">
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
        <div class="wrapper" style="margin-top: -186.5px">
            <div class="inner-wrapper">
                <form id="sign-up-form" action="controller/sign-up-controller.php" method="post"
                      onsubmit="javascript: return validatePasswords(document.getElementById('password').value,
                                  document.getElementById('re-enter-password').value,
                                  'sign-up-warning-message', this.id);">
                    <div class="message-wrapper" style="margin-bottom: 20px">
                        <h2 style="text-align: center; color: rgb(102, 102, 102)">Create a New Account</h2>
                    </div>
                    <input class="general-form-input" type="text" name="full-name" placeholder="Full name" maxlength="50" 
                           autocomplete="off" title="Fuul Name" required>
                    <input class="general-form-input" type="text" name="email" placeholder="Email address" maxlength="50" 
                           autocomplete="off" title="Email Address" required>
                    <input class="general-form-input" type="text" name="username" placeholder="Username" maxlength="10" 
                           autocomplete="off" title="Username" required>
                    <input id="password" class="general-form-input" type="password" name="password" placeholder="Password" maxlength="12" 
                           autocomplete="off" title="Password" required>
                    <input id="re-enter-password" class="general-form-input" style="margin-bottom: 5px" type="password" name="re-enter-password" 
                           placeholder="Re-enter password" maxlength="10" autocomplete="off" title="Re-enter Password" required>
                    <div class="message-wrapper">
                        <span id="sign-up-warning-message" class="warning-message">
                            <?php
                            session_start();

                            if (isset($_SESSION['signUpMessage'])) {

                                if($_SESSION['signUpMessage'] != ""){
                                    
                                    echo(htmlspecialchars($_SESSION['signUpMessage']));
                                }
                            }

                            session_destroy();
                            ?>
                        </span>
                    </div>
                    <input class="general-form-input" type="submit" name="sign-up" value="Sign Up" required>
                </form>
            </div>
        </div>
    </body>
</html>