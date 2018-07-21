<!DOCTYPE html>
<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/controller/EventController.php";
include_once "$root/model/constants/EventConstants.php";
include_once "$root/model/vo/Event.php";
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
        <title>Events List</title>
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
        <link href="styles/events-list.css" rel="stylesheet" type="text/css">

        <!-- External fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

        <!--  JavaScript files -->
        <script type="text/javascript" src="scripts/main.js"></script>
        
        <script type="text/javascript">
            function deleteSelectedEvent() {
                
                var selectedEvent = document.getElementsByClassName("selected-row")[0];
                
                if(selectedEvent != null) {
                    
                    var cell = selectedEvent.getElementsByTagName("td");
                    var inGoogleCalendar = cell[1].innerHTML;
                    var confirmMessage = "Are you want to delete this event?"
                    
                    if(inGoogleCalendar == "Yes") {
                        
                        confirmMessage = "This event will also be deleted in your Google Calendar account. " + confirmMessage;
                    }
                    
                    if (confirm(confirmMessage)) {
                        
                        window.location.href = "controller/delete-events-controller.php?event=" + selectedEvent.id;
                    }
                }
            }
            
            function updateSelectedEvent() {
                
                var selectedEvent = document.getElementsByClassName("selected-row")[0];
                
                if(selectedEvent != null) {
                    
                    window.location.href = "update-event.php?event=" + selectedEvent.id;
                }
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
            <li class="dropdown selected-navigation-option">
                <p>My Calendar</p>
                <div class="dropdown-menu">
                    <a href="create-events.php">Create Events</a>
                    <a class="selected-dropdown-option" href="events-list.php">Events List</a>
                </div>
            </li>
        </ul>
        <div class="wrapper" style="margin-top: -230px">
            <div class="inner-wrapper" style="width: 700px"> 
                <form id="events-list-form" action="#" method="post">
                    <div class="message-wrapper" style="margin-bottom: 20px">
                        <h2 style="text-align: center; color: rgb(102, 102, 102)">Events List</h2>
                    </div>
                    <div class="events-list-table-wrapper">
                        <table id="events-list-table">
                            <thead>
                                <tr>
                                    <th style="width: 150px">Event Title</th>
                                    <th style="width: 80px">Google Calendar</th>
                                    <th style="width: 120px">Event Type</th>
                                    <th style="width: 135px">Event Start Time</th>
                                    <th style="width: 135px">Event End Time</th>
                                    <th style="width: 80px">Event Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (session_status() == PHP_SESSION_NONE) {

                                    session_start();
                                }

                                $eventController = new EventController();
                                $events = $eventController->findAllUserEvents($_SESSION['user']->getId());

                                foreach ($events as $event) {

                                    $id = $event->getId();
                                    $eventTitle = $event->getEventTitle();
                                    $googleCalendar = 'No';
                                    $eventType = $event->getEventType();
                                    $eventStartTime = $event->getEventStartTime();
                                    $eventEndTime = $event->getEventEndTime();
                                    $eventDate = $event->getEventDate();

                                    if ($event->getGCalendarId() != NULL && $event->getGEventId() != NULL) {

                                        $googleCalendar = 'Yes';
                                    }
                                    
                                    if($eventType == EventConstants::ALL_DAY_EVENT['name']) {
                                        
                                        $eventType = EventConstants::ALL_DAY_EVENT['value'];
                                        $eventStartTime = '-';
                                        $eventEndTime = '-';
                                    } else {
                                        
                                        $eventType = EventConstants::FIXED_TIME_EVENT['value'];
                                        $eventDate = '-';
                                    }

                                    echo("<tr id='$id'> 
                                            <td>$eventTitle</td>
                                            <td>$googleCalendar</td>    
                                            <td>$eventType</td>");
                                    
                                    if($eventDate == '-') {
                                        
                                        echo("<td><script type='text/javascript'>document.write(new  Date('$eventStartTime').toLocaleString())</script></td>
                                              <td><script type='text/javascript'>document.write(new  Date('$eventEndTime').toLocaleString())</script></td>
                                              <td>$eventDate</td>
                                            </tr>");
                                    } else {
                                        
                                        $eventDate = $eventDate . ' 00:00';
                                        echo("<td>$eventStartTime</td>
                                              <td>$eventEndTime</td>
                                              <td><script type='text/javascript'>document.write(new  Date('$eventDate').toLocaleDateString())</script></td>
                                            </tr>");
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <script type="text/javascript">
                            addSelectionRowHandler("events-list-table");
                        </script>
                    </div>
                    <div class="events-list-input-wrapper">
                        <input type="button" name="update" value="Update" onclick="updateSelectedEvent()">
                        <input type="button" name="delete" value="Delete" onclick="deleteSelectedEvent()">
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>