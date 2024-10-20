<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include '../db.php';

$nama = $_GET['nama'] ?? null;
$query = "SELECT * FROM obat";

if ($nama != null) {
    $query = $query . " WHERE nama LIKE '%$nama%'";
}

$sql = $conn->query($query);
$response = $sql->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($response);