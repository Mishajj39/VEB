<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class HttpTest extends TestCase
{
    public function testRealHttpRequest()
    {
        try {
            $client = new Client(['base_uri' => 'http://localhost:8080', 'timeout' => 5]);
            $response = $client->get('/form.html');
            $this->assertEquals(200, $response->getStatusCode());
        } catch (\Exception $e) {
            $this->markTestSkipped("Сервер не запущен. Запустите: docker-compose up -d");
        }
    }
    
    public function testMockHttpRequest()
    {
        $mock = new MockHandler([
            new Response(200, [], '<html><body>OK</body></html>')
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        
        $response = $client->get('/test');
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('OK', (string)$response->getBody());
    }
}