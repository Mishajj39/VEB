<?php
require_once __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;

class ApiClient {
    private Client $client;

    public function __construct() {
        $this->client = new Client([
            'timeout' => 10,
            'verify' => false  // Для локальной разработки (опционально)
        ]);
    }

    public function request(string $url, array $params = []): array {
        try {
            $response = $this->client->get($url, [
                'query' => $params
            ]);
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['error' => 'Ошибка парсинга JSON: ' . json_last_error_msg()];
            }
            
            return $data;
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return ['error' => 'Не удалось подключиться к API. Проверьте интернет-соединение.'];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            return ['error' => "API вернул ошибку {$statusCode}. Попробуйте позже."];
        } catch (\Exception $e) {
            return ['error' => 'Ошибка: ' . $e->getMessage()];
        }
    }
}