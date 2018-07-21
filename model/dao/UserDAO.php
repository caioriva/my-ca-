<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/googlecalendarapi/settings/GoogleAPIProvider.php";
include_once "$root/model/ConnectionFactory.php";
include_once "$root/model/vo/User.php";

class UserDAO {

    const QUERY_FILE = "http://www.mycal.com/database/query-lib.json";
    
    const LOGIN_GENERAL_ERROR = "Login Error";
    const SIGN_UP_GENERAL_ERROR = "Sign Up Error";
    const INVALID_USERNAME_ERROR = "Invalid Username";
    const INVALID_PASSWORD_ERROR = "Invalid Password";
    const GOOGLE_CALENDAR_AUTHORIZATION_ERROR = "Google Calendar Authorization Error";
    const GOOGLE_CALENDAR_UNAUTHORIZED = "My Cal has been unauthorized to access your Google Calendar account";
    const GOOGLE_CALENDAR_ALREADY_UNAUTHORIZED = "My Cal is already unauthorized to access your Google Calendar account";
    const GOOGLE_CALENDAR_ALREADY_AUTHORIZED = "My Cal is already authorized to access your Google Calendar account";

    private $connFactory;
    private $googleAPIProvider;
    private $queryLib;

    public function __construct() {

        $this->connFactory = new ConnectionFactory();
        $this->googleAPIProvider = GoogleAPIProvider::getInstance();
        $this->queryLib = json_decode(file_get_contents(self::QUERY_FILE));
    }

    public function requestUserLogin() {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);

