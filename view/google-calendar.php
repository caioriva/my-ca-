<!DOCTYPE html>
<html>
    <head>
        <title>Google Calendar</title>
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
        <link href="styles/google-calendar.css" rel="stylesheet" type="text/css">

        <!-- External fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

        <!--  JavaScript files -->
        <script type="text/javascript" src="scripts/main.js"></script>

        <?php
        $root = realpath($_SERVER["DOCUMENT_ROOT"]);
        include_once "$root/model/vo/User.php";

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['gCalendarUnauthMessage'])) {
            
            $gCalendarUnauthMessage = htmlspecialchars($_SESSION['gCalendarUnauthMessage']);
            echo("<script type='text/javascript'>alert('$gCalendarUnauthMessage');</script>");
            $_SESSION['gCalendarUnauthMessage'] = NULL;
        } else if (isset($_SESSION['gCalendarAuthMessage'])) {
            
            $gCalendarAuthMessage = htmlspecialchars($_SESSION['gCalendarAuthMessage']);

            if ($gCalendarAuthMessage != "") {

                echo("<script type='text/javascript'>alert('$gCalendarAuthMessage');</script>");
            } else {

                echo("<script type='text/javascript'>alert('My Cal has been authorized to access your Google Calendar account');</script>");
            }
            $_SESSION['gCalendarAuthMessage'] = NULL;
        }
        ?>
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
                    <a href="profile.php">Profile</a>
                    <a class="selected-dropdown-option" href="google-calendar.php">Google Calendar</a>
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
        <div class="wrapper" name="login-form" style="margin-top: -215.5px">
            <div class="inner-wrapper"> 
                <img style="text-align: center" src="../images/google-calendar-logo.png" alt="Google Calendar">
                <div class="message-wrapper" style="margin-bottom: 20px; width: 500px">
                    <h2 style="text-align: center; color: rgb(102, 102, 102)">Google Calendar Authorization</h2>
                </div>
                <form action="controller/google-calendar-controller.php" method="post">
                    <div class='google-calendar-input-wrapper' style='margin-bottom: 20px'>
                        <?php
                        if (session_status() == PHP_SESSION_NONE) {
                            session_start();
                        }

                        if (isset($_SESSION['user'])) {

                            $googleCalendarToken = $_SESSION['user']->getGRefreshToken();
                            $isGRefreshTokenActivated = $_SESSION['user']->getGRefreshTokenActivated();
                            
                            if ($googleCalendarToken != NULL && $isGRefreshTokenActivated == 't') {

                                echo("<input type='checkbox' name='google-calendar-auth' value='authorized' checked>");
                            } else {

                                echo("<input type='checkbox' name='google-calendar-auth' value='unauthorized'>");
                            }
                        } else {

                            echo("<script type='text/javascript'>"
                            . "alert('Your user has been disconected. Please, login again.');"
                            . "window.location.replace('http://www.mycal.com/view/login.php');"
                            . "</script>");
                        }
                        ?>
                        <label style="color: rgb(102, 102, 102)">I authorize My Cal to have access to my Google Calendar account</label>
                    </div>
                    <input class="general-form-input" type='submit' name='save' value='Save'>
                </form>
            </div>
        </div>
    </body>
</html>
