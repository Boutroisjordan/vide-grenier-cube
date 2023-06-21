<?php
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testIndexAction()
    {
        $client = new Client();


        $email = 'nouveau@example.com';
        $email = 'nouveau';
        $password = '1234';
        $password = '1234';

      
        $response = $client->request('GET', 'http://localhost/product');


        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        var_dump($result); 
        print_r($log); 
    }

}

?>