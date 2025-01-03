<?php
session_start();

$arquivo = strtolower(end(  explode('/', $_SERVER['SCRIPT_NAME'])));

if(isset($_SESSION['id']) && $arquivo == 'login.php') {
  header('Location: /');
} else if (!isset($_SESSION['id']) && $arquivo != 'login.php') {
  header('Location: /login.php');
}