<?php
/**
* 
*/
class Db {
    
  public static function connect(){
    $parameters = include(ROOT.'/config/db.php');
    
    $dsn = "mysql:host={$parameters['Host']}; dbname={$parameters['HostDb']}";
    $options = array(
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );
    
    $db = new PDO($dsn, $parameters['user'], $parameters['password'], $options);

    return $db;
  }
}