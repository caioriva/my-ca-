<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/model/dao/EventDAO.php";

class EventController {
    
    private $eventDao;

    public function __construct() {

        $this->eventDao = new EventDAO();
    }
    
    public function createEvent() {
        
        $returnValue = $this->eventDao->createEvent();
        $_SESSION['eventCreated'] = $returnValue;
        header("location: http://www.mycal.com/view/create-events.php");
    }
    
    public function findAllUserEvents($userId) {

        return $this->eventDao->findAllUserEvents($userId);
    }
    
    public function deleteEvent() {
        
        $this->eventDao->deleteEvent();
        header("location: http://www.mycal.com/view/events-list.php");
    }
    
    public function updateEvent() {
        
        $this->eventDao->updateEvent();
        header("location: http://www.mycal.com/view/events-list.php");
    }
}
