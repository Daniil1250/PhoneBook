<?php
 try {
    $conn = new PDO("mysql:host=localhost;dbname=phonebook", "root", ""); 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
    echo "Ошибка:" . $e->getMessage();
    
}


     ?>