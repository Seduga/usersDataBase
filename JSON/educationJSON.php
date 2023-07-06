<?php
require_once "../pdo.php";
session_start();
$stmt = $pdo->query('SELECT * from education join institution on education.institution_id = institution.institution_id order by rank');
$rows = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $rows[] = $row;
}
echo json_encode($rows)

?>