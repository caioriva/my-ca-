<?php

class User {
    
    private $id;
    private $fullName;
    private $email;
    private $username;
    private $password;
    private $gRefreshToken;
    private $gRefreshTokenActivated;
    
    public function __construct() { 
    }
    
    public static function create($id, $fullName, $email, $username, $password, $gRefreshToken, $gRefreshTokenActivated) {
        $instance = new self();
        $instance->id = $id;
        $instance->fullName = $fullName;
        $instance->email = $email;
        $instance->username = $username;
        $instance->password = $password;
        $instance->gRefreshToken = $gRefreshToken;
        $instance->gRefreshTokenActivated = $gRefreshTokenActivated;
        
        return $instance;
    }
   
    function getId() {
        return $this->id;
    }

    function getFullName() {
        return $this->fullName;
    }

    function getEmail() {
        return $this->email;
    }

    function getUsername() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }

    function getGRefreshToken() {
        return $this->gRefreshToken;
    }

    function getGRefreshTokenActivated() {
        return $this->gRefreshTokenActivated;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFullName($fullName) {
        $this->fullName = $fullName;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setGRefreshToken($gRefreshToken) {
        $this->gRefreshToken = $gRefreshToken;
    }

    function setGRefreshTokenActivated($gRefreshTokenActivated) {
        $this->gRefreshTokenActivated = $gRefreshTokenActivated;
    }
}
