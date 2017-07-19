<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 18/07/17
 * Time: 14:01
 */

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = 'coda';
$DB_NAME = 'journal_complete';

try{
    $DB_con = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME}",$DB_USER,$DB_PASS);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo $e->getMessage();
}

include 'moteur.php';
$crud = new Crud($DB_con);
