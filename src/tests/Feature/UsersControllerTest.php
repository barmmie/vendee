<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Enum\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UsersControllerTest extends TestCase
{

    use WithFaker, RefreshDatabase;
    /**
     * @dataProvider roleData
     */
    public function test_it_allows_us_to_register_a_user($role): void
    {

        $username = $this->faker->userName();
        $password = $this->faker->password();

        $this->postJson(route('user.store'), [
            'username' => $username,
            'password' => $password,
            'password_confirmation' => $password,
            'role' => $role
        ])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'username' => $username,
            'role' => $role
        ]);

        $user = User::where('username', $username)
            ->first();

        $this->assertTrue(Hash::check($password, $user->password));

    }


    /**
     * @dataProvider invalidRegistrationData
     */
    public function test_a_user_cant_register_with_invalid_registration_data($invalidData, $invalidFields)
    {
        $this->postJson(route('user.store'), $invalidData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors($invalidFields);

        $this->assertDatabaseEmpty('users');
    }

     /**
     * @dataProvider roleData
     */
    public function test_a_user_cant_register_with_duplicate_username($role)
    {

        $username = $this->faker->userName();

        User::factory()->create([
            'username' => $username
        ]);

        $password = $this->faker->password();

        $this->postJson(route('user.store'), [
            'username' => $username,
            'password' => $password,
            'password_confirmation' => $password,
            'role' => $role
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors('username');

    }

    public function test_a_user_can_update_their_password()
    {

        $username = $this->faker->userName();
        $password = $this->faker->password();
        $newPassword = $this->faker->password();

        $user = User::factory()->create([
            'username' => $username,
            'password' => Hash::make($password)
        ]);


        $this->actingAs($user)->putJson(route('user.update', $user->id), [
            'old_password' => $password,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ])->assertSuccessful();

        $user->refresh();

        $this->assertTrue(Hash::check($newPassword, $user->password));

    }

    public function test_a_user_must_cannot_create_a_new_password_with_an_invalid_old_password()
    {

        $username = $this->faker->userName();
        $password = $this->faker->password();
        $newPassword = $this->faker->password();

        $user = User::factory()->create([
            'username' => $username,
            'password' => Hash::make($password)
        ]);


        $this->actingAs($user)->putJson(route('user.update', $user->id), [
            'old_password' => $this->faker->password(),
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['password']);

        $user->refresh();

        $this->assertFalse(Hash::check($newPassword, $user->password));

    }

    public function invalidRegistrationData()
    {
        // [][$invalidData, $invalidValue]
        return [
            [
                ['username' => 'username_cannot_be_blank', 'password' => 'different', 'password_confirmation' => 'password'],
                ['password']
            ],
            [
                [],
                ['username', 'password']
            ],

            [
                ['username' => 'le', 'password' => 'password', 'password_confirmation' => 'password'],
                ['username']
            ],
            [
                ['username' => 'username_cannot_be_blank', 'password' => 'password', 'password_confirmation' => 'password', 'role' => 'some_invalid_role' ],
                ['role']
            ],
        ];
    }

    public function roleData()
    {
        return array_map(fn($roleValue) => [$roleValue], Role::values());
    }

}
