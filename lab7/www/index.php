<?php
require_once 'Student.php';
require_once 'QueueManager.php';

$studentModel = new Student();
$queue = new QueueManager();
$students = $studentModel->getAll();
$stats = $queue->getStats();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab7: Асинхронная обработка данных</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 { color: #333; margin-bottom: 10px; }
        h2 { color: #555; margin-bottom: 20px; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-number { font-size: 48px; font-weight: bold; margin: 10px 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; color: #333; }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .info { background: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 5px; margin-top: 20px; }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            background: #ff6b35;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🧑‍💻 Лабораторная работа №7</h1>
            <p>Асинхронная обработка данных через <span class="badge">Файловую очередь (Kafka эмуляция)</span></p>
            <p><strong>Вариант:</strong> Нечётный (Эмуляция Kafka)</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div>📊 Сообщений в очереди</div>
                <div class="stat-number"><?php echo $stats['main_queue_size']; ?></div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div>⚠️ Ошибок в очереди</div>
                <div class="stat-number"><?php echo $stats['error_queue_size']; ?></div>
            </div>
        </div>
        
        <div class="card">
            <h2>📝 Регистрация студента</h2>
            <div id="message"></div>
            <form id="registerForm">
                <div class="form-group">
                    <label for="name">Имя:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit">📤 Отправить в очередь</button>
            </form>
        </div>
        
        <div class="card">
            <h2>📋 Зарегистрированные студенты</h2>
            <?php if (empty($students)): ?>
                <p>Нет зарегистрированных студентов</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Имя</th><th>Email</th><th>Дата</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr><td><?php echo $student['id']; ?></td><td><?php echo htmlspecialchars($student['name']); ?></td><td><?php echo htmlspecialchars($student['email']); ?></td><td><?php echo $student['created_at']; ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData();
            formData.append('name', document.getElementById('name').value);
            formData.append('email', document.getElementById('email').value);
            
            try {
                const response = await fetch('send.php', { method: 'POST', body: formData });
                const result = await response.json();
                const messageDiv = document.getElementById('message');
                if (result.success) {
                    messageDiv.innerHTML = `<div class="alert alert-success">✅ ${result.message}</div>`;
                    document.getElementById('registerForm').reset();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-error">❌ ${result.error}</div>`;
                }
            } catch (error) {
                document.getElementById('message').innerHTML = `<div class="alert alert-error">❌ Error: ${error.message}</div>`;
            }
        });
    </script>
</body>
</html>