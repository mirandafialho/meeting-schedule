<?php

namespace Tests\Feature\Models;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    public string $userAuthToken;

    /**
     * Testing create user
     *
     * @return void
     */
    public function test_create_user()
    {
        User::factory()->create([
            'email'    => 'yuri@yuri.com',
            'password' => 'secret'
        ]);

        $this->assertDatabaseCount('users', 1);
    }

    /**
     * Testing the login
     *
     * @return void
     */
    public function test_login()
    {
        $response = $this->post('/api/login', [
            'username' => 'yuri@example.com',
            'password' => 'secret'
        ]);

        $this->userAuthToken = $response->access_token;

        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    /**
     * Test listing all employees.
     *
     * @return void
     */
    public function test_retrieve_all_employees()
    {
        Employee::factory(10)->create();

        $response = $this->get('/api/employees', [
            'Authorization' => 'Bearer ' . $this->userAuthToken
        ]);

        $result = json_decode($response->getContent(), true);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Found all employees.',
                     'data'    => $result['data']
                 ])
                 ->assertJsonCount(
                     10,
                     $result['data']
                 );
    }
}
