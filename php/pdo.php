<?php

try {
    // データベース接続
    $db = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // SELECT文（クライアント情報取得）
    $stmt = $db->prepare("SELECT * FROM clients WHERE id=?");
    $stmt->execute([$client_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo '接続失敗' . $e->getMessage();
    exit();
}

// SELECT文（ユーザー情報取得、降順）
$stmt = $db->prepare("SELECT * FROM users WHERE shop_id=? ORDER BY created_at DESC");
$stmt->execute([$shop_id]);
$data = $stmt->fetchAll();

// INSERT文（クライアント情報追加）
$sql = "INSERT INTO clients (id, name, tel, ref) VALUES (:id, :name, :tel, :ref)";
$stmt = $db->prepare($sql);
$params = array(':id' => $id, ':name' => $name, ':tel' => $tel, ':ref' => $ref);
$stmt->execute($params);

// DELETE文（一時テーブルからメールアドレスによる削除）
$stmt = $db->prepare('DELETE FROM temp_new WHERE email = :email');
$stmt->execute(array(':email' => $email));

// UPDATE文（クライアント情報更新）
$stmt = $db->prepare('UPDATE clients SET name=:name, manager=:manager, tel=:tel, subdomain=:subdomain, login_id=:login_id, login_pw=:login_pw, status=:status, admin_memo=:admin_memo, staff_memo=:staff_memo WHERE id = :id');
$stmt->execute(array(':id' => $id, ':name' => $name, ':manager' => $manager, ':tel' => $tel, ':subdomain' => $subdomain, ':login_id' => $login_id, ':login_pw' => $login_pw, ':status' => $status, ':admin_memo' => $admin_memo, ':staff_memo' => $staff_memo));

// TRUNCATE文（コンテンツテーブルの初期化）
$sql = "TRUNCATE TABLE contents";
$stmt = $db->prepare($sql);
$stmt->execute();

// INSERT文（PDFアクセス情報追加）
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

