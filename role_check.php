<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = new PDO('sqlite:event_platform.db');

// Проверка токена
$token = $_SESSION['token'] ?? ''; // Получение токена из сессии

// Проверка токена в базе данных
$stmt = $db->prepare("SELECT role_id FROM users WHERE token = ?");
$stmt->execute([$token]);
$role_id = $stmt->fetchColumn();


if ($role_id == "5") {
    // echo "Доступ к ресурсу разрешен.";
    $auth_check = 1;
} elseif($role_id == "777") {
    // echo "Доступ к ресурсу разрешен. admin";
    $auth_check = 777;
}
else{
    // echo "Доступ к ресурсу запрешен.";
    $auth_check = 0;
}
?>
