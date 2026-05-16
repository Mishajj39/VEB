<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная — Данные подписки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📋 Ваша подписка</h1>

        <?php if(isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
            <div class="errors">
                <h3 style="color:red; margin-bottom:10px;">❌ Ошибки при заполнении формы:</h3>
                <ul style="color:red;">
                    <?php foreach($_SESSION['errors'] as $error): ?>
                        <li>❌ <?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['user_data'])): ?>
            <div class="data-card">
                <h2>✅ Данные из сессии (текущая подписка):</h2>
                <ul>
                    <li><strong>👤 Имя:</strong> <?= $_SESSION['user_data']['fullname'] ?></li>
                    <li><strong>📧 Email:</strong> <?= $_SESSION['user_data']['email'] ?></li>
                    <li><strong>📖 Журнал:</strong> <?= $_SESSION['user_data']['magazine'] ?></li>
                    <li><strong>⏱️ Срок подписки:</strong> <?= $_SESSION['user_data']['duration'] ?></li>
                    <li><strong>📱 Электронная версия:</strong> <?= $_SESSION['user_data']['digital'] ?></li>
                    <li><strong>💳 Способ оплаты:</strong> <?= $_SESSION['user_data']['payment'] ?></li>
                </ul>
            </div>
        <?php else: ?>
            <p>📭 Данных в сессии пока нет. Заполните форму.</p>
        <?php endif; ?>

        <?php if(isset($_COOKIE['last_fullname']) && $_COOKIE['last_fullname'] != ''): ?>
            <div class="cookie-card">
                <h3>🍪 Последняя подписка (из cookies):</h3>
                <ul>
                    <li><strong>👤 Имя:</strong> <?= htmlspecialchars($_COOKIE['last_fullname']) ?></li>
                    <li><strong>📧 Email:</strong> <?= htmlspecialchars($_COOKIE['last_email']) ?></li>
                    <li><strong>📖 Журнал:</strong> <?= htmlspecialchars($_COOKIE['last_magazine']) ?></li>
                    <li><strong>⏱️ Срок подписки:</strong> <?= htmlspecialchars($_COOKIE['last_duration']) ?></li>
                    <li><strong>💳 Способ оплаты:</strong> <?= htmlspecialchars($_COOKIE['last_payment']) ?></li>
                    <li><strong>📱 Электронная версия:</strong> <?= htmlspecialchars($_COOKIE['last_digital']) ?></li>
                </ul>
                <small>🍪 Данные из cookies хранятся 30 дней на вашем компьютере</small>
            </div>
        <?php else: ?>
            <div class="cookie-card">
                <p>🍪 Данных в cookies пока нет. После оформления подписки они здесь появятся.</p>
            </div>
        <?php endif; ?>

        <div class="nav-links">
            <a href="form.html">📝 Заполнить форму</a> |
            <a href="view.php">📋 Посмотреть все данные</a>
        </div>
    </div>
</body>
</html>