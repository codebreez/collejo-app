<?php

namespace Collejo\App\Modules\Auth\Tests\Browser;

use Collejo\App\Modules\ACL\Models\User;
use Collejo\Foundation\Testing\TestCase;

class LoginControllerTest extends TestCase
{
    /**
     * @throws \Exception
     * @throws \Throwable
     * @covers \Collejo\App\Modules\Auth\Http\Controllers\LoginController::showLoginForm()
     */
    public function testShowLoginForm()
    {
        $this->runDatabaseMigrations();

        $response = $this->get(route('login'));

        $this->assertGuest();

        $response->assertStatus(200);
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     * @covers \Collejo\App\Modules\Auth\Http\Controllers\LoginController::login()
     * @covers \Collejo\App\Modules\Auth\Http\Controllers\LoginController::authenticated()
     */
    public function testLoginSuccess()
    {
        $user = factory(User::class)->create();

        $credentials = [
            'email'    => $user->email,
            'password' => 123,
        ];

        $response = $this->post(route('login'), $credentials);

        $this->assertCredentials($credentials);

        // Assert redirect
        $response->assertStatus(302);
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     * @covers \Collejo\App\Modules\Auth\Http\Controllers\LoginController::sendFailedLoginResponse()
     */
    public function testLoginFailed()
    {
        $user = factory(User::class)->create();

        $credentials = [
            'email'    => $user->email,
            'password' => 'wrong password',
        ];

        $response = $this->post(route('login'), $credentials);

        $this->assertInvalidCredentials($credentials);

        // Assert error message response
        $response->assertStatus(200);
    }
}
