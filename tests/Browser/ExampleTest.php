<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/be/login')
                    ->type('email', 'admin@larajs.com')
                    ->type('password', 'admin123')
                    ->press('Login')
                    ->screenshot('dashboard');
        });
    }
}
