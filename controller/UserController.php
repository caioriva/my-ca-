<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/model/dao/UserDAO.php";

class UserController {

    private $userDao;

    public function __construct() {

        $this->userDao = new UserDAO();
    }

    public function requestUserLogin() {

        $requestResponse = $this->userDao->requestUserLogin();

        if ($requestResponse == NULL) {

            header("location: http://www.mycal.com/view/welcome.php");
        } else {

            $_SESSION['loginError'] = $requestResponse;
            header("location: http://www.mycal.com/view/login.php");
        }
    }

    public function requestUserSignUp() {

        $requestResponse = $this->userDao->saveOrUpdateUser(TRUE);

        if ($requestResponse == NULL) {

            $_SESSION['signUpMessage'] = "";
            header("location: http://www.mycal.com/view/login.php");
        } else {

            $_SESSION['signUpMessage'] = $requestResponse;
            header("location: http://www.mycal.com/view/sign-up.php");
        }
    }

    public function requestUserLogout() {

        header("location: http://www.mycal.com/view/home.html");
    }

    public function editUserProfile() {

        $requestResponse = $this->userDao->saveOrUpdateUser(FALSE);

        if ($requestResponse == NULL) {

            $_SESSION['profileEditingMessage'] = "";
            header("location: http://www.mycal.com/view/profile.php");
        } else {

            $_SESSION['profileEditingMessage'] = $requestResponse;
            header("location: http://www.mycal.com/view/profile-editing.php");
        }
    }

    public function validateGoogleCalendarAuthorization() {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST["google-calendar-auth"])) {
                $gCalendarAuthMessage = $gCalendarUnauthMessage = $this->userDao->authorizeUserGoogleCalendar();
                $_SESSION['gCalendarAuthMessage'] = $gCalendarAuthMessage;
                header("location: http://www.mycal.com/view/google-calendar.php");
            } else {

                $gCalendarUnauthMessage = $this->userDao->unauthorizeUserGoogleCalendar();
                $_SESSION['gCalendarUnauthMessage'] = $gCalendarUnauthMessage;
                header("location: http://www.mycal.com/view/google-calendar.php");
            }
        }
    }

    public function authorizeUserGoogleCalendarAfterRedirect() {

        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            if (isset($_GET['code'])) {
                
                $this->userDao->authorizeUserGoogleCalendarByAuthCode($_GET['code']);
                $_SESSION['gCalendarAuthMessage'] = "";
                header("location: http://www.mycal.com/view/google-calendar.php");
            }
        }
    }
}
