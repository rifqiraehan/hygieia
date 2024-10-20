<?php

include '../db.php';

$response = null;

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'));
        $nama = $data->nama ?? "";
        $hp = $data->hp ?? "";
        $email = $data->email ?? "";

        $query_pasien = "INSERT INTO pasien (nama, hp, email) VALUES (?, ?, ?)";
        $query_user = "INSERT INTO user (username, password, id_pasien) VALUES (?, ?, ?)";

        $conn->beginTransaction();

        $stmt_pasien = $conn->prepare($query_pasien);
            $stmt_user = $conn->prepare($query_user) ;
            $stmt_pasien -> execute([$nama,$hp,$email]);
            $stmt_user -> execute([$email, sha1($hp), $conn->lastInsertId()]);
        $conn->commit();
            $response ['message'] = "Successful! please login using your email as username and phone number as password";
    }
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    if ($e->getCode() == 23000) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            $response['message'] = "This email is already registered. Please use a different email.";
        } else {
            $response['message'] = "Error: Integrity constraint violation.";
        }
    } else {
        $response['message'] = "Error: " . $e->getMessage();
    }
}

echo json_encode($response);