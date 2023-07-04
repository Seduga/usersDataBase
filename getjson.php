<?php
require_once "pdo.php";
session_start();
$stmt = $pdo->query("SELECT user_id, profile_id, first_name , last_name , headline from profile");
$rows = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $rows[] = $row;
}
echo(json_encode($rows));

// $stmt = $pdo->prepare("SELECT * from profile where profile_id = :xyz");
// $stmt->execute(array(":xyz" => $_GET['profile_id']));
// while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//     $rows[] = $row;
// }




?>