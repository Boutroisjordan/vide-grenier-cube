<?php declare(strict_types=1);
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testLoginAction()
    {

        $client = new Client();


        $email = 'test@example.com';
        $password = '1234';

        
        $response = $client->request('POST', 'http://localhost/login', [
            'form_params' => [
                'email' => $email,
                'password' => $password
            ]
        ]);


        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);

        
    }

    public function testRegisterAction()
    {

        // ...
        $client = new Client();


        $email = 'nouveau@example.com';
        $email = 'nouveau';
        $password = '1234';
        $password = '1234';

       
        $response = $client->request('POST', 'http://localhost/register', [
            'form_params' => [
                'email' => $email,
                "username" => $username,
                'password' => $password,
                'password-check' => $passwordCheck
            ]
        ]);

        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        var_dump($result); 
        print_r($log); 
    }
}

?>