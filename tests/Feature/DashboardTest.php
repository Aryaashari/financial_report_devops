<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_loads_dashboard_and_shows_credit_debit_chart()
    {
        $user = User::first();

        $this->assertNotNull($user, 'No user found in the database. Please insert at least one user.');

        $this->actingAs($user);


        $response = $this->get('/dashboard');

        $response->assertStatus(200);

        $response->assertSee('canvas id="creditDebitChart"', false);


        $response->assertSee('Credit');
        $response->assertSee('Debit');

    }
}
