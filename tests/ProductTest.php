<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class ProductTest extends TestCase
{
    public function testIndexAction()
    {
        print_r("\nIndex Produit\n"); 
        $client = new Client();


        $email = 'nouveau@example.com';
        $email = 'nouveau';
        $password = '1234';
        $password = '1234';

      
        $response = $client->request('GET', 'http://localhost/product');


        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        print_r("Attendu: 200\n"); 
        print_r("Obtenu: " . $statusCode . "\n"); 
        print_r($log); 
    }

}

?>