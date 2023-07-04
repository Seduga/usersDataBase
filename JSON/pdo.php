<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'Danil', 'kek');
$stmt = $pdo ->query('SELECT * FROM users');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


?>