            if (($username != NULL) && ($password != NULL)) {

                $this->connFactory->open();
                $this->connFactory->verifyConStatus();
                $con = $this->connFactory->getCon();

                $result = pg_prepare($con, $this->queryLib->findUserByUsername->name, $this->queryLib->findUserByUsername->value);
                $result = pg_execute($con, $this->queryLib->findUserByUsername->name, array($username));
                $this->connFactory->close();

                if (pg_num_rows($result) == 1) {

                    $resultArray = pg_fetch_all($result);
                    if ($resultArray[0]['password'] == $password) {

                        $_SESSION['user'] = User::create($resultArray[0]['id'], $resultArray[0]['full_name'], $resultArray[0]['email'], $resultArray[0]['username'], $resultArray[0]['password'], $resultArray[0]['g_refresh_token'], $resultArray[0]['g_refresh_token_activated']);

                        return NULL;
                    } else {

                        return self::INVALID_PASSWORD_ERROR;
                    }
                } else {

                    return self::INVALID_USERNAME_ERROR;
                }
            }
        }

        return self::LOGIN_GENERAL_ERROR;
    }

    public function saveOrUpdateUser($isToSave) {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (!$isToSave) {

                $id = $_SESSION['user']->getId();
                $gRefreshToken = $_SESSION['user']->getGRefreshToken();
                $gRefreshTokenActivated = $_SESSION['user']->getGRefreshTokenActivated();
            }

            $fullName = trim($_POST["full-name"]);
            $email = trim($_POST["email"]);
            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);
            $reEnterPassword = trim($_POST["re-enter-password"]);

            if (($fullName != NULL) && ($email != NULL) && ($username != NULL) && ($password != NULL) && ($reEnterPassword != NULL)) {



                $this->connFactory->open();
                $this->connFactory->verifyConStatus();
                $con = $this->connFactory->getCon();

                if ($isToSave) {

                    $result = pg_prepare($con, $this->queryLib->saveUser->name, $this->queryLib->saveUser->value);
                    $result = pg_execute($con, $this->queryLib->saveUser->name, array($fullName, $email, $username, $password));
                } else {

                    $result = pg_prepare($con, $this->queryLib->updateUser->name, $this->queryLib->updateUser->value);
                    $result = pg_execute($con, $this->queryLib->updateUser->name, array($fullName, $email, $username, $password, $id));
                }

                $this->connFactory->close();

                if ($result == TRUE) {

                    if (!$isToSave) {

                        $_SESSION['user'] = User::create($id, $fullName, $email, $username, $password, $gRefreshToken, $gRefreshTokenActivated);
                    }

                    return NULL;
                } else {

                    return self::SIGN_UP_GENERAL_ERROR;
                }
            }
        }

        return self::SIGN_UP_GENERAL_ERROR;
    }

    public function unauthorizeUserGoogleCalendar() {

        $gRefreshToken = $_SESSION['user']->getGRefreshToken();
        $isGRefreshTokenActivated = $_SESSION['user']->getGRefreshTokenActivated();

        if ($gRefreshToken != NULL && $isGRefreshTokenActivated == 't') {

            $id = $_SESSION['user']->getId();
            
            $this->connFactory->open();
            $this->connFactory->verifyConStatus();
            $con = $this->connFactory->getCon();
            $result = pg_prepare($con, $this->queryLib->updateGoogleGalendarRefreshToken->name, $this->queryLib->updateGoogleGalendarRefreshToken->value);
            $result = pg_execute($con, $this->queryLib->updateGoogleGalendarRefreshToken->name, array($gRefreshToken, 'FALSE', $id));
            $this->connFactory->close();

            if ($result == TRUE) {

                if (isset($_SESSION['accessTokenExpiry']) && isset($_SESSION['accessToken'])) {

                    $_SESSION['accessTokenExpiry'] = NULL;
                    $_SESSION['accessToken'] = NULL;
                }

                $_SESSION['user']->setGRefreshTokenActivated(FALSE);
                return self::GOOGLE_CALENDAR_UNAUTHORIZED;
            } else {

                return self::GOOGLE_CALENDAR_AUTHORIZATION_ERROR;
            }
        } else {

            return self::GOOGLE_CALENDAR_ALREADY_UNAUTHORIZED;
        }
    }

    public function authorizeUserGoogleCalendar() {

        $gRefreshToken = $_SESSION['user']->getGRefreshToken();
        $isGRefreshTokenActivated = $_SESSION['user']->getGRefreshTokenActivated();

        if ($gRefreshToken != NULL && $isGRefreshTokenActivated == 't') {

            return self::GOOGLE_CALENDAR_ALREADY_AUTHORIZED;
        } else {

            $googleCalendarURL = $this->googleAPIProvider->getGoogleCalendarURL();

            header("location: $googleCalendarURL");
            exit();
        }
    }

    public function authorizeUserGoogleCalendarByAuthCode($authCode) {

        try {

            $calendarData = $this->googleAPIProvider->getAccessTokenByAuthorizationCode($authCode);

            if ($calendarData['refresh_token'] != NULL) {

                $refreshToken = $calendarData['refresh_token'];
            } else {

                $refreshToken = $_SESSION['user']->getGRefreshToken();
            }

            $_SESSION['accessTokenExpiry'] = time() + $calendarData['expires_in'];
            $_SESSION['accessToken'] = $calendarData['access_token'];

            $id = $_SESSION['user']->getId();

            $this->connFactory->open();
            $this->connFactory->verifyConStatus();
            $con = $this->connFactory->getCon();

            $queryLib = json_decode(file_get_contents(self::QUERY_FILE));
            $result = pg_prepare($con, $queryLib->updateGoogleGalendarRefreshToken->name, $queryLib->updateGoogleGalendarRefreshToken->value);
            $result = pg_execute($con, $queryLib->updateGoogleGalendarRefreshToken->name, array($refreshToken, 'TRUE', $id));
            $this->connFactory->close();

            $_SESSION['user']->setGRefreshToken($refreshToken);
            $_SESSION['user']->setGRefreshTokenActivated(TRUE);
        } catch (Exception $ex) {

            echo $ex->getMessage();
            exit();
        }
    }
}