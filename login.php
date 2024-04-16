<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="cred-body">
    <div class="auth-form">
        <h2>Авторизация</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label class="cred-label" for="email">Email:</label>
                <input class="cred-input" type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label class="cred-label" for="password">Пароль:</label>
                <input class="cred-input" type="password" id="password" name="password" required>
            </div>
            <button class="cred-button" type="submit">Войти</button>
        </form>
    </div>

<?php
include 'role_check.php';

if ($auth_check == 1 || $auth_check == 777){
    echo "Вы уже авторизованы.";
    exit;
}

$db = new PDO('sqlite:event_platform.db');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверка учетных данных
    $stmt = $db->prepare("SELECT id, password, token FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Генерация нового токена
        $token = bin2hex(random_bytes(16)); // Генерация безопасного токена

        // Сохранение токена в базе данных
        $update = $db->prepare("UPDATE users SET token = ? WHERE id = ?");
        $update->execute([$token, $user['id']]);

        // Установка токена в сессию или возврат клиенту
        $_SESSION['token'] = $token;
        // echo "Вы успешно авторизованы.";
        header("Location: index.php");
    } else {
        echo "Неверный логин или пароль.";
    }
}
?>


</body>
</html>
