<?php
include 'role_check.php';

if ($auth_check == 0 || $auth_check == 1){
    echo "У вас недостаточно прав для просмотра этой страницы.";
    exit;
}

$db = new PDO('sqlite:event_platform.db');
$event_id = $_GET['event_id'] ?? null;

if ($event_id) {
    $stmt = $db->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Сбор данных из формы и их обновление в базе данных
    $name = $_POST['name'];
    $price = $_POST['price'];
    $number_seats = $_POST['number_seats'];
    $date = $_POST['date'];
    $updateStmt = $db->prepare("UPDATE events SET name = ?, price = ?, number_seats = ?, date = ? WHERE id = ?");
    $updateStmt->execute([$name, $price, $number_seats, $date, $event_id]);
    header("Location: users_eventsShow.php"); // Перенаправление обратно на страницу с событиями
    exit;
}

?>

<form action="" method="post">
    <label for="name">Event Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($event['name']); ?>">
    <label for="price">Price:</label>
    <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($event['price']); ?>">
    <label for="number_seats">Number of Seats:</label>
    <input type="text" id="number_seats" name="number_seats" value="<?php echo htmlspecialchars($event['number_seats']); ?>">
    <label for="date">Date:</label>
    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($event['date']); ?>">
    <button type="submit">Update Event</button>
</form>
