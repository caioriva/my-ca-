<!DOCTYPE html>
<html>
    <head>
        <title>Welcome</title>
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
                <a onclick="confirmUserLogout()" href="javascript:void(0);">
                    <p>Logout</p>
                </a>
            </li>
            <li class="selected-navigation-option">
                <a href="welcome.php">
                    <p>Welcome</p>
                </a>
            </li>
            <li class="dropdown">
                <p>About Me</p>
                <div class="dropdown-menu">
                    <a href="profile.php">Profile</a>
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
        <div class="wrapper" style="margin-top: -170px">
            <div class="inner-wrapper">
                <img style="text-align: center" src="../images/my-cal-logo.png" alt="MyCal">
                <div class="message-wrapper" style="width: 500px">
                    <h1 style="text-align: center; color: rgb(102, 102, 102)">
                        Welcome, 
                        <?php
                        $root = realpath($_SERVER["DOCUMENT_ROOT"]);
                        include_once "$root/model/vo/User.php";

                        session_start();

                        if (isset($_SESSION['user'])) {

                            $connectedUser = $_SESSION['user'];
                            $firstName = explode(" ", $connectedUser->getFullName())[0];
                            echo($firstName);
                        } else {
                            echo("<script type='text/javascript'>"
                            . "alert('Your user has been disconected. Please, login again.');"
                            . "window.location.replace('http://www.mycal.com/view/login.php');"
                            . "</script>");
                        }
                        ?>!
                    </h1>
                </div>
            </div>
        </div>
    </body>
</html>