<!DOCTYPE html>
<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/model/constants/EventConstants.php";
include_once "$root/model/vo/User.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    
    echo("<script type='text/javascript'>"
        . "alert('Your user has been disconected. Please, login again.');"
        . "window.location.replace('http://www.mycal.com/view/login.php');"
        . "</script>");
}
?>
<html>
    <head>
        <title>Create Events</title>
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

        <script type='text/javascript'>

            window.onload = function () {

                showInputByEventType(document.getElementById("event-type")
                        .options[document.getElementById("event-type").selectedIndex].value);
            };

            function showInputByEventType(selectedItem) {
                <?php
                $allDayName = EventConstants::ALL_DAY_EVENT['name'];
                $fixedTimeName = EventConstants::FIXED_TIME_EVENT['name'];

                echo("if (selectedItem == '$fixedTimeName') {   
                        showDivById('$fixedTimeName'); 
                        hideDivById('$allDayName');
                    } else { 
                        showDivById('$allDayName'); 
                        hideDivById('$fixedTimeName');
                    }");
            ?>
            }

            function validateCreateEventsForm() {

                var eventTypeValue = document.getElementById("event-type").options[document.getElementById("event-type").selectedIndex].value;
                var fixedTimeEvent = null;

                <?php
                $fixedTimeName = EventConstants::FIXED_TIME_EVENT['name'];
                echo("fixedTimeEvent = '$fixedTimeName'");
                ?>

                if (eventTypeValue == fixedTimeEvent) {

                    var startTimeValue = document.getElementById("event-start-time").value;
                    var endTimeValue = document.getElementById("event-end-time").value;

                    if (!startTimeValue || !endTimeValue) {

                        document.getElementById("create-event-form").reset();
                        document.getElementById("create-event-warning-message").innerHTML = "Date Field(s) Unfilled";
                        return false;
                    }

                    var startTime = new Date(startTimeValue);
                    var endTime = new Date(endTimeValue);

                    if (startTime < new Date() || endTime < new Date()) {

                        document.getElementById("create-event-form").reset();
                        document.getElementById("create-event-warning-message").innerHTML = "Date(s) Less Than Current Date";
                        return false;
                    } else if (endTime <= startTime){

                        document.getElementById("create-event-form").reset();
                        document.getElementById("create-event-warning-message").innerHTML = "End Date Less Than Start Date";
                        return false;
                    } else {
                        
                        return true;
                    }
                } else {

                    var dateValue = document.getElementById("event-date").value;

                    if (!dateValue) {

                        document.getElementById("create-event-form").reset();
                        document.getElementById("create-event-warning-message").innerHTML = "Date Field(s) Unfilled";
                        return false;
                    }

                    var date = new Date(dateValue);

                    if (date < new Date()) {

                        document.getElementById("create-event-form").reset();
                        document.getElementById("create-event-warning-message").innerHTML = "Date(s) Less Than Current Date";
                        return false;
                    } else {

                        return true;
                    }
                }
            }
        </script>

        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['eventCreated'])) {

            if ($_SESSION['eventCreated']) {

                echo("<script type='text/javascript'>alert('Your event has been created');</script>");
            } else {

                echo("<script type='text/javascript'>alert('Event Creation Problems: Your event has not been created');</script>");
            }

            $_SESSION['eventCreated'] = NULL;
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
            <li class="dropdown">
                <p>About Me</p>
                <div class="dropdown-menu">
                    <a href="profile.php">Profile</a>
                    <a href="google-calendar.php">Google Calendar</a>
                </div>
            </li>
            <li class="dropdown selected-navigation-option">
                <p>My Calendar</p>
                <div class="dropdown-menu">
                    <a class="selected-dropdown-option" href="create-events.php">Create Events</a>
                    <a href="events-list.php">Events List</a>
                </div>
            </li>
        </ul>
        <div class="wrapper" name="login-form" style="margin-top: -140px">
            <div class="inner-wrapper"> 
                <form id="create-event-form" action="controller/create-events-controller.php" method="post" onsubmit="javascript:return validateCreateEventsForm()">
                    <div class="message-wrapper" style="margin-bottom: 20px">
                        <h2 style="text-align: center; color: rgb(102, 102, 102)">Create Your Events</h2>
                    </div>
                    <input class="general-form-input" type="text" name="event-title" placeholder="Event Title" autocomplete="off" title="Event Title" maxlength="50" required/>
                    <?php
                    $allDayName = EventConstants::ALL_DAY_EVENT['name'];
                    $fixedTimeName = EventConstants::FIXED_TIME_EVENT['name'];
                    $allDayValue = EventConstants::ALL_DAY_EVENT['value'];
                    $fixedTimeValue = EventConstants::FIXED_TIME_EVENT['value'];

                    echo("<select id='event-type' class='general-form-input' name='event-type' autocomplete='off' 
                            onchange='javascript:showInputByEventType(this.options[this.selectedIndex].value);' required>
                                <option value='$fixedTimeName'>$fixedTimeValue</option>
                                <option value='$allDayName'>$allDayValue</option>
                        </select>
                        <div id='$fixedTimeName'>
                            <input id='event-start-time' class='general-form-input' type='datetime-local' name='event-start-time' 
                                placeholder='Event Start Time' autocomplete='off' title='Event Start Date/Time'/>
                            <input id='event-end-time' class='general-form-input' type='datetime-local' name='event-end-time' 
                                placeholder='Event End Time' autocomplete='off' title='Event End Date/Time'/>
                        </div>
                        <div id='$allDayName'>
                            <input id='event-date' class='general-form-input' type='date' name='event-date' 
                            placeholder='Event Date' autocomplete='off' title='Event'/>
                        </div>");
                    ?>
                    <div class="message-wrapper">
                        <span id="create-event-warning-message" class="warning-message"></span>
                    </div>
                    <input class="general-form-input" type="submit" value="Create">
                </form>
            </div>
        </div>
    </body>
</html>