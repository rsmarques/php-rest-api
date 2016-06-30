<?php

use PHPUnit\Framework\TestCase;

class ContactsTest extends TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client   = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost'
        ]);
    }

    public $testData    = [
        "57673e7a02243" => ['name' => 'Michal', 'phone_number' => '506088156',  'address' => 'Michalowskiego 41'],
        "57673e7a8e093" => ['name' => 'Marcin', 'phone_number' => '502145785',  'address' => 'Opata Rybickiego 1'],
        "57673e7b2a6ef" => ['name' => 'Piotr',  'phone_number' => '504212369',  'address' => 'Horacego 23'],
        "57673e7bb8cbf" => ['name' => 'Albert', 'phone_number' => '605458547',  'address' => 'Jan Pawla 67'],
    ];

    public function test()
    {
        // Testing delete all
        $response   = $this->client->delete('/contacts/', []);
        $this->assertEquals(200, $response->getStatusCode());

        // Testing creating a list of items
        $response   = $this->client->put('/contacts/', ['form_params' => $this->testData]);
        $this->assertEquals(200, $response->getStatusCode());

        $data       = json_decode($response->getBody(), true);
        $this->assertEquals($data, $this->testData);

        // Testing retrieving an item (first element)
        reset($this->testData);
        $response   = $this->client->get('/contacts/' . key($this->testData), []);
        $this->assertEquals(200, $response->getStatusCode());

        $data       = json_decode($response->getBody(), true);
        $this->assertEquals($data, current($this->testData));

        // Testing retrieving all items
        $response   = $this->client->get('/contacts/', []);
        $this->assertEquals(200, $response->getStatusCode());

        $data       = json_decode($response->getBody(), true);
        $this->assertEquals($data, $this->testData);

        // Testing deleting one element (last element)
        end($this->testData);
        $response   = $this->client->delete('/contacts/' . key($this->testData), []);
        $this->assertEquals(200, $response->getStatusCode());

        $response   = $this->client->get('/contacts/' . key($this->testData), []);
        $data       = json_decode($response->getBody(), true);
        // assuring the item is no longer in the db
        $this->assertEquals($data, []);

        // Testing updating one element (last element into first)
        reset($this->testData);
        $response   = $this->client->put('/contacts/' . key($this->testData), ['form_params' => end($this->testData)]);
        $this->assertEquals(200, $response->getStatusCode());

        $data       = json_decode($response->getBody(), true);
        $this->assertEquals($data, current($this->testData));

        // Testing creating an element
        $response   = $this->client->post('/contacts/', ['form_params' => reset($this->testData)]);
        $this->assertEquals(200, $response->getStatusCode());

        $data       = json_decode($response->getBody(), true);
        $this->assertEquals($data, current($this->testData));

        $response   = $this->client->get('/contacts/', []);
        $data       = json_decode($response->getBody(), true);
        // assuring last item is equal than the one created
        $this->assertEquals(end($data), current($this->testData));

    }
}
