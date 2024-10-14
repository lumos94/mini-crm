<?php
declare(strict_types=1);

namespace Tests\Unit\Requests;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreClientRequest;

/**
 * Class StoreClientRequestTest
 *
 * @covers \App\Http\Requests\StoreClientRequest
 */
class StoreClientRequestTest extends TestCase
{
    /**
     * Test that validation passes with valid data.
     *
     * @covers \App\Http\Requests\StoreClientRequest::rules
     */
    public function test_validation_passes_with_valid_data(): void
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'avatar' => \Illuminate\Http\UploadedFile::fake()->image('avatar.png', 100, 100),
        ];

        $request = new StoreClientRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes());
    }

    /**
     * Test that validation fails when first_name is missing.
     *
     * @covers \App\Http\Requests\StoreClientRequest::rules
     */
    public function test_validation_fails_without_first_name(): void
    {
        $data = [
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'avatar' => \Illuminate\Http\UploadedFile::fake()->image('avatar.png', 100, 100),
        ];

        $request = new StoreClientRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('first_name', $validator->errors()->toArray());
    }

    /**
     * Test that validation fails when email is not unique.
     *
     * @covers \App\Http\Requests\StoreClientRequest::rules
     */
    public function test_validation_fails_with_duplicate_email(): void
    {
        // create a sclient with the email to be checked if it is duplciated
        factory(\App\Client::class)->create(['email' => 'john.doe@example.com']);

        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'avatar' => \Illuminate\Http\UploadedFile::fake()->image('avatar.png', 100, 100),
        ];

        $request = new StoreClientRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /**
     * Test that validation fails when avatar dimensions are incorrect.
     *
     * @covers \App\Http\Requests\StoreClientRequest::rules
     */
    public function test_validation_fails_with_incorrect_avatar_dimensions(): void
    {
        //image size must be 100x100

        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'avatar' => \Illuminate\Http\UploadedFile::fake()->image('avatar.png', 50, 50)
        ];

        $request = new StoreClientRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('avatar', $validator->errors()->toArray());
    }
}
