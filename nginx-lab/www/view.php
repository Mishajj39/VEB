<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все подписки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📚 Все сохранённые подписки</h1>
        
        <?php if(file_exists("data.txt")): ?>
            <?php
            $lines = file("data.txt", FILE_IGNORE_NEW_LINES);
            if(count($lines) > 0):
            ?>
                <table border="1" cellpadding="10" cellspacing="0">
                    <tr style="background:#667eea; color:white;">
                        <th>#</th>
                        <th>ФИО</th>
                        <th>Email</th>
                        <th>Журнал</th>
                        <th>Срок</th>
                        <th>Эл. версия</th>
                        <th>Оплата</th>
                    </tr>
                    <?php foreach($lines as $index => $line): ?>
                        <?php 
                            $data = explode(";", $line);
                            while(count($data) < 6) $data[] = "";
                        ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($data[0]) ?></td>
                            <td><?= htmlspecialchars($data[1]) ?></td>
                            <td><?= htmlspecialchars($data[2]) ?></td>
                            <td><?= htmlspecialchars($data[3]) ?></td>
                            <td><?= htmlspecialchars($data[4]) ?></td>
                            <td><?= htmlspecialchars($data[5]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>📭 Данных нет</p>
            <?php endif; ?>
        <?php else: ?>
            <p>📭 Файл данных не найден. Пока нет ни одной подписки.</p>
        <?php endif; ?>
        
        <div class="nav-links">
            <a href="form.html">📝 Заполнить форму</a> |
            <a href="index.php">🏠 На главную</a>
        </div>
    </div>
</body>
</html>