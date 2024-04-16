<?php
// Подключение к базе данных SQLite
$db = new PDO('sqlite:event_platform.db');

// Запрос к базе данных для получения событий
$query = "SELECT * FROM events";
$events = $db->query($query);

// Преобразование результатов запроса в массив
$eventsArray = $events->fetchAll(PDO::FETCH_ASSOC);

// Обращение массива событий для вывода их в обратном порядке
$eventsArray = array_reverse($eventsArray);

// Счетчик для контроля количества событий в блоке
$counter = 0;

echo "<div class='events-section'>"; // Начало секции событий

foreach ($eventsArray as $event) {
    if ($counter % 2 == 0 && $counter != 0) {
        echo "</div><div class='events-section'>"; // Закрытие и открытие нового блока секции после каждых двух событий
    }

    // Вывод информации о событии
    echo "<div class='event'>";
    echo "<h2>" . htmlspecialchars($event['name']) . "</h2>";
    echo "<p class='price'>Price: " . htmlspecialchars($event['price']) . "</p>";
    echo "<p>Seats available: " . htmlspecialchars($event['number_seats']) . "</p>";
    echo "<p class='event-date' >Date: " . htmlspecialchars($event['date']) . "</p>";
    echo "<form action='event_write.php' method='post'>";
    echo "<input type='hidden' name='event_id' value='" . htmlspecialchars($event['id']) . "'>";
    echo "<button type='submit'>Записаться</button>";
    echo "</form>";
    echo "<img class='event-img' src='image/" . rawurlencode(htmlspecialchars($event['img'])) . "'>";
    echo "</div>";

    $counter++; // Увеличение счетчика
}

echo "</div>"; // Закрытие последней секции событий
?>
