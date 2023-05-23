<?php

// データベース接続
try {
    $db = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

} catch (PDOException $e) {
    echo '接続失敗' . $e->getMessage();
    exit();
}

// SELECT文
$stmt = $db->prepare("SELECT * FROM clients WHERE id=?");
$stmt->execute([$client_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// SELECT文（fetchAll()、降順）
$stmt = $db->prepare("SELECT * FROM users WHERE shop_id=? ORDER BY created_at DESC");
$stmt->execute([$shop_id]);
$data = $stmt->fetchAll();

// INSERT文
$sql = "INSERT INTO clients (id, name, tel, ref) VALUES (:id, :name, :tel, :ref)";
$stmt = $db->prepare($sql);
$params = array(':id' => $id, ':name' => $name, ':tel' => $tel, ':ref' => $ref);
$stmt->execute($params);

// DELETE文
$stmt = $db->prepare('DELETE FROM temp_new WHERE email = :email');
$stmt->execute(array(':email' => $email));

// UPDATE文
$stmt = $db->prepare('UPDATE clients SET name=:name, manager=:manager, tel=:tel WHERE id = :id');
$stmt->execute(array(':id' => $id, ':name' => $name, ':manager' => $manager, ':tel' => $tel));


// INSERT文
$stmt = $db->prepare("INSERT INTO user_pdf_access (pdf_version_id, user_id, cid) VALUES (?, ? , ?)");
$stmt->execute([$pdf_version_id, $user_id, $client_id]);

// 最後に挿入されたIDの取得
$id = $db->lastInsertId();

// 要素数を数える
$query = "SELECT COUNT(*) FROM visits WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$count = $stmt->fetchColumn();

// 合計ポイント取得
$query = "SELECT SUM(point) FROM points WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$points = $stmt->fetchColumn();

