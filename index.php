<?php
/*Общие настройки*/
ini_set('display_errors', 0);
error_reporting(E_ALL);

/* Константы */
define("ROOT",dirname(__FILE__));
define("SNAME",$_SERVER['SERVER_NAME']);

/*База данных*/
require_once(ROOT.'/router.php');
require_once(ROOT.'/ct.php');
require_once(ROOT.'/db.php');

/*Начало сессий*/
session_start();

$_SESSION['dev'] = true;

if (!isset($_SESSION['user'])) $_SESSION['user'] = 'guest';

$router = new Router();
$router->run();