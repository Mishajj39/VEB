<?php
require 'db.php';
require 'Subscription.php';

$subscription = new Subscription($pdo);

$sort = $_GET['sort'] ?? 'created_at DESC';
$filter = $_GET['filter'] ?? 'all';

$all = $subscription->getAll($sort);
$totalCount = $subscription->getCount();

if ($filter == 'digital') {
    $filtered = array_filter($all, fn($item) => $item['digital_version'] == 1);
} else {
    $filtered = $all;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список подписок</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .stats { background: #e7f3ff; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .filters { margin: 20px 0; }
        .filters a, .sort a { margin-right: 10px; text-decoration: none; padding: 5px 10px; background: #f0f0f0; border-radius: 3px; }
        .filters a:hover, .sort a:hover { background: #ddd; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 3px; }
        .btn:hover { background: #45a049; }
    </style>
</head>
<body>
    <h2>📋 Список оформленных подписок</h2>
    
    <div class="stats">
        <strong>📊 Статистика:</strong> Всего подписок: <?= $totalCount ?> | 
        Электронных версий: <?= count(array_filter($all, fn($item) => $item['digital_version'] == 1)) ?> |
        Печатных версий: <?= count(array_filter($all, fn($item) => $item['digital_version'] == 0)) ?>
    </div>

    <div class="sort">
        <strong>📅 Сортировка:</strong>
        <a href="?sort=created_at DESC">Новые сначала</a>
        <a href="?sort=created_at ASC">Старые сначала</a>
        <a href="?sort=name ASC">По имени (А-Я)</a>
        <a href="?sort=name DESC">По имени (Я-А)</a>
    </div>

    <div class="filters">
        <strong>🔍 Фильтр:</strong>
        <a href="?filter=all">Все подписки</a>
        <a href="?filter=digital">Только с электронной версией</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Срок подписки</th>
                <th>Журнал</th>
                <th>Эл. версия</th>
                <th>Формат оплаты</th>
                <th>Дата оформления</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($filtered)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Нет данных</td>
                </tr>
            <?php else: ?>
                <?php foreach($filtered as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['subscription_term']) ?></td>
                        <td><?= htmlspecialchars($row['magazine']) ?></td>
                        <td><?= $row['digital_version'] ? '✅ Да' : '❌ Нет' ?></td>
                        <td><?= htmlspecialchars($row['payment_format']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="form.html" class="btn">➕ Оформить новую подписку</a>
</body>
</html>