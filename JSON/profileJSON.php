<?php
require_once "../pdo.php";
session_start();
    $stmt = $pdo->query('SELECT * from profile');
    $rows = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $row;
    
    }
    echo (json_encode($rows));
?>