<!DOCTYPE html>
<html>
    <head>
        <title>Profile</title>
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
        <link href="styles/profile.css" rel="stylesheet" type="text/css">

        <!-- External fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

        <!--  JavaScript files -->
        <script type="text/javascript" src="scripts/main.js"></script>  
    </head>
    <body>
        <ul class="navigation-menu">
            <li class="login-logout-option">
                <a onclick="confirmUserLogout()" href="javascript:void(0);">
                    <p>Logout</p>
                </a>
            </li>
            <li>
                <a href="welcome.php">
                    <p>Welcome</p>
                </a>
            </li>
            <li class="dropdown selected-navigation-option">
                <p>About Me</p>
                <div class="dropdown-menu">
                    <a class="selected-dropdown-option" href="profile.php">Profile</a>
                    <a href="google-calendar.php">Google Calendar</a>
                </div>
            </li>
            <li class="dropdown">
                <p>My Calendar</p>
                <div class="dropdown-menu">
                    <a href="create-events.php">Create Events</a>
                    <a href="events-list.php">Events List</a>
                </div>
            </li>
        </ul>
        <div class="wrapper" name="login-form" style="margin-top: -165px">
            <div class="inner-wrapper"> 
                <?php
                $root = realpath($_SERVER["DOCUMENT_ROOT"]);
                include_once "$root/model/vo/User.php";

                session_start();

                if (isset($_SESSION['user'])) {

                    $connectedUser = $_SESSION['user'];
                    $firstName = explode(" ", $connectedUser->getFullName())[0];
                    $fullName = $connectedUser->getFullName();
                    $email = $connectedUser->getEmail();
                    $userName = $connectedUser->getUsername();
                    $password = $connectedUser->getPassword();


                    echo("<div class='profile-message-wrapper' style='margin-bottom: 20px'>"
                    . "<h2 style='text-align: center; color: rgb(102, 102, 102)'> $firstName's Profile </h2>"
                    . "</div>");
                    echo("<div class='profile-input-wrapper' style='margin-bottom: 20px'>"
                    . "<h3>Full Name</h3>"
                    . "<input type='text' name='full-name' "
                    . "placeholder='Full name' value='$fullName' "
                    . "title='Full Name' disabled>"
                    . "</div>");
                    echo("<div class='profile-input-wrapper' style='margin-bottom: 20px'>"
                    . "<h3>Email Address</h3>"
                    . "<input type='text' name='email' "
                    . "placeholder='Email address' value='$email' "
                    . "title='Email Address' disabled>"
                    . "</div>");
                    echo("<div class='profile-input-wrapper' style='margin-bottom: 20px'>"
                    . "<h3>Username</h3>"
                    . "<input type='text' name='username' "
                    . "placeholder='Username' value='$userName' "
                    . "title='Username' disabled>"
                    . "</div>");
                    echo("<div class='profile-input-wrapper' style='margin-bottom: 20px'>"
                    . "<h3>Password</h3>"
                    . "<input type='password' name='password' "
                    . "placeholder='Password' value='$password' "
                    . "title='Password' disabled>"
                    . "</div>");
                } else {
                    
                    echo("<script type='text/javascript'>"
                            . "alert('Your user has been disconected. Please, login again.');"
                            . "window.location.replace('http://www.mycal.com/view/login.php');"
                            . "</script>");
                }

                if (isset($_SESSION['profileEditingMessage']) && $_SESSION['profileEditingMessage'] == "") {

                    echo("<script type='text/javascript'>alert('Profile Editing Successful!');</script>");
                    $_SESSION['profileEditingMessage'] = NULL;
                }
                ?>
                <div class="profile-message-wrapper">
                    <a style="float: left" href="profile-editing.php">Want to change any profile information?</a>
                </div>
            </div>
        </div>
    </body>
</html>