<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/googlecalendarapi/settings/GoogleAPIProvider.php";
include_once "$root/googlecalendarapi/calendar/GoogleCalendarAPI.php";
include_once "$root/model/constants/EventConstants.php";
include_once "$root/model/ConnectionFactory.php";
include_once "$root/model/vo/User.php";
include_once "$root/model/vo/Event.php";

class EventDAO {

    const FIND_EVENT_ERROR = 'Error: Failed to find event';
    const QUERY_FILE = "http://www.mycal.com/database/query-lib.json";

    private $connFactory;
    private $googleAPIProvider;
    private $googleCalendarAPI;
    private $queryLib;

    public function __construct() {

        $this->connFactory = new ConnectionFactory();
        $this->googleAPIProvider = GoogleAPIProvider::getInstance();
        $this->googleCalendarAPI = new GoogleCalendarAPI();
        $this->queryLib = json_decode(file_get_contents(self::QUERY_FILE));
    }

    public function createEvent() {

        try {

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $userId = $_SESSION['user']->getId();
                $eventTitle = $_POST['event-title'];
                $eventType = $_POST['event-type'];

                $this->connFactory->open();
                $this->connFactory->verifyConStatus();
                $con = $this->connFactory->getCon();

                if ($eventType == EventConstants::FIXED_TIME_EVENT['name']) {


                    $startDatetime = new DateTime($_POST['event-start-time']);
                    $endDatetime = new DateTime($_POST['event-end-time']);
                    $eventStartTime = $startDatetime->format('Y-m-d\TH:i:s');
                    $eventEndTime = $endDatetime->format('Y-m-d\TH:i:s');

                    $calendarId = NULL;
                    $eventId = NULL;

                    if ($_SESSION['user']->getGRefreshToken() != NULL && $_SESSION['user']->getGRefreshTokenActivated() == 't') {

                        $isFullDayEvent = FALSE;
                        $calendarId = EventConstants::DEFAULT_CALENDAR_ID;

                        $calendarData = $this->googleAPIProvider->getNewAccessTokenIfExpired($_SESSION['accessTokenExpiry'], $_SESSION['user']->getGRefreshToken());
                        $accessToken = NULL;

                        if ($calendarData == NULL) {

                            $accessToken = $_SESSION['accessToken'];
                        } else {

                            $_SESSION['accessTokenExpiry'] = time() + $calendarData['expires_in'];
                            $_SESSION['accessToken'] = $calendarData['access_token'];
                            $accessToken = $calendarData['access_token'];
                        }

                        $userTimezone = $this->googleCalendarAPI->getUserCalendarTimezone($accessToken);
                        $eventTime = ['startTime' => $eventStartTime, 'endTime' => $eventEndTime];
                        $eventId = $this->googleCalendarAPI->createCalendarEvent($calendarId, $eventTitle, $isFullDayEvent, $eventTime, $userTimezone, $accessToken);
                    }

                    $result = pg_prepare($con, $this->queryLib->saveEvent->name, $this->queryLib->saveEvent->value);
                    $result = pg_execute($con, $this->queryLib->saveEvent->name, array($eventTitle, $eventType, $eventStartTime, $eventEndTime, NULL, $calendarId, $eventId, $userId));
                } else {

                    $eventDate = $_POST['event-date'];
                    $calendarId = NULL;
                    $eventId = NULL;

                    if ($_SESSION['user']->getGRefreshToken() != NULL && $_SESSION['user']->getGRefreshTokenActivated()) {

                        $isFullDayEvent = TRUE;
                        $calendarId = EventConstants::DEFAULT_CALENDAR_ID;

                        $calendarData = $this->googleAPIProvider->getNewAccessTokenIfExpired($_SESSION['accessTokenExpiry'], $_SESSION['user']->getGRefreshToken());
                        $accessToken = NULL;

                        if ($calendarData == NULL) {

                            $accessToken = $_SESSION['accessToken'];
                        } else {

                            $_SESSION['accessTokenExpiry'] = time() + $calendarData['expires_in'];
                            $_SESSION['accessToken'] = $calendarData['access_token'];
                            $accessToken = $calendarData['access_token'];
                        }

                        $userTimezone = $this->googleCalendarAPI->getUserCalendarTimezone($accessToken);
                        $eventTime = ['eventDate' => $eventDate];
                        $eventId = $this->googleCalendarAPI->createCalendarEvent($calendarId, $eventTitle, $isFullDayEvent, $eventTime, $userTimezone, $accessToken);
                    }

                    $result = pg_prepare($con, $this->queryLib->saveEvent->name, $this->queryLib->saveEvent->value);
                    $result = pg_execute($con, $this->queryLib->saveEvent->name, array($eventTitle, $eventType, NULL, NULL, $eventDate, $calendarId, $eventId, $userId));
                }

                $this->connFactory->close();
                return TRUE;
            }

            return FALSE;
        } catch (Exception $ex) {

            echo $ex->getMessage();
            exit();
        }
    }

    public function findAllUserEvents($userId) {

        try {

            $this->connFactory->open();
            $this->connFactory->verifyConStatus();
            $con = $this->connFactory->getCon();
            $result = pg_prepare($con, $this->queryLib->findAllEventsByUserId->name, $this->queryLib->findAllEventsByUserId->value);
            $result = pg_execute($con, $this->queryLib->findAllEventsByUserId->name, array($userId));
            $this->connFactory->close();

            $resultArray = pg_fetch_all($result);
            $events = array();

            if ($resultArray == TRUE) {

                foreach ($resultArray as $eventArray) {

                    $event = Event::create($eventArray['id'], $eventArray['event_title'], $eventArray['event_type'], $eventArray['event_start_time'], $eventArray['event_end_time'], $eventArray['event_date'], $eventArray['g_calendar_id'], $eventArray['g_event_id'], $eventArray['user_id']);
                    array_push($events, $event);
                }
            }

            return $events;
        } catch (Exception $ex) {

            echo $ex->getMessage();
            exit();
        }
    }

    public function findEventById($id) {

        try {

            $this->connFactory->open();
            $this->connFactory->verifyConStatus();
            $con = $this->connFactory->getCon();
            $result = pg_prepare($con, $this->queryLib->findEventById->name, $this->queryLib->findEventById->value);
            $result = pg_execute($con, $this->queryLib->findEventById->name, array($id));
            $this->connFactory->close();

            $resultArray = pg_fetch_all($result);

            if ($resultArray == TRUE) {

                $event = Event::create($resultArray[0]['id'], $resultArray[0]['event_title'], $resultArray[0]['event_type'], $resultArray[0]['event_start_time'], $resultArray[0]['event_end_time'], $resultArray[0]['event_date'], $resultArray[0]['g_calendar_id'], $resultArray[0]['g_event_id'], $resultArray[0]['user_id']);
            } else {

                throw new Exception(self::FIND_EVENT_ERROR);
            }

            return $event;
        } catch (Exception $ex) {

            echo $ex->getMessage();
            exit();
        }
    }

    public function deleteEvent() {

        try {

            if ($_SERVER["REQUEST_METHOD"] == "GET") {

                if (isset($_GET['event'])) {

                    $eventId = $_GET['event'];
                    $event = $this->findEventById($eventId);

                    if ($_SESSION['user']->getGRefreshToken() != NULL && $_SESSION['user']->getGRefreshTokenActivated() == 't' && $event->getGCalendarId() != NULL && $event->getGEventId() != NULL) {

                        $calendarData = $this->googleAPIProvider->getNewAccessTokenIfExpired($_SESSION['accessTokenExpiry'], $_SESSION['user']->getGRefreshToken());
                        $accessToken = NULL;

                        if ($calendarData == NULL) {

                            $accessToken = $_SESSION['accessToken'];
                        } else {

                            $_SESSION['accessTokenExpiry'] = time() + $calendarData['expires_in'];
                            $_SESSION['accessToken'] = $calendarData['access_token'];
                            $accessToken = $calendarData['access_token'];
                        }

                        $this->googleCalendarAPI->deleteCalendarEvent($event->getGEventId(), $event->getGCalendarId(), $accessToken);
                    }

                    $this->connFactory->open();
                    $this->connFactory->verifyConStatus();
                    $con = $this->connFactory->getCon();
                    $result = pg_prepare($con, $this->queryLib->deleteEvent->name, $this->queryLib->deleteEvent->value);
                    $result = pg_execute($con, $this->queryLib->deleteEvent->name, array($eventId));
                    $this->connFactory->close();
                }
            }
        } catch (Exception $ex) {

            echo $ex->getMessage();
            exit();
        }
    }

    public function updateEvent() {

        try {

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $userId = $_SESSION['user']->getId();
                $eventId = $_POST['event-id'];
                $eventTitle = $_POST['event-title'];
                $eventType = $_POST['event-type'];
                $oldEvent = $this->findEventById($eventId);

                $this->connFactory->open();
                $this->connFactory->verifyConStatus();
                $con = $this->connFactory->getCon();

                if ($eventType == EventConstants::FIXED_TIME_EVENT['name']) {


                    $startDatetime = new DateTime($_POST['event-start-time']);
                    $endDatetime = new DateTime($_POST['event-end-time']);
                    $eventStartTime = $startDatetime->format('Y-m-d\TH:i:s');
                    $eventEndTime = $endDatetime->format('Y-m-d\TH:i:s');

                    if ($_SESSION['user']->getGRefreshToken() != NULL && $_SESSION['user']->getGRefreshTokenActivated() == 't' 
                            && $oldEvent->getGCalendarId() != NULL && $oldEvent->getGEventId() != NULL) {

                        $isFullDayEvent = FALSE;

                        $calendarData = $this->googleAPIProvider->getNewAccessTokenIfExpired($_SESSION['accessTokenExpiry'], $_SESSION['user']->getGRefreshToken());
                        $accessToken = NULL;

                        if ($calendarData == NULL) {

                            $accessToken = $_SESSION['accessToken'];
                        } else {

                            $_SESSION['accessTokenExpiry'] = time() + $calendarData['expires_in'];
                            $_SESSION['accessToken'] = $calendarData['access_token'];
                            $accessToken = $calendarData['access_token'];
                        }

                        $userTimezone = $this->googleCalendarAPI->getUserCalendarTimezone($accessToken);
                        $eventTime = ['startTime' => $eventStartTime, 'endTime' => $eventEndTime];
                        $this->googleCalendarAPI->updateCalendarEvent($oldEvent->getGEventId(), $oldEvent->getGCalendarId(), $eventTitle, $isFullDayEvent, $eventTime, $userTimezone, $accessToken);
                    }

                    $result = pg_prepare($con, $this->queryLib->updateEvent->name, $this->queryLib->updateEvent->value);
                    $result = pg_execute($con, $this->queryLib->updateEvent->name, array($eventTitle, $eventType, $eventStartTime, $eventEndTime, NULL, $eventId));
                } else {

                    $eventDate = $_POST['event-date'];

                    if ($_SESSION['user']->getGRefreshToken() != NULL && $_SESSION['user']->getGRefreshTokenActivated()
                            && $oldEvent->getGCalendarId() != NULL && $oldEvent->getGEventId() != NULL) {

                        $isFullDayEvent = TRUE;

                        $calendarData = $this->googleAPIProvider->getNewAccessTokenIfExpired($_SESSION['accessTokenExpiry'], $_SESSION['user']->getGRefreshToken());
                        $accessToken = NULL;

                        if ($calendarData == NULL) {

                            $accessToken = $_SESSION['accessToken'];
                        } else {

                            $_SESSION['accessTokenExpiry'] = time() + $calendarData['expires_in'];
                            $_SESSION['accessToken'] = $calendarData['access_token'];
                            $accessToken = $calendarData['access_token'];
                        }

                        $userTimezone = $this->googleCalendarAPI->getUserCalendarTimezone($accessToken);
                        $eventTime = ['eventDate' => $eventDate];
                        $this->googleCalendarAPI->updateCalendarEvent($oldEvent->getGEventId(), $oldEvent->getGCalendarId(), $eventTitle, $isFullDayEvent, $eventTime, $userTimezone, $accessToken);
                    }

                    $result = pg_prepare($con, $this->queryLib->updateEvent->name, $this->queryLib->updateEvent->value);
                    $result = pg_execute($con, $this->queryLib->updateEvent->name, array($eventTitle, $eventType, NULL, NULL, $eventDate, $eventId));
                }

                $this->connFactory->close();
            }
        } catch (Exception $ex) {

            echo $ex->getMessage();
            exit();
        }
    }
}
