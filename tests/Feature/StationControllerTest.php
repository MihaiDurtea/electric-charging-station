<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class StationControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        // seed the database
        $this->artisan('db:seed');
        // alternatively you can call
        // $this->seed();
    }

    public function testCreateAndGet()
    {
        $response = $this->json('get', 'api/stations', [
            'name' => 'test api 6',
            'latitude' => '45.65',
            'longitude' => '34.45',
            'company_id' => '1',
            'address' => 'cluj'
        ]);

        print_r($response->json());

        $response->assertStatus(201)
            ->assertEquals([
                'created' => true,
            ]);
//        $response = $this->json('GET', 'api/stations');
//
//        print_r($response->json());

    }

    public function testStationGetRequest()
    {

        $this->json('get', 'api/stations')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'latitude',
                            'longitude',
                            'company_id',
                            'adzzzzzzress',
                            'created_at'
                        ]
                    ]
                ]
            );
    }
}
