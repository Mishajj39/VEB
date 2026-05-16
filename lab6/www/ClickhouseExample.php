<?php

namespace App;

use App\Helpers\ClientFactory;

class ClickhouseExample
{
    private $client;

    public function __construct()
    {
        $this->client = ClientFactory::make('http://clickhouse:8123/');
    }

    public function query($sql)
    {
        $response = $this->client->post('', [
            'body' => $sql,
            'headers' => [
                'Content-Type' => 'text/plain; charset=utf-8',
            ]
        ]);
        return $response->getBody()->getContents();
    }

    // Создание таблицы для погодных данных
    public function createWeatherTable()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS weather_data (
            city String,
            temperature Float32,
            humidity UInt8,
            pressure UInt16,
            wind_speed Float32,
            condition String,
            recorded_at DateTime
        ) ENGINE = MergeTree()
        ORDER BY (city, recorded_at)
        ";
        
        return $this->query($sql);
    }

    // Вставка данных о погоде
    public function insertWeather($city, $data)
    {
        $sql = sprintf(
            "INSERT INTO weather_data (city, temperature, humidity, pressure, wind_speed, condition, recorded_at) 
             VALUES ('%s', %f, %d, %d, %f, '%s', now())",
            $city,
            $data['temperature'],
            $data['humidity'],
            $data['pressure'],
            $data['wind_speed'],
            $data['condition']
        );
        
        return $this->query($sql);
    }

    // Получение текущей погоды для города
    public function getCurrentWeather($city)
    {
        $sql = "
        SELECT * FROM weather_data 
        WHERE city = '{$city}' 
        ORDER BY recorded_at DESC 
        LIMIT 1
        ";
        
        return $this->query($sql);
    }

    // Получение истории погоды для города
    public function getWeatherHistory($city, $hours = 24)
    {
        $sql = "
        SELECT * FROM weather_data 
        WHERE city = '{$city}' 
        AND recorded_at >= now() - INTERVAL {$hours} HOUR
        ORDER BY recorded_at DESC
        ";
        
        return $this->query($sql);
    }

    // Статистика по городу
    public function getWeatherStats($city)
    {
        $sql = "
        SELECT 
            city,
            avg(temperature) as avg_temp,
            max(temperature) as max_temp,
            min(temperature) as min_temp,
            avg(humidity) as avg_humidity,
            avg(pressure) as avg_pressure
        FROM weather_data 
        WHERE city = '{$city}'
        GROUP BY city
        ";
        
        return $this->query($sql);
    }

    // Получение всех городов
    public function getAllCities()
    {
        $sql = "
        SELECT DISTINCT city FROM weather_data ORDER BY city
        ";
        
        return $this->query($sql);
    }
}