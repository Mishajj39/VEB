<?php
session_start();
require_once 'ApiClient.php';
require_once 'UserInfo.php';

// Настройки кеширования (штрафное задание)
$cacheFile = __DIR__ . '/api_cache.json';
$cacheTtl = 300; 

// Функция для получения данных с кешированием
function getApiDataWithCache($url, $cacheFile, $cacheTtl) {
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTtl) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached && !isset($cached['error'])) {
            $_SESSION['api_data'] = $cached;
            $_SESSION['from_cache'] = true;
            return $cached;
        }
    }
    
    $api = new ApiClient();
    $url = 'https://api.spaceflightnewsapi.net/v4/articles/';
    $apiData = $api->request($url, ['limit' => 10]);
    
    if (!isset($apiData['error'])) {
        file_put_contents($cacheFile, json_encode($apiData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $_SESSION['from_cache'] = false;
    }
    
    $_SESSION['api_data'] = $apiData;
    return $apiData;
}

// Получаем данные из API (Новости космонавтики)
$apiUrl = 'https://api.spaceflightnewsapi.net/v4/articles/';
$apiData = getApiDataWithCache($apiUrl, $cacheFile, $cacheTtl);

$userInfo = UserInfo::getInfo();

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
          
if ($isAjax && isset($_GET['refresh'])) {
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
    }
    $newData = getApiDataWithCache($apiUrl, $cacheFile, $cacheTtl);
    header('Content-Type: application/json');
    echo json_encode($newData);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новости космонавтики - Подписка на журнал</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            color: #e0e0e0;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #00d4ff;
            text-shadow: 0 0 10px rgba(0,212,255,0.5);
        }
        
        .card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        
        .subscription-form {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #00d4ff;
            font-weight: bold;
        }
        
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(255,255,255,0.2);
            background: rgba(0,0,0,0.3);
            color: #fff;
            border-radius: 5px;
            font-size: 16px;
        }
        
        button, .btn-refresh {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        button:hover, .btn-refresh:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
        }
        
        .news-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .news-item {
            background: rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 15px;
            transition: all 0.3s;
        }
        
        .news-item:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.12);
        }
        
        .news-item h3 {
            color: #00d4ff;
            margin-bottom: 10px;
            font-size: 1.1em;
        }
        
        .news-item .summary {
            font-size: 0.9em;
            line-height: 1.4;
            margin-bottom: 10px;
            color: #ccc;
        }
        
        .news-item .date {
            font-size: 0.8em;
            color: #888;
        }
        
        .news-item a {
            color: #00d4ff;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .info-item {
            background: rgba(0,212,255,0.1);
            padding: 10px;
            border-radius: 5px;
        }
        
        .info-label {
            font-weight: bold;
            color: #00d4ff;
            font-size: 0.85em;
        }
        
        .info-value {
            font-size: 0.9em;
            word-break: break-all;
        }
        
        .error-message {
            background: rgba(255,0,0,0.2);
            border: 1px solid rgba(255,0,0,0.5);
            padding: 15px;
            border-radius: 10px;
            color: #ff6b6b;
            margin-bottom: 20px;
        }
        
        .success-message {
            background: rgba(0,255,0,0.1);
            border: 1px solid rgba(0,255,0,0.3);
            padding: 15px;
            border-radius: 10px;
            color: #6bff6b;
            margin-bottom: 20px;
        }
        
        .cache-badge {
            display: inline-block;
            background: #764ba2;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8em;
            margin-bottom: 15px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-left: 10px;
        }
        
        .btn-refresh {
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Журнал «Новости космонавтики»</h1>
        
        <?php if (isset($_SESSION['subscription_success'])): ?>
            <div class="success-message">
                ✅ <?php echo htmlspecialchars($_SESSION['subscription_success']); ?>
                <?php unset($_SESSION['subscription_success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['subscription_error'])): ?>
            <div class="error-message">
                ❌ <?php echo htmlspecialchars($_SESSION['subscription_error']); ?>
                <?php unset($_SESSION['subscription_error']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Форма подписки -->
        <div class="card">
            <h2>📧 Оформление подписки</h2>
            <form class="subscription-form" action="save.php" method="POST">
                <div class="form-group">
                    <label for="name">Имя:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="frequency">Частота рассылки:</label>
                    <select id="frequency" name="frequency">
                        <option value="daily">Ежедневно</option>
                        <option value="weekly">Еженедельно</option>
                        <option value="monthly">Ежемесячно</option>
                    </select>
                </div>
                <button type="submit">Подписаться</button>
            </form>
        </div>
        
        <!-- Информация о пользователе -->
        <div class="card">
            <h2>👤 Информация о пользователе</h2>
            <div class="info-grid">
                <?php foreach ($userInfo as $key => $val): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $key))); ?>:
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($val); ?></div>
                    </div>
                <?php endforeach; ?>
                <div class="info-item">
                    <div class="info-label">Браузер:</div>
                    <div class="info-value"><?php echo UserInfo::getBrowserInfo(); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Новости из API -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>📰 Последние новости космонавтики</h2>
                <button class="btn-refresh" onclick="refreshNews()" id="refreshBtn">
                    🔄 Обновить данные
                </button>
            </div>
            
            <?php if (isset($_SESSION['from_cache']) && $_SESSION['from_cache']): ?>
                <div class="cache-badge">💾 Данные из кеша (обновлены менее 5 минут назад)</div>
            <?php endif; ?>
            
            <div id="news-container">
                <?php if (isset($apiData['error'])): ?>
                    <div class="error-message">
                        ⚠️ <?php echo htmlspecialchars($apiData['error']); ?>
                    </div>
                <?php elseif (isset($apiData['results']) && count($apiData['results']) > 0): ?>
                    <div class="news-list">
                        <?php foreach (array_slice($apiData['results'], 0, 9) as $article): ?>
                            <div class="news-item">
                                <h3><?php echo htmlspecialchars($article['title'] ?? 'Без названия'); ?></h3>
                                <div class="summary">
                                    <?php 
                                    $summary = $article['summary'] ?? '';
                                    echo htmlspecialchars(mb_substr($summary, 0, 150)) . (mb_strlen($summary) > 150 ? '...' : '');
                                    ?>
                                </div>
                                <div class="date">
                                    📅 <?php echo date('d.m.Y H:i', strtotime($article['published_at'] ?? 'now')); ?>
                                </div>
                                <a href="<?php echo htmlspecialchars($article['url'] ?? '#'); ?>" target="_blank">Читать далее →</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="error-message">Нет доступных новостей</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        async function refreshNews() {
            const btn = document.getElementById('refreshBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '🔄 Загрузка... <span class="loading"></span>';
            btn.disabled = true;
            
            try {
                const response = await fetch('index.php?refresh=1', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Ошибка сети');
                }
                
                const data = await response.json();
                
                if (data.error) {
                    document.getElementById('news-container').innerHTML = 
                        '<div class="error-message">⚠️ ' + data.error + '</div>';
                } else if (data.results && data.results.length > 0) {
                    let html = '<div class="news-list">';
                    data.results.slice(0, 9).forEach(article => {
                        const summary = article.summary || '';
                        const shortSummary = summary.length > 150 ? summary.substring(0, 150) + '...' : summary;
                        html += `
                            <div class="news-item">
                                <h3>${escapeHtml(article.title || 'Без названия')}</h3>
                                <div class="summary">${escapeHtml(shortSummary)}</div>
                                <div class="date">📅 ${new Date(article.published_at).toLocaleString('ru-RU')}</div>
                                <a href="${escapeHtml(article.url || '#')}" target="_blank">Читать далее →</a>
                            </div>
                        `;
                    });
                    html += '</div>';
                    document.getElementById('news-container').innerHTML = html;
                    
                    const successMsg = document.createElement('div');
                    successMsg.className = 'success-message';
                    successMsg.innerHTML = '✅ Данные успешно обновлены!';
                    successMsg.style.marginTop = '15px';
                    document.getElementById('news-container').appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 3000);
                } else {
                    document.getElementById('news-container').innerHTML = 
                        '<div class="error-message">Нет доступных новостей</div>';
                }
            } catch (error) {
                document.getElementById('news-container').innerHTML = 
                    '<div class="error-message">⚠️ Ошибка при обновлении данных: ' + error.message + '</div>';
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>