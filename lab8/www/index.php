<?php
require 'db.php';
require 'Subscription.php';

$subscription = new Subscription($pdo);
$all = $subscription->getAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Список подписок</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #4CAF50; color: white; }
    </style>
</head>
<body>
    <h2>Список подписок</h2>
    <a href="form.html">+ Новая подписка</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Срок</th>
                <th>Журнал</th>
                <th>Эл. версия</th>
                <th>Оплата</th>
                <th>Дата</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($all as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['subscription_term']) ?></td>
                <td><?= htmlspecialchars($row['magazine']) ?></td>
                <td><?= $row['digital_version'] ? '✅' : '❌' ?></td>
                <td><?= htmlspecialchars($row['payment_format']) ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>