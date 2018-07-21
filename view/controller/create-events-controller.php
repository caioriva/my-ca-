<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/controller/EventController.php";

session_start();
$eventController = new EventController();
$eventController->createEvent();
session_write_close();
