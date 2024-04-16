<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Спортивные мероприятия города</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'role_check.php'; ?>
    <header>
    <h1>Городские Спортивные События</h1>
        <nav>
            <?php
            // Определение массива с элементами меню
            if($auth_check == 0){
            $menu_items = [
                'register.php' => 'Регистрация',
                'login.php' => 'Войти',
                'index.php' => 'Мероприятия',
                'my_eventsShow.php' => 'Мои мероприятия'
            ];
            }
            elseif($auth_check == 1){
            $menu_items = [
                'exit.php' => 'Выйти',
                'index.php' => 'Мероприятия',
                'my_eventsShow.php' => 'Мои мероприятия'
                ];
            }
            elseif($auth_check == 777){
                $menu_items = [
                    'exit.php' => 'Выйти',
                    'index.php' => 'Мероприятия',
                    'my_eventsShow.php' => 'Мои мероприятия',
                    'users_eventsShow.php' => 'Зарегистрированные на мероприятия'
                    ];
            }
            // Вывод элементов меню
            echo '<ul>';
            foreach ($menu_items as $link => $title) {
                echo "<li><a href='{$link}'>{$title}</a></li>";
            }
            echo '</ul>';
            ?>
        </nav>
    </header>
<?php
    if($auth_check == 777){
    echo "<div>";
        include 'admin_menu.php';
    echo "</div>";
    }
?>
    <?php include 'events_script.php'; ?>

    <footer>
        <p>Контакты и информация о правах.</p>
    </footer>
</body>
</html>
