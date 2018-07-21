<?php

class Event {
    
    private $id;
    private $eventTitle;
    private $eventType;
    private $eventStartTime;
    private $eventEndTime;
    private $eventDate;
    private $gCalendarId;
    private $gEventId;
    private $userId;
            
    public function __construct() { 
    }
    
    public static function create($id, $eventTitle, $eventType, $eventStartTime, $eventEndTime, $eventDate, $gCalendarId, $gEventId, $userId) {
        $instance = new self();
        $instance->id = $id;
        $instance->eventTitle = $eventTitle;
        $instance->eventType = $eventType;
        $instance->eventStartTime = $eventStartTime;
        $instance->eventEndTime = $eventEndTime;
        $instance->eventDate = $eventDate;
        $instance->gCalendarId = $gCalendarId;
        $instance->gEventId = $gEventId;
        $instance->userId = $userId;
        
        return $instance;
    }
    
    function getId() {
        return $this->id;
    }

    function getEventTitle() {
        return $this->eventTitle;
    }

    function getEventType() {
        return $this->eventType;
    }

    function getEventStartTime() {
        return $this->eventStartTime;
    }

    function getEventEndTime() {
        return $this->eventEndTime;
    }

    function getEventDate() {
        return $this->eventDate;
    }

    function getGCalendarId() {
        return $this->gCalendarId;
    }

    function getGEventId() {
        return $this->gEventId;
    }

    function getUserId() {
        return $this->userId;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setEventTitle($eventTitle) {
        $this->eventTitle = $eventTitle;
    }

    function setEventType($eventType) {
        $this->eventType = $eventType;
    }

    function setEventStartTime($eventStartTime) {
        $this->eventStartTime = $eventStartTime;
    }

    function setEventEndTime($eventEndTime) {
        $this->eventEndTime = $eventEndTime;
    }

    function setEventDate($eventDate) {
        $this->eventDate = $eventDate;
    }

    function setGCalendarId($gCalendarId) {
        $this->gCalendarId = $gCalendarId;
    }

    function setGEventId($gEventId) {
        $this->gEventId = $gEventId;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }
}
