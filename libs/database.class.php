<?php

class Database
{
    public static $conn = null;
    public static function getConnection()
    {
        if (Database::$conn == null) {
            $servername = "mysql.selfmade.ninja";
            $username = "onedayservice" ; 
            $password = "9688682785";
            $dbname = "onedayservice_data";

            // Create connection
            $connection = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error); //TODO: Replace this with exception handling
            } else {
                //printf("New connection establishing...");
                Database::$conn = $connection; //replacing null with actual connection
                return Database::$conn;
            }
        } else {
            return Database::$conn;

        }
    }
}