<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/controller/UserController.php";

session_start();
$userController = new UserController();
$userController->requestUserLogout();
session_destroy();

