<!DOCTYPE html>
<html>
    <head>
        <title>Profile Editing</title>
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

        <script type="text/javascript">
            function cancelProfileEditing() {

                window.location.replace("http://www.mycal.com/view/profile.php");
            }
        </script>
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
        <div class="wrapper" name="login-form" style="margin-top: -215px">
            <div class="inner-wrapper"> 
                <form id="profile-editing-form" onsubmit="javascript: return validatePasswords(
                                document.getElementById('password').value,
                                document.getElementById('re-enter-password').value,
                                'profile-editing-warning-message', this.id)"action="controller/profile-editing-controller.php" method="post">
                    <div class="profile-message-wrapper" style="margin-bottom: 20px">
                        <h2 style="text-align: center; color: rgb(102, 102, 102)">Profile Editing</h2>
                    </div>
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

                        echo("<div class='profile-input-wrapper' style='margin-bottom: 20px'>"
                        . "<h3 style='width:150px'>Full Name</h3>"
                        . "<input type='text' name='full-name' "
                        . "placeholder='Full name' value='$fullName' "
                        . "maxlength='50' autocomplete='off' title='Full Name' required>"
                        . "</div>");
                        echo("<div class='profile-input-wrapper' style='margin-bottom: 20px'>"
                        . "<h3 style='width:150px'>Email Address</h3>"
                        . "<input type='text' name='email' "
                        . "placeholder='Email address' value='$email' "
                        . "maxlength='50' autocomplete='off' title='Email Address' required>"
                        . "</div>");
                        echo("<div class='profile-input-wrapper' style='margin-bottom: 20px'>"
                        . "<h3 style='width:150px'>Username</h3>"
                        . "<input type='text' name='username' "
                        . "placeholder='Username' value='$userName' "
                        . "maxlength='10' autocomplete='off' title='Username' required>"
                        . "</div>");
                        echo("<div class='profile-input-wrapper' style='margin-bottom: 20px'>"
                        . "<h3 style='width:150px'>Password</h3>"
                        . "<input id='password' type='password' name='password' "
                        . "placeholder='Password' value='$password' "
                        . "maxlength='12' autocomplete='off' title='Password' required>"
                        . "</div>");
                        echo("<div class='profile-input-wrapper' style='margin-bottom: 20px'>"
                        . "<h3 style='width:150px'>Re-enter Password</h3>"
                        . "<input id='re-enter-password' type='password' name='re-enter-password' "
                        . "placeholder=Re-enter password' value='$password' "
                        . "maxlength='12' autocomplete='off' title='Re-enter Password' required>"
                        . "</div>");
                    } else {

                        echo("<script type='text/javascript'>"
                        . "alert('Your user has been disconected. Please, login again.');"
                        . "window.location.replace('http://www.mycal.com/view/login.php');"
                        . "</script>");
                    }
                    ?>
                    <div class="message-wrapper" style="width: 410px">
                        <div class="inner-message-wrapper">
                            <span id="profile-editing-warning-message" class="warning-message">
                                <?php
                                if (isset($_SESSION['profileEditingMessage'])) {

                                    echo(htmlspecialchars($_SESSION['profileEditingMessage']));
                                    $_SESSION['profileEditingMessage'] = NULL;
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="profile-message-wrapper" style="width: 410px">
                        <input style="float: right; width: 115px; margin-left: 20px" type="submit" name="save" value="Save">
                        <input style="float: right; width: 115px;" type="button" name="cancel" value="Cancel" onclick="cancelProfileEditing()">
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
