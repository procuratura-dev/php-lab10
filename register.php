<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css"> <!-- Подключение стилей CSS -->
</head>
<body class="cred-body">
    <div class="registration-form">
        <h2>Регистрация</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label class="cred-label" for="firstName">Имя:</label>
                <input class="cred-input" type="text" id="firstName" name="firstName" required>
            </div>
            <div class="form-group">
                <label class="cred-label" for="lastName">Фамилия:</label>
                <input class="cred-input" type="text" id="lastName" name="lastName" required>
            </div>
            <div class="form-group">
                <label class="cred-label" for="email">Email:</label>
                <input class="cred-input"type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label class="cred-label" for="password">Пароль:</label>
                <input class="cred-input" type="password" id="password" name="password" required>
            </div>
            <button class="cred-button" type="submit">Зарегистрироваться</button>
        </form>
    </div>

<?php
include 'role_check.php';


if ($auth_check == 1 || $auth_check == 777){
    echo "Вы уже авторизованы.";
    exit;
}
// Подключение к базе данных
$db = new PDO('sqlite:event_platform.db');

// Проверка наличия POST запроса
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['firstName'];
    $surname = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_id = 5;

    // Хеширование пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Подготовка SQL-запроса для вставки данных
    $stmt = $db->prepare("INSERT INTO users (name, surname, email, role_id, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $surname, $email, $role_id, $hashed_password]);

    echo "Пользователь зарегистрирован.";
    header("Location: login.php");
}
?>




</body>
</html>
