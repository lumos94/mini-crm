<?php
declare(strict_types=1);

use App\User;
use Tests\TestCase;
use App\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class ClientControllerTest
 *
 * @covers \App\Http\Controllers\ClientController
 */
class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up method to initialize resources before running tests.
     *
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Fake the storage disk for file uploads
        Storage::fake('public');
    }

    /**
     * Test if the client view is returned successfully.
     *
     * @covers \App\Http\Controllers\ClientController::index
     * @return void
     */
    public function test_it_displays_the_clients_view()
    {
        //act as admin to see the clients view
        $response = $this->actingAs($this->adminUser())->get('/clients');

        $response->assertStatus(200);
        $response->assertViewIs('clients');
    }

    /**
     * Test if paginated clients are fetched successfully with avatar URLs.
     *
     * @covers \App\Http\Controllers\ClientController::getClients
     */
    public function test_it_fetches_paginated_clients_with_avatar_urls(): void
    {
        factory(Client::class, 15)->create(); // Create 15 fake clients

        $response = $this->actingAs($this->adminUser())->getJson('/api/clients');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data', 'links', 'meta',
        ]);

        $clients = $response->json('data');

        // Checks paginationb
        $this->assertCount(10, $clients);

        // Check if the avatar URL is correct
        $this->assertStringContainsString('storage/avatars', $clients[0]['avatar_url']);
    }

    /**
     * Test if all clients are fetched successfully without the pagination.
     *
     * @covers \App\Http\Controllers\ClientController::getAllClients
     */
    public function test_it_fetches_all_clients_without_pagination(): void
    {
        factory(Client::class, 5)->create();

        $response = $this->actingAs($this->adminUser())->getJson('/api/clients/all');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    /**
     * Test if a new client is created successfully.
     *
     * @covers \App\Http\Controllers\ClientController::store
     * @return void
     */
    public function test_it_creates_a_new_client()
    {
        Storage::fake('public');

        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'avatar' => UploadedFile::fake()->image('avatar.png', 100, 100),
        ];

        $response = $this->actingAs($this->adminUser())->postJson('/api/clients', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('clients', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);

        Storage::disk('public')->assertExists('avatars/' . $data['avatar']->hashName());
    }

    /**
     * Test if a specific client is displayed successfully.
     *
     * @covers \App\Http\Controllers\ClientController::show
     * @return void
     */
    public function test_it_displays_a_specific_client()
    {
        $client = factory(Client::class)->create();

        $response = $this->actingAs($this->adminUser())->getJson("/api/clients/{$client->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $client->id,
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'email' => $client->email,
        ]);
    }

    /**
     * Test if an existing client is updated successfully.
     *
     * @covers \App\Http\Controllers\ClientController::update
     */
    public function test_it_updates_an_existing_client(): void
    {
        $client = factory(Client::class)->create();

        //todo: use faker to randomly create the data and store them in varibles
        $updateData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
        ];

        $response = $this->actingAs($this->adminUser())->putJson("/api/clients/{$client->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
        ]);
    }

    /**
     * Test if an existing client is deleted successfully.
     *
     * @covers \App\Http\Controllers\ClientController::destroy
     */
    public function test_it_deletes_an_existing_client(): void
    {
        $client = factory(Client::class)->create();

        $response = $this->actingAs($this->adminUser())->deleteJson("/api/clients/{$client->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }

    /**
     * Helper method to authenticate an admin user for tests.
     *
     */
    protected function adminUser(): User
    {
        return factory(User::class)->create(['is_admin' => true]);
    }
}
