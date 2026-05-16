<?php

require 'vendor/autoload.php';

use App\RedisExample;
use App\ElasticExample;
use App\ClickhouseExample;

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>";
echo "<html><head><title>Лабораторная работа №6 - Погода</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
    h1 { color: #333; }
    h2 { color: #666; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .weather-card { background: #e8f4f8; padding: 15px; margin: 10px 0; border-radius: 8px; }
    pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
    .success { color: green; }
    .error { color: red; }
</style>";
echo "</head><body>";
echo "<div class='container'>";
echo "<h1>🌤️ Лабораторная работа №6</h1>";
echo "<p><strong>Тема:</strong> Изучение нереляционных баз данных (Redis, Elasticsearch, ClickHouse)</p>";
echo "<p><strong>Задание:</strong> Работа с погодными данными</p>";

echo "<h2>🔴 Redis - Кеширование погодных данных</h2>";

try {
    $redis = new RedisExample();
    
    $mockWeather = [
        'city' => 'Moscow',
        'temperature' => 18.5,
        'humidity' => 65,
        'condition' => 'Cloudy'
    ];
    
    $redis->setWeatherCache('Moscow', $mockWeather);
    $cachedWeather = $redis->getWeatherCache('Moscow');
    
    echo "<div class='weather-card'>";
    echo "<strong>📦 Данные из Redis (TTL: 1 час):</strong><br>";
    if ($cachedWeather) {
        echo "Город: {$cachedWeather['city']}<br>";
        echo "Температура: {$cachedWeather['temperature']}°C<br>";
        echo "Влажность: {$cachedWeather['humidity']}%<br>";
        echo "Состояние: {$cachedWeather['condition']}<br>";
    }
    echo "</div>";
    
    $redis->setValue('test_key', 'Redis работает!');
    echo "<p class='success'>✅ Redis тест: " . $redis->getValue('test_key') . "</p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Redis ошибка: " . $e->getMessage() . "</p>";
}

echo "<h2>🔍 Elasticsearch - Индексация погодных статей</h2>";

try {
    $elastic = new ElasticExample();
    
    $weatherDoc = [
        'title' => 'Прогноз погоды в Москве',
        'city' => 'Moscow',
        'forecast' => 'Солнечно, до 20°C',
        'date' => date('Y-m-d')
    ];
    
    $result = $elastic->indexDocument('weather_forecasts', 1, $weatherDoc);
    echo "<div class='weather-card'>";
    echo "<strong>📄 Индексация документа:</strong><br>";
    echo "<pre>" . print_r($result, true) . "</pre>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Elasticsearch ошибка: " . $e->getMessage() . "</p>";
}

echo "<h2>⚡ ClickHouse - Основное хранилище погодных данных</h2>";

try {
    $clickhouse = new ClickhouseExample();
    
    echo "<div class='weather-card'>";
    echo "<strong>📊 Создание таблицы:</strong><br>";
    $createResult = $clickhouse->createWeatherTable();
    echo "Таблица weather_data создана/существует<br>";
    echo "</div>";
    
    echo "<div class='weather-card'>";
    echo "<strong>💾 Вставка данных:</strong><br>";
    
    $weatherSamples = [
        ['Moscow', ['temperature' => 18.5, 'humidity' => 65, 'pressure' => 1013, 'wind_speed' => 3.2, 'condition' => 'Cloudy']],
        ['Moscow', ['temperature' => 19.0, 'humidity' => 63, 'pressure' => 1014, 'wind_speed' => 2.8, 'condition' => 'Sunny']],
        ['Saint Petersburg', ['temperature' => 15.2, 'humidity' => 78, 'pressure' => 1011, 'wind_speed' => 4.5, 'condition' => 'Rainy']],
        ['Saint Petersburg', ['temperature' => 14.8, 'humidity' => 80, 'pressure' => 1012, 'wind_speed' => 4.0, 'condition' => 'Rainy']],
        ['Kazan', ['temperature' => 20.1, 'humidity' => 55, 'pressure' => 1015, 'wind_speed' => 2.5, 'condition' => 'Sunny']],
    ];
    
    foreach ($weatherSamples as $sample) {
        $clickhouse->insertWeather($sample[0], $sample[1]);
        echo "✓ Добавлена погода для {$sample[0]}<br>";
    }
    echo "</div>";
    
    echo "<div class='weather-card'>";
    echo "<strong>🌍 Текущая погода по городам:</strong><br>";
    
    $cities = ['Moscow', 'Saint Petersburg', 'Kazan'];
    foreach ($cities as $city) {
        $current = $clickhouse->getCurrentWeather($city);
        echo "<pre>" . $city . ":\n" . $current . "</pre>";
    }
    echo "</div>";
    
    echo "<div class='weather-card'>";
    echo "<strong>📈 Статистика погоды:</strong><br>";
    foreach ($cities as $city) {
        $stats = $clickhouse->getWeatherStats($city);
        echo "<pre>" . $city . ":\n" . $stats . "</pre>";
    }
    echo "</div>";
    
    echo "<div class='weather-card'>";
    echo "<strong>🏙️ Список городов в базе:</strong><br>";
    $allCities = $clickhouse->getAllCities();
    echo "<pre>" . $allCities . "</pre>";
    echo "</div>";
    
    echo "<div class='weather-card'>";
    echo "<strong>🔧 Пример произвольного запроса:</strong><br>";
    $customQuery = "SELECT city, count(*) as readings_count FROM weather_data GROUP BY city";
    $result = $clickhouse->query($customQuery);
    echo "<pre>" . $result . "</pre>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ ClickHouse ошибка: " . $e->getMessage() . "</p>";
}

echo "<h2>📝 Вывод</h2>";
echo "<ul>";
echo "<li><strong>Redis</strong> - используется для кеширования погодных данных (TTL 1 час)</li>";
echo "<li><strong>Elasticsearch</strong> - для индексации и поиска прогнозов погоды</li>";
echo "<li><strong>ClickHouse</strong> - основное хранилище для аналитики погодных данных</li>";
echo "</ul>";

echo "</div></body></html>";