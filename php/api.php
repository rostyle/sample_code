<?php
require('common.php');

// リクエストがPOSTの場合のみ実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTデータからlicense_keyを取得
    $license_key = $_POST['license_key'] ?? null;

    if (!is_null($license_key)) {
        try {
            $db = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            $stmt = $db->prepare("SELECT * FROM licensekeys WHERE license_key = :license_key");
            $stmt->bindParam(':license_key', $license_key, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                // レコードが見つかった場合、statusを返す
                $status = $data['status'];
                $response = ['status' => $status];
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                // レコードが見つからない場合、エラーメッセージを返す
                $response = ['error' => 'License key not found'];
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        } catch (PDOException $e) {
            $response = ['error' => 'Database connection failed: ' . $e->getMessage()];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    } else {
        // license_keyがPOSTデータにない場合、エラーメッセージを返す
        $response = ['error' => 'License key is missing'];
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // POSTリクエストでない場合、エラーメッセージを返す
    $response = ['error' => 'Invalid request method'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
