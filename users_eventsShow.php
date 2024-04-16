<?php 
include 'role_check.php';  // Этот файл предполагается для проверки прав доступа

if ($auth_check == 0 || $auth_check == 1){
    echo "У вас недостаточно прав для просмотра этой страницы.";
    exit;
}
?>

<header>
    <h1>События, на которые записаны пользователи</h1>
</header>
<link rel="stylesheet" href="style.css">
<?php
// Подключение к базе данных SQLite
$db = new PDO('sqlite:event_platform.db');

// Запрос к базе данных для получения всех событий и пользователей, записанных на них
$query = "SELECT e.id AS event_id, e.name, e.price, e.number_seats, e.date, e.img, 
          u.name AS user_name, u.surname, u.email, er.id AS record_id
          FROM events e 
          JOIN event_records er ON e.id = er.event_id 
          JOIN users u ON u.id = er.user_id";

$events = $db->query($query);  // Используем query, так как не передаем параметры

// Преобразование результатов запроса в массив
$eventsArray = $events->fetchAll(PDO::FETCH_ASSOC);

echo "<div class='events-section'>"; // Начало секции событий

foreach ($eventsArray as $event) {
    echo "<div class='event'>";
    echo "<h2>" . htmlspecialchars($event['name']) . "</h2>";
    echo "<p class='price'>Price: " . htmlspecialchars($event['price']) . "</p>";
    echo "<p>Seats available: " . htmlspecialchars($event['number_seats']) . "</p>";
    echo "<p class='event-date'>Date: " . htmlspecialchars($event['date']) . "</p>";
    echo "<p>Registered by: " . htmlspecialchars($event['user_name']) . " " . htmlspecialchars($event['surname']) . " (" . htmlspecialchars($event['email']) . ")</p>";
    echo "<p>Record ID: " . htmlspecialchars($event['record_id']) . "</p>";  // Выводим ID записи
    echo "<img class='event-img' src='image/" . rawurlencode(htmlspecialchars($event['img'])) . "'>";
    echo "<form action='edit_event.php' method='get'>";
    echo "<input type='hidden' name='event_id' value='" . htmlspecialchars($event['event_id']) . "'>";
    echo "<button type='submit'>Редактировать</button>";
    echo "</form>";
    echo "</div>";
}

echo "</div>"; // Закрытие последней секции событий
?>
