<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User; // Adjust this import if your User model is located elsewhere.
use Illuminate\Support\Facades\Artisan;
use RifRocket\FilamentMailbox\Models\Email;

class MailboxTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the Mailbox Dashboard page loads correctly.
     *
     * @return void
     */
    public function testMailboxDashboardLoads()
    {
        // Create a test user (ensure you have a User factory defined).
        $user = User::factory()->create();

        // Acting as an authenticated user, access the dashboard page.
        // Adjust the route name or URL as needed based on your Filament configuration.
        $response = $this->actingAs($user)->get(route('filament.pages.mailbox-dashboard'));
        
        $response->assertStatus(200)
                 ->assertSee('Mailbox Dashboard');
    }

    /**
     * Test that the fetch emails command runs successfully.
     *
     * @return void
     */
    public function testFetchEmailsCommand()
    {
        // Run the mailbox:fetch-emails command.
        $exitCode = Artisan::call('mailbox:fetch-emails', ['--folder' => 'INBOX']);
        
        // Ensure that the command exits with a success code.
        $this->assertEquals(0, $exitCode);

        // Optionally, assert that emails were inserted into the database.
        // Here we simply check that the emails table exists and has zero records,
        // which may be the expected default in a test environment.
        $this->assertDatabaseCount('emails', 0);
    }
}

