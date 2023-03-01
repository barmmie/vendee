<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Enum\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{

    use WithFaker, DatabaseTransactions;

    /**
     * @dataProvider roleData
     */
    public function test_it_can_authenticate_a_user($role)
    {
        $username = $this->faker->userName();
        $password = $this->faker->password();

        $user = User::factory()->create([
            'username' => $username,
            'password' => Hash::make($password),
            'role' => $role
        ]);

        $this->postJson(route('auth.store'), [
            'username' => $username,
            'password' => $password,
        ])->assertSuccessful()
            ->assertJsonPath('token', fn (string $token) => hash('sha256',$token) === $user->refresh()->api_token);
    }


    /**
     * @dataProvider roleData
     */
    public function test_it_does_not_authenticate_a_user_with_invalid_password($role)
    {
        $username = $this->faker->userName();
        $password = 'correctpassword';

        $user = User::factory()->withPassword($password)->create([
            'username' => $username,
            'role' => $role
        ]);

        $this->postJson(route('auth.store'), [
            'username' => $username,
            'password' => 'wrongpassword',
        ])->assertBadRequest();

    }

    /**
     * @dataProvider roleData
     */
    public function test_it_does_not_authenticate_an_already_logged_in_user($role)
    {
        $username = $this->faker->userName();
        $password = $this->faker->password();

        $user = User::factory()->withPassword($password)->withAlreadyAuthenticatedToken()->create([
            'username' => $username,
            'role' => $role
        ]);

        $this->postJson(route('auth.store'), [
            'username' => $username,
            'password' => $password,
        ])->assertStatus(Response::HTTP_CONFLICT);
    }

    /**
     * @dataProvider roleData
     */
    public function test_it_can_log_a_user_out($role)
    {

        $user = User::factory()->withAlreadyAuthenticatedToken()->create([
            'role' => $role
        ]);

        $this->actingAs($user)->deleteJson(route('auth.delete'))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertNull($user->api_token);
    }

    /**
     * @dataProvider roleData
     */
    public function test_it_can_log_user_sessions_out($role)
    {

        $password = $this->faker->password();
        $user = User::factory()->withPassword($password)->withAlreadyAuthenticatedToken()->create([
            'role' => $role
        ]);

        $this->actingAs($user, 'web')->deleteJson(route('auth.destroy'))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertNull($user->api_token);
    }

    public function roleData() {
        return array_map(fn($roleValue) => [$roleValue], Role::values());
    }
}
