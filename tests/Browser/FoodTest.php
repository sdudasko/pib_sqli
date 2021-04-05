<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FoodTest extends DuskTestCase
{
//    use DatabaseMigrations;

    protected $env_debug_on = [
        'true' => [
            '500' => 'Syntax error:'
        ],
        'false' => [
            '500' => 'SERVER ERROR'
        ]
    ];

    protected $secondOrderSQLiQueries = [
        "--' UNION SELECT 1, null, null, null, null, null, null, null, null, null, null, null, version(), null, null, null, null, null, null, null--'"
    ];


    public function testSecondOrderSQLi()
    {
        $this->browse(function (Browser $browser) {

            // TODO - Test all parameters

            $maliciousString = $this->secondOrderSQLiQueries[0];

//            $browser->visit('/create')
//                ->typeSlowly('email', Str::random(5).'@gmail.com', 10)
//                ->typeSlowly('last_name', 'xuser last_name', 10)
//                ->typeSlowly('title', 'xuser title', 10)
//                ->typeSlowly('price', 10, 10)
//                ->typeSlowly('description', 'description', 10)
//                ->typeSlowly('first_name', $maliciousString, 10)
//                ->press('Submit')
//            ;

            // TODO - lets suppose we got 500 - check we really have 500
//            TODO nech nie je page fixne - http://pib_sqli.test/sqli?page=4

//            $link = $browser->visit('/sqli?page=5')
//                ->script('return $("table").first().find("td").last().find("a").attr("href")')[0];

            $browser
                ->visit('/sqli?page=1')
                ->scrollToElement('a:contains(UNION SELECT):last');

            $browser->visit('/sqli?page=1')
                ->pause(3000)
                ->clickLink('UNION SELECT')
                ->pause(3000)
                ->assertSee("PostgreSQL")
                ->pause(3000)
            ;


            $browser->storeConsoleLog('_test_' . now());
        });
    }
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function testCreateFormParams()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/create')
                ->typeSlowly('email', 'xdudasko@stuba.sk', 20)
                ->typeSlowly('first_name', 'xuser first_name', 20)
                ->typeSlowly('last_name', 'xuser last_name', 20)
                ->typeSlowly('title', 'xuser title', 20)
                ->typeSlowly('price', 10, 20)
                ->typeSlowly('description', 'description', 20)
                ->press('Submit');
        });
    }

    public function testListingSearch()
    {
        $debug = getenv('APP_DEBUG');
        $expected_error_text_500 = $this->env_debug_on[$debug][500];

        $this->browse(function (Browser $browser) use ($expected_error_text_500) {

            // TODO - Test all parameters

            $browser->visit('/sqli')
                ->typeSlowly('q', '\'', 20)
                ->waitFor('', 2000)
                ->press('Search')
                ->assertSee($expected_error_text_500)
            ;

            $this->assertDatabaseHas('users', [
                'email' => 'xdudasko@stuba.sk',
            ]);

            $browser->storeConsoleLog('_test_' . now());
        });
    }
}
