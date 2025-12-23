<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\URL;

class MailTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_navigate_to_verification_link()
    {
        // 通知をモック
        Notification::fake();

        $user = User::factory()->unverified()->create();

        // 登録後に通知が送られる
        Notification::assertNothingSent();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/verify') 
                ->assertSee('認証はこちらから') 
                ->click('@verify-email-button') 
                ->pause(1000);

            // 実際のメール認証リンクにリダイレクトされることを確認
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1($user->email)]
            );

            $browser->assertPathIs(parse_url($verificationUrl, PHP_URL_PATH));
        });
    }
}
