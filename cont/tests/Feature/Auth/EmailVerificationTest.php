<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_is_disabled(): void
    {
        $this->markTestSkipped('Email verification is disabled (email_verified_at removed and routes disabled).');
    }
}
