<?php
include 'role_check.php';

if ($auth_check == 0){
    echo "У вас недостаточно прав для просмотра этой страницы. Авторизуйтесь.";
    exit;
}

$db = new PDO('sqlite:event_platform.db');

// Проверка токена и получение user_id
$token = $_SESSION['token'] ?? '';


// Запрос в базу данных для получения user_id по токену
$stmt = $db->prepare("SELECT id FROM users WHERE token = ?");
$stmt->execute([$token]);
$user_id = $stmt->fetchColumn();

// Проверяем, что пришел POST-запрос с идентификатором события
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];

    // Проверка на существование уже такой записи в event_records
    $checkStmt = $db->prepare("SELECT COUNT(*) FROM event_records WHERE user_id = ? AND event_id = ?");
    $checkStmt->execute([$user_id, $event_id]);
    $exists = $checkStmt->fetchColumn() > 0;

    if ($exists) {
        echo "Вы уже записаны на это мероприятие!";
        exit; // Если запись существует, завершаем скрипт
    }else {

    // Подготовка запроса на добавление записи в event_records
    $stmt = $db->prepare("INSERT INTO event_records (user_id, event_id) VALUES (?, ?)");
    $result = $stmt->execute([$user_id, $event_id]);

    echo "Вы успешно записаны на мероприятие!";
    }
}
exit;
?>
