<?php include 'role_check.php';

if ($auth_check == 0 || $auth_check == 1){
    echo "У вас недостаточно прав для просмотра этой страницы.";
    exit;
}
?>
<!-- event edit -->
<form action="admin_menu.php" method="POST" enctype="multipart/form-data">
    <div class="event_form">
            <div class="event-group">
                <label class="event-label" for="eventName">Название:</label>
                <input class="event-input" type="text" id="eventName" name="eventName" required>
            </div>
            <div class="event-group">
                <label class="event-label" for="eventPrice">Цена:</label>
                <input class="event-input" type="text" id="eventPrice" name="eventPrice" required>
            </div>
            <div class="event-group">
                <label class="event-label" for="eventSeats">Кол-во мест:</label>
                <input class="event-input" type="text" id="eventSeats" name="eventSeats" required>
            </div>
            <div class="event-group">
                <label class="event-label" for="eventDate">Дата:</label>
                <input class="event-input" type="date" id="eventDate" name="eventDate" required>
            </div>
            <div class="event-group">
                <label class="event-label" for="eventImg">Изображение:</label>
                <input class="event-input" type="file" id="eventImg" name="eventImg" required>
            </div>

            <button class="event-button" type="submit">Добавить</button>
    </div>
</form>
<!-- admin edit -->
<form action="admin_menu.php" method="POST" enctype="multipart/form-data">
    <div class="event_form">
            <div class="event-group">
                <label class="event-label" for="userEmail">Email:</label>
                <input class="event-input" type="text" id="userEmail" name="userEmail" required>
            </div>
            <div class="event-group">
                <label class="event-label" for="roleUser">Роль user/manager:</label>
                <input class="event-input" type="text" id="roleUser" name="roleUser" required>
            </div>

            <button class="event-button" type="submit">Изменить роль</button>
    </div>
</form>



<?php
// Подключение к базе данных
$db = new PDO('sqlite:event_platform.db');

// Проверка наличия POST запроса
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['eventImg'])) {
    $eventName = $_POST['eventName'];
    $eventPrice = $_POST['eventPrice'];
    $eventSeats = $_POST['eventSeats'];
    $eventDate = $_POST['eventDate'];
    
    // Проверки на ввод данных
    if(is_numeric($eventPrice) && is_numeric($eventSeats)){
        // Обработка загрузки изображения
        $target_dir = "image/";
        $target_file = $target_dir . basename($_FILES["eventImg"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;
        
        // Проверка, является ли файл изображением
        $check = getimagesize($_FILES["eventImg"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "Файл не является изображением.";
            $uploadOk = 0;
        }
        
        // Проверка формата файла
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Только JPG, JPEG, и PNG файлы разрешены.";
            $uploadOk = 0;
        }
        
        // Попытка загрузки файла, если все проверки прошли
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["eventImg"]["tmp_name"], $target_file)) {
                // Подготовка SQL-запроса для вставки данных с названием загруженного файла
                $stmt = $db->prepare("INSERT INTO events (name, price, number_seats, date, img) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$eventName, $eventPrice, $eventSeats, $eventDate, basename($_FILES["eventImg"]["name"])]);
                header("Location: index.php");
            } else {
                echo "Произошла ошибка при загрузке файла.";
            }
        }
    } else {
        echo "Данные введены не верно.";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userEmail']) && isset($_POST['roleUser'])) {
    $user_email = $_POST['userEmail'];
    $role = $_POST['roleUser'];
    $role_id = ($role === "manager") ? 777 : 5;  // 777 для менеджера, 5 для пользователя

    $stmt = $db->prepare("UPDATE users SET role_id = ? WHERE email = ?");
    if ($stmt->execute([$role_id, $user_email])) {
        header("Location: index.php"); // Перенаправление обратно на страницу с событиями
        exit;
    }
}

?>