<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('has the default api page')->get('/api/')->assertStatus(200)->assertJson(['current_date'=>now()->toDateString()]);
it('can register new user')->post('/api/register',['name'=>'admin','email'=>'admin@admin.com','password'=>'admin'])
    ->assertStatus(201)
    ->assertJson(['message'=>'User registered successfully.']);
it('can login with registered user')->defer(fn()=> User::create(['name'=>'admin','email'=>'admin@admin.com','password'=>'admin']))
    ->post('/api/login',['email'=>'admin@admin.com','password'=>'admin'])
    ->assertStatus(200)
    ->assertJsonStructure(['message','token']);
it('can not login with invalid credentials')->defer(fn()=>User::factory()->create())
    ->post('/api/login',['email'=>'test@test.com','password'=>'test'])
    ->assertStatus(401)
    ->assertJson(['message'=>'Invalid credentials.']);
it('can get user info with registered user', function(){
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/api/user');
    $response->assertStatus(200);
    $response->assertJsonStructure(['data']);
    $response->assertJson(['data'=>['name'=>$user->name,'email'=>$user->email,'last_login'=>$user->updated_at->toDateTimeString()]]);
});
