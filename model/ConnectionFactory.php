<?php
Class ConnectionFactory {

    const PERSISTENCE_FILE = "http://www.mycal.com/config/persistence.json";

    private $con;
    private $host;
    private $user;
    private $password;
    private $dbName;
    private $conString;
    private $conSuccessMsg;
    private $conFailureMsg;

    function __construct() {
        $data = json_decode(file_get_contents(self::PERSISTENCE_FILE));
        $this->host = $data->host;
        $this->user = $data->user;
        $this->password = $data->password;
        $this->dbName = $data->db_name;

        $this->initStrings();
    }

    public function open() {
        $this->con = pg_connect($this->conString);
    }

    public function close() {
        pg_close($this->con);
    }

    public function verifyConStatus() {
        if (!$this->con) {
            echo($this->conFailureMsg);
            exit();
        }
    }

    private function initStrings() {
        $this->conString = "host=" . $this->host
                . " user=" . $this->user
                . " password=" . $this->password
                . " dbname=" . $this->dbName;

        $this->conFailureMsg = "<h3>The system is not connected to "
                . $this->dbName . " on " . $this->host . ".</h3>";
    }

    function getCon() {
        return $this->con;
    }

}
