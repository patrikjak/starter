<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class WithoutAuthTest extends TestCase
{
    #[DefineEnvironment('disableAuth')]
    public function testDashboardAccessibleWithoutAuth(): void
    {
        $this->get(route('admin.dashboard'))->assertOk();
    }

    #[DefineEnvironment('disableAuth')]
    public function testLoginReturns404WhenAuthDisabled(): void
    {
        $this->get('/login')->assertNotFound();
    }

    #[DefineEnvironment('disableAuth')]
    public function testRegisterReturns404WhenAuthDisabled(): void
    {
        $this->get('/register')->assertNotFound();
    }

    #[DefineEnvironment('disableAuth')]
    public function testApiLoginReturns404WhenAuthDisabled(): void
    {
        $this->postJson('/api/login')->assertNotFound();
    }

    #[DefineEnvironment('disableAuth')]
    public function testApiLogoutReturns404WhenAuthDisabled(): void
    {
        $this->postJson('/api/logout')->assertNotFound();
    }

    #[DefineEnvironment('disableAuth')]
    public function testProfileReturns404WhenAuthDisabled(): void
    {
        $this->get('/admin/profile')->assertNotFound();
    }

    #[DefineEnvironment('disableAuth')]
    public function testChangePasswordReturns404WhenAuthDisabled(): void
    {
        $this->get('/admin/change-password')->assertNotFound();
    }
}
