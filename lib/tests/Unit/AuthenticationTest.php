<?php

namespace Tests\Unit;

use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_register_a_new_user()
    {
        $response = $this->postJson('/api/register', [
            'name'     => 'Yuri Miranda Fialho',
            'email'    => 'yuri@example.com',
            'password' => 'secret',
            'phone'    => '(51) 99988-7766'
        ]);

        $result = json_decode($response->getContent(), true);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User registered.',
                     'data'    => $result['data']
                 ]);
    }
}
