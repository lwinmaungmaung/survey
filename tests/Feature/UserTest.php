<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('has the default api page', function () {
    $this->get(route('start'))->assertStatus(200)->assertJson(['current_date' => now()->toDateString()]);
});
it('can register new user', function () {
    $this->post(route('user.register'), ['name' => 'admin', 'email' => 'admin@admin.com', 'password' => 'admin'])
        ->assertStatus(201)
        ->assertJson(['message' => 'User registered successfully.']);
});
it('cannot register duplicate user', function () {
    $this->post(route('user.register'), ['name' => 'admin', 'email' => 'admin@admin.com', 'password' => 'admin'])
        ->assertStatus(201)
        ->assertJson(['message' => 'User registered successfully.']);
    $this->post(route('user.register'), ['name' => 'admin', 'email' => 'admin@admin.com', 'password' => 'admin'])
        ->assertStatus(409)
        ->assertJson(['message' => 'User already exists.']);
});
it('can login with registered user', function () {
    $user = User::create(['name' => 'admin', 'email' => 'admin@admin.com', 'password' => 'admin']);
    $this->post(route('user.login'), ['email' => $user->email, 'password' => 'admin'])
        ->assertStatus(200)
        ->assertJsonStructure(['message', 'token']);
});
it('can not login with invalid credentials', function () {
    User::factory()->create();
    $this
        ->post(route('user.login'), ['email' => 'test@test.com', 'password' => 'test'])
        ->assertStatus(401)
        ->assertJson(['message' => 'Invalid credentials.']);
});

it('can only get one login token', function () {
    $user = User::factory()->create();
    $response = $this->post(route('user.login'), ['email' => $user->email, 'password' => 'password']);
    $response->assertOk();
    $response->assertJsonStructure(['message', 'token']);
    $res1 = $response->json('token');
    $this->assertDatabaseCount('personal_access_tokens', 1);
    $response = $this->post(route('user.login'), ['email' => $user->email, 'password' => 'password']);
    $this->assertDatabaseCount('personal_access_tokens', 1);
    $response->assertOk();
    $res2 = $response->json('token');
    $this->assertNotEquals($res1, $res2);
});

it('can get user info with registered user', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('user.profile'));
    $response->assertStatus(200);
    $response->assertJsonStructure(['data']);
    $response->assertJson(['data' => ['name' => $user->name, 'email' => $user->email, 'last_login' => $user->updated_at->toDateTimeString()]]);
});

it('cannot create a form with register user if there is no fields', function(){
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post(route('form.store'), ['name' => 'test', 'description' => 'test']);
    $response->assertStatus(302);
});

it('can create a form and database accessibility with register user', function(){
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post(route('form.store'),
        [
            'name' => 'test',
            'description' => 'test',
            'fields' => [
                ['name' => 'test', 'type' => 'text', 'required' => true],
                ['name' => 'test2', 'type' => 'radio', 'required' => false, 'options' => ['test', 'test2'],'default' => 'test2']
            ]
        ]
    );
    $response->assertStatus(201);
    $response->assertJson(['message' => 'Form created successfully.']);
    $this->assertDatabaseHas('forms', ['name' => 'test', 'description' => 'test']);
    $this->assertDatabaseHas('form_fields', ['name' => 'test', 'type' => 'text', 'required' => true]);
    $this->assertDatabaseHas('form_fields', ['name' => 'test2', 'type' => 'radio', 'required' => false,'options' => json_encode(['test', 'test2'], JSON_THROW_ON_ERROR),'default' => 'test2']);
});
