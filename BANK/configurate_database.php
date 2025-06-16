<?php
    $host= 'localhost';
    $dbUser= 'root';
    $dbPass= '';

    if (!$dbConn=mysqli_connect($host, $dbUser, $dbPass))
        die('Не може да се осъществи връзка със сървъра.');

    $sql = 'CREATE Database IF NOT EXISTS bankDB'; 
    if (!$queryResource=mysqli_query($dbConn,$sql))
        die('Не може да се създаде базата данни.');
    
     if (!mysqli_select_db($dbConn,'bankDB')) 
        die('Не може да се селектира базата данни.');
     mysqli_query($dbConn,"SET NAMES 'UTF8'");
?>
