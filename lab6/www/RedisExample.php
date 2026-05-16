<?php

namespace App;

use Predis\Client as PredisClient;

class RedisExample
{
    private $client;

    public function __construct()
    {
        $this->client = new PredisClient('tcp://redis:6379');
    }

    public function setValue($key, $value)
    {
        $this->client->set($key, $value);
    }

    public function getValue($key)
    {
        return $this->client->get($key);
    }

    public function setWeatherCache($city, $weatherData)
    {
        $this->client->setex("weather:{$city}", 3600, json_encode($weatherData));
    }

    public function getWeatherCache($city)
    {
        $data = $this->client->get("weather:{$city}");
        return $data ? json_decode($data, true) : null;
    }
}