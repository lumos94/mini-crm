<?php
declare(strict_types=1);

namespace Tests\Unit\Resources;

use Illuminate\Http\Request;
use Tests\TestCase;
use App\Client;
use App\Http\Resources\ClientResource;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ClientResourceTest
 *
 * @covers \App\Http\Resources\ClientResource
 */
class ClientResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the ClientResource.
     *
     * @covers \App\Http\Resources\ClientResource::toArray
     */
    public function test_it_transforms_client_resource_correctly(): void
    {
        $client = factory(Client::class)->create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'john.doe@example.com',
            'avatar'     => 'avatars/avatar.png',
        ]);

        // Create a mock request
        $request = Request::create('/api/clients', 'GET');

        $resource = new ClientResource($client);

        $resourceArray = $resource->toArray($request); // Pass a valid Request object

        $this->assertEquals([
            'id'          => $client->id,
            'first_name'  => 'John',
            'last_name'   => 'Doe',
            'email'       => 'john.doe@example.com',
            'avatar'      => 'avatars/avatar.png',
            'avatar_url'  => asset('storage/avatars/avatar.png'),
            'created_at'  => $client->created_at,
            'updated_at'  => $client->updated_at,
        ], $resourceArray);
    }
}
