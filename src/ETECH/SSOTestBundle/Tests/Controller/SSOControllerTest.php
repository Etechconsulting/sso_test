<?php

namespace ETECH\SSOTestBundle\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SSOControllerTest extends WebTestCase
{
    /*
    * Test generation token temporaire
    */
    public function testGeneratetTokenTemp(){
        $client = static::createClient();
        $data = '{
            "token_session": "8b0d9c93a3545d0d99f6486208dd3659ec802ee1",
            "login": "santevet"
        }';
        $crawler = $client->request(
            'PUT', '/generate/token/temp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        ); 
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token_temp', $body);
        $this->assertArrayHasKey('expiration', $body);
        $this->assertNotEquals(null,$body['token_temp']);
        $this->assertNotEquals(null,$body['expiration']);
        return $body;
    }
    /*
    * Test login apres generation token
    */
    public function testLoginToken(){
        
        $client = static::createClient();
        $data = '{
            "token_session": "8b0d9c93a3545d0d99f6486208dd3659ec802ee1",
            "login": "santevet"
        }';
        $crawler = $client->request(
            'PUT', '/generate/token/temp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        ); 
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token_temp', $body);
        $this->assertArrayHasKey('expiration', $body);
        $this->assertNotEquals(null,$body['token_temp']);
        $this->assertNotEquals(null,$body['expiration']);
        
        $_data = $body;
        
        $data = '{
          "login": "santevet",
          "token_temp": "'.$_data['token_temp'].'",
          "expiration": "'.$_data['expiration'].'"
        }';
        $crawler = $client->request(
            'POST', '/login/token', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        );
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token_session', $body);
        $this->assertArrayHasKey('validity', $body);
        $this->assertNotEquals(null,$body['token_session']);
        $this->assertNotEquals(null,$body['validity']);
    }
    /*
    * Test generation token avec token session invalide
    */
    public function testGeneratetTokenTempFaillureToken(){
        $client = static::createClient();
        $data = '{
            "token_session": "token_not_exist",
            "login": "santevet"
        }';
        $crawler = $client->request(
            'PUT', '/generate/token/temp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        ); 
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token_temp', $body);
        $this->assertArrayHasKey('expiration', $body);
        $this->assertEquals(null,$body['token_temp']);
        $this->assertEquals(null,$body['expiration']);
    }
    /*
    * Test generation token avec login invalide
    */
    public function testGeneratetTokenTempFaillureLogin(){
        $client = static::createClient();
        $data = '{
            "token_session": "8b0d9c93a3545d0d99f6486208dd3659ec802ee1",
            "login": "error_login"
        }';
        $crawler = $client->request(
            'PUT', '/generate/token/temp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        ); 
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token_temp', $body);
        $this->assertArrayHasKey('expiration', $body);
        $this->assertEquals(null,$body['token_temp']);
        $this->assertEquals(null,$body['expiration']);
    }
    /*
    * Test generation token avec token et login invalide
    */
    public function testGeneratetTokenTempFaillure(){
        $client = static::createClient();
        $data = '{
            "token_session": "token_not_exist",
            "login": "error_login"
        }';
        $crawler = $client->request(
            'PUT', '/generate/token/temp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        ); 
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token_temp', $body);
        $this->assertArrayHasKey('expiration', $body);
        $this->assertEquals(null,$body['token_temp']);
        $this->assertEquals(null,$body['expiration']);
    }
    /*
    * Test generation token avec format body envoyé invalide invalide
    */
    public function testGeneratetTokenTempFaillureFormat(){
        $client = static::createClient();
        $data = "text";
        $crawler = $client->request(
            'PUT', '/generate/token/temp', array(), array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        ); 
        $response = $client->getResponse();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertRegexp('/"message":"format invalide"/', $response->getContent());
    }
    /*
    * Test login token avec login invalide
    */
    public function testLoginTokenFaillureLogin(){
        $client = static::createClient();
        $data = '{
            "token_session": "8b0d9c93a3545d0d99f6486208dd3659ec802ee1",
            "login": "santevet"
        }';
        $crawler = $client->request(
            'PUT', '/generate/token/temp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        ); 
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $_data = json_decode($response->getContent(), true);
        
        $data = '{
          "login": "error_login",
          "token_temp": "'.$_data['token_temp'].'",
          "expiration": "'.$_data['expiration'].'"
        }';
        $crawler = $client->request(
            'POST', '/login/token', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        );
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token_session', $body);
        $this->assertArrayHasKey('validity', $body);
        $this->assertEquals(null,$body['token_session']);
        $this->assertEquals(null,$body['validity']);
    }
    /*
    * Test login token avec date expiration invalide
    */
    public function testLoginTokenFaillureExpired(){
         $client = static::createClient();
        $data = '{
            "token_session": "8b0d9c93a3545d0d99f6486208dd3659ec802ee1",
            "login": "santevet"
        }';
        $crawler = $client->request(
            'PUT', '/generate/token/temp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        ); 
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $_data = json_decode($response->getContent(), true);
        
        $data = '{
          "login": "santevet",
          "token_temp": "'.$_data['token_temp'].'",
          "expiration": "2015-01-01 00:00:00"
        }';
        $crawler = $client->request(
            'POST', '/login/token', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        );
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token_session', $body);
        $this->assertArrayHasKey('validity', $body);
        $this->assertEquals(null,$body['token_session']);
        $this->assertEquals(null,$body['validity']);
    }
    /*
    * Test login token avec token temporaire invalide
    */
    public function testLoginTokenFaillureTokenTemp(){
        $client = static::createClient();
        $data = '{
            "token_session": "8b0d9c93a3545d0d99f6486208dd3659ec802ee1",
            "login": "santevet"
        }';
        $crawler = $client->request(
            'PUT', '/generate/token/temp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        ); 
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $_data = json_decode($response->getContent(), true);
        
        $data = '{
          "login": "santevet",
          "token_temp": "error_token_temp",
          "expiration": "'.$_data['expiration'].'"
        }';
        $crawler = $client->request(
            'POST', '/login/token', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        );
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token_session', $body);
        $this->assertArrayHasKey('validity', $body);
        $this->assertEquals(null,$body['token_session']);
        $this->assertEquals(null,$body['validity']);
    }
    /*
    * Test login token avec login/token_temporaire/expiration invalide
    */
    public function testLoginTokenFaillure(){
        $client = static::createClient();
        $data = '{
          "login": "error_login",
          "token_temp": "error_token_temp",
          "expiration": "2010-02-02 05:00:00"
        }';
        $crawler = $client->request(
            'POST', '/login/token', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        );
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token_session', $body);
        $this->assertArrayHasKey('validity', $body);
        $this->assertEquals(null,$body['token_session']);
        $this->assertEquals(null,$body['validity']);
    }
    /*
    * Test login token avec format de données envoyés invalide
    */
    public function testLoginTokenFaillureFormat(){
        $client = static::createClient();
        $data = "text";
        $crawler = $client->request(
            'POST', '/login/token', array(), array(),
            array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        ); 
        $response = $client->getResponse();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertRegexp('/"message":"format invalide"/', $response->getContent());
    }
    /*
    * Test generation si le token n'existe pas
    * Car il se peut qu'il existe déja un token temporaire au momen du génération de token temporaire, dans ce cas, le token temporaire est remplacé par un nouveau
    */
    public function testGeneratetTokenTempWithNoExistTokenTemp(){
        $client = static::createClient();
        $data = '{
            "token_session": "8b0d9c93a3545d0d99f6486208dd3659ec802ee1",
            "login": "santevet"
        }';
        $crawler = $client->request(
            'PUT', '/generate/token/temp', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            $data
        ); 
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $body = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token_temp', $body);
        $this->assertArrayHasKey('expiration', $body);
        $this->assertNotEquals(null,$body['token_temp']);
        $this->assertNotEquals(null,$body['expiration']);
        return $body;
    }
}